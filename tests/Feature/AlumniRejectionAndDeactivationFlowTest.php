<?php

namespace Tests\Feature;

use App\Models\AlumniUser;
use App\Models\CmsSetting;
use App\Models\User;
use App\Notifications\AlumniApproved;
use App\Notifications\AlumniRejected;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AlumniRejectionAndDeactivationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    private function createAdmin(): User
    {
        return User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@poba.test',
            'password' => bcrypt('secret123'),
            'role'     => 'superadmin',
        ]);
    }

    private function createAlumni(array $overrides = []): AlumniUser
    {
        return AlumniUser::create(array_merge([
            'full_name'       => 'Test Alumni',
            'email'           => 'alumni@poba.test',
            'password'        => bcrypt('secret123'),
            'phone_number'    => '+923001112233',
            'entry'           => 10,
            'class_year'      => 2015,
            'ccp_no'          => 'CCP-1001',
            'house'           => 'Jinnah',
            'education'       => 'Bachelors',
            'field_of_study'  => 'Computer Science',
            'field_of_work'   => 'Software Engineering',
            'current_city'    => 'Lahore',
            'current_country' => 'Pakistan',
            'status'          => 'pending',
            'is_active'       => false,
            'consent_sharing' => true,
            'agree_terms'     => true,
        ], $overrides));
    }

    public function test_admin_cannot_reject_alumni_without_reason()
    {
        $admin = $this->createAdmin();
        $alumni = $this->createAlumni();

        $response = $this->actingAs($admin, 'web')
            ->post(route('admin.alumni.reject', $alumni->id), [
                'reason' => '',
            ]);

        $response->assertSessionHasErrors('reason');
        $this->assertEquals('pending', $alumni->fresh()->status);
        $this->assertNull($alumni->fresh()->rejection_reason);
    }

    public function test_admin_can_reject_with_reason_and_user_receives_notification()
    {
        Notification::fake();
        $admin = $this->createAdmin();
        $alumni = $this->createAlumni();

        CmsSetting::set('contact_email', 'help@poba.org');
        CmsSetting::set('contact_number', '+92 300 0000000');

        $response = $this->actingAs($admin, 'web')
            ->post(route('admin.alumni.reject', $alumni->id), [
                'reason' => 'CNIC document is illegible. Please provide a clear scan.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $alumni = $alumni->fresh();
        $this->assertEquals('rejected', $alumni->status);
        $this->assertEquals('CNIC document is illegible. Please provide a clear scan.', $alumni->rejection_reason);

        Notification::assertSentTo($alumni, AlumniRejected::class, function ($notification) {
            return $notification->reason === 'CNIC document is illegible. Please provide a clear scan.'
                && $notification->supportEmail === 'help@poba.org'
                && $notification->supportPhone === '+92 300 0000000';
        });
    }

    public function test_rejected_users_tab_displays_rejected_alumni_and_rejection_reason()
    {
        $admin = $this->createAdmin();
        $rejectedUser = $this->createAlumni([
            'full_name'        => 'Rejected Person',
            'email'            => 'rejected@poba.test',
            'status'           => 'rejected',
            'rejection_reason' => 'Duplicate membership application.',
        ]);

        $approvedUser = $this->createAlumni([
            'full_name' => 'Approved Person',
            'email'     => 'approved@poba.test',
            'status'    => 'approved',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.alumni.rejected'));

        $response->assertStatus(200);
        $response->assertSee('Rejected Users');
        $response->assertSee('Rejected Person');
        $response->assertSee('Duplicate membership application.');
        $response->assertDontSee('Approved Person');
    }

    public function test_admin_can_view_edit_and_approve_rejected_alumni()
    {
        Notification::fake();
        $admin = $this->createAdmin();
        $alumni = $this->createAlumni([
            'status'           => 'rejected',
            'rejection_reason' => 'Please update your organization and city.',
        ]);

        // 1. View profile shows rejection banner and reason
        $viewResponse = $this->actingAs($admin, 'web')->get(route('admin.alumni.show', $alumni->id));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Application Rejected');
        $viewResponse->assertSee('Please update your organization and city.');
        $viewResponse->assertSee('Approve Application');

        // 2. Admin edits user's information
        $updateResponse = $this->actingAs($admin, 'web')
            ->put(route('admin.alumni.update', $alumni->id), [
                'full_name'            => 'Corrected Alumni Name',
                'current_city'         => 'Karachi',
                'current_organization' => 'Tech Corp',
            ]);
        $updateResponse->assertSessionHas('success');
        $this->assertEquals('Corrected Alumni Name', $alumni->fresh()->full_name);
        $this->assertEquals('Karachi', $alumni->fresh()->current_city);

        // 3. Admin approves from rejected state
        $approveResponse = $this->actingAs($admin, 'web')
            ->post(route('admin.alumni.approve', $alumni->id));

        $approveResponse->assertSessionHas('success');
        $alumni = $alumni->fresh();
        $this->assertEquals('approved', $alumni->status);
        $this->assertTrue($alumni->is_active);
        $this->assertNull($alumni->rejection_reason);

        Notification::assertSentTo($alumni, AlumniApproved::class);
    }

    public function test_admin_must_provide_reason_when_deactivating_user()
    {
        $admin = $this->createAdmin();
        $alumni = $this->createAlumni([
            'status'    => 'approved',
            'is_active' => true,
        ]);

        // Fail without reason
        $failResponse = $this->actingAs($admin, 'web')
            ->postJson(route('admin.alumni.toggleStatus', $alumni->id), [
                'reason' => '',
            ]);
        $failResponse->assertStatus(422);
        $failResponse->assertJsonValidationErrors('reason');
        $this->assertTrue($alumni->fresh()->is_active);

        // Succeed with reason
        $successResponse = $this->actingAs($admin, 'web')
            ->postJson(route('admin.alumni.toggleStatus', $alumni->id), [
                'reason' => 'Violation of community guidelines.',
            ]);
        $successResponse->assertStatus(200);
        $successResponse->assertJson([
            'success'   => true,
            'is_active' => false,
            'reason'    => 'Violation of community guidelines.',
        ]);

        $alumni = $alumni->fresh();
        $this->assertFalse($alumni->is_active);
        $this->assertEquals('Violation of community guidelines.', $alumni->deactivation_reason);

        // Reactivating clears reason and sets is_active to true
        $reactivateResponse = $this->actingAs($admin, 'web')
            ->postJson(route('admin.alumni.toggleStatus', $alumni->id), []);
        $reactivateResponse->assertStatus(200);
        $reactivateResponse->assertJson([
            'success'   => true,
            'is_active' => true,
            'reason'    => null,
        ]);
        $this->assertTrue($alumni->fresh()->is_active);
        $this->assertNull($alumni->fresh()->deactivation_reason);
    }

    public function test_user_list_status_filter_filters_active_and_deactivated_alumni()
    {
        $admin = $this->createAdmin();
        $activeUser = $this->createAlumni([
            'full_name' => 'Active User',
            'email'     => 'active@poba.test',
            'status'    => 'approved',
            'is_active' => true,
        ]);
        $deactivatedUser = $this->createAlumni([
            'full_name'           => 'Deactivated User',
            'email'               => 'deactivated@poba.test',
            'status'              => 'approved',
            'is_active'           => false,
            'deactivation_reason' => 'Requested account pause.',
        ]);

        // 1. All
        $resAll = $this->actingAs($admin, 'web')->get(route('admin.alumni.index'));
        $resAll->assertSee('Active User');
        $resAll->assertSee('Deactivated User');

        // 2. Active filter
        $resActive = $this->actingAs($admin, 'web')->get(route('admin.alumni.index', ['status' => 'active']));
        $resActive->assertSee('Active User');
        $resActive->assertDontSee('Deactivated User');

        // 3. Deactivated filter
        $resDeactivated = $this->actingAs($admin, 'web')->get(route('admin.alumni.index', ['status' => 'deactivated']));
        $resDeactivated->assertDontSee('Active User');
        $resDeactivated->assertSee('Deactivated User');
    }

    public function test_rejected_and_deactivated_users_are_blocked_on_login()
    {
        CmsSetting::set('contact_email', 'support@poba.com');
        CmsSetting::set('contact_number', '+92 21 9999999');

        $rejectedUser = $this->createAlumni([
            'email'            => 'rejected.login@poba.test',
            'password'         => bcrypt('password123'),
            'status'           => 'rejected',
            'rejection_reason' => 'Invalid college registration number.',
        ]);

        $deactivatedUser = $this->createAlumni([
            'email'               => 'deactivated.login@poba.test',
            'password'            => bcrypt('password123'),
            'status'              => 'approved',
            'is_active'           => false,
            'deactivation_reason' => 'Pending annual dues clearance.',
        ]);

        // Attempt login with rejected account
        $resReject = $this->post(route('login.post'), [
            'email'    => 'rejected.login@poba.test',
            'password' => 'password123',
        ]);
        $resReject->assertSessionHasErrors('email');
        $resReject->assertSessionHas('blocked_modal', function ($modal) {
            return $modal['type'] === 'rejected'
                && str_contains($modal['reason'], 'Invalid college registration number')
                && str_contains($modal['email'], 'support@poba.com');
        });
        $this->assertFalse(Auth::guard('alumni')->check());

        // Attempt login with deactivated account
        $resDeact = $this->post(route('login.post'), [
            'email'    => 'deactivated.login@poba.test',
            'password' => 'password123',
        ]);
        $resDeact->assertSessionHasErrors('email');
        $resDeact->assertSessionHas('blocked_modal', function ($modal) {
            return $modal['type'] === 'deactivated'
                && str_contains($modal['reason'], 'Pending annual dues clearance')
                && str_contains($modal['phone'], '+92 21 9999999');
        });
        $this->assertFalse(Auth::guard('alumni')->check());
    }

    public function test_deactivated_user_is_immediately_logged_out_by_middleware()
    {
        $alumni = $this->createAlumni([
            'status'              => 'approved',
            'is_active'           => false,
            'deactivation_reason' => 'Account frozen by administrator.',
        ]);

        // Acting as alumni whose is_active is false hitting alumni protected route
        $response = $this->actingAs($alumni, 'alumni')->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('blocked_modal', function ($modal) {
            return $modal['type'] === 'deactivated'
                && str_contains($modal['reason'], 'Account frozen by administrator');
        });
        $this->assertFalse(Auth::guard('alumni')->check());
    }

    public function test_rejected_and_deactivated_users_are_blocked_from_registering_with_field_specific_errors()
    {
        $this->createAlumni([
            'email'            => 'existing.rejected@poba.test',
            'ccp_no'           => 'CCP-REJECT-99',
            'status'           => 'rejected',
            'rejection_reason' => 'Information mismatch with college records.',
        ]);

        // 1. Same CCP No but DIFFERENT email -> Error must be on 'ccp_no', NOT 'email'
        $responseCcp = $this->post(route('member.store'), [
            'email'  => 'brand.new.email@poba.test',
            'ccp_no' => 'CCP-REJECT-99',
        ]);

        $responseCcp->assertSessionHasErrors('ccp_no');
        $responseCcp->assertSessionDoesntHaveErrors('email');
        $responseCcp->assertSessionHas('blocked_modal', function ($modal) {
            return $modal['type'] === 'rejected'
                && $modal['field'] === 'ccp_no'
                && str_contains($modal['reason'], 'Information mismatch with college records');
        });

        // 2. Same email but different CCP No -> Error must be on 'email'
        $responseEmail = $this->post(route('member.store'), [
            'email'  => 'existing.rejected@poba.test',
            'ccp_no' => 'CCP-UNIQUE-777',
        ]);

        $responseEmail->assertSessionHasErrors('email');
        $responseEmail->assertSessionDoesntHaveErrors('ccp_no');
        $responseEmail->assertSessionHas('blocked_modal', function ($modal) {
            return $modal['type'] === 'rejected'
                && $modal['field'] === 'email';
        });
    }

    public function test_alumni_show_renders_custom_approve_modal_instead_of_browser_confirm()
    {
        $admin = $this->createAdmin();
        $alumni = $this->createAlumni([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.alumni.show', $alumni->id));

        $response->assertStatus(200);
        $response->assertSee('onclick="openApproveModal()"', false);
        $response->assertSee('id="approveModal"', false);
        $response->assertSee('Confirm Approval', false);
        $response->assertDontSee("onsubmit=\"return confirm('Approve", false);
    }
}
