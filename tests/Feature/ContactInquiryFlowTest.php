<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\ContactInquiry;
use App\Models\User;
use App\Notifications\ContactInquiryConfirmationNotification;
use App\Notifications\ContactInquiryReplyNotification;
use App\Notifications\NewContactInquiryAdminNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactInquiryFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    private function createAdmin(array $permissions = ['contact']): User
    {
        return User::create([
            'name'        => 'Admin User',
            'email'       => 'admin@poba.test',
            'password'    => bcrypt('secret123'),
            'role'        => 'admin',
            'permissions' => $permissions,
        ]);
    }

    public function test_contact_form_submission_persists_inquiry_and_dispatches_emails()
    {
        Notification::fake();
        CmsSetting::set('contact_email', 'admin.inbox@poba.org');

        $payload = [
            'first_name'    => 'Ali',
            'last_name'     => 'Khan',
            'email_address' => 'ali.khan@example.com',
            'phone_ext'     => '+92',
            'phone'         => '3001234567',
            'message'       => 'Hello, I would like to inquire about upcoming alumni chapter meetups in Islamabad.',
        ];

        $response = $this->post(route('contact.send'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify record in database
        $this->assertDatabaseHas('contact_inquiries', [
            'first_name'    => 'Ali',
            'last_name'     => 'Khan',
            'email_address' => 'ali.khan@example.com',
            'phone_ext'     => '+92',
            'phone'         => '3001234567',
            'message'       => 'Hello, I would like to inquire about upcoming alumni chapter meetups in Islamabad.',
            'is_read'       => false,
        ]);

        $inquiry = ContactInquiry::first();

        // Verify email dispatched to official admin email
        Notification::assertSentOnDemand(
            NewContactInquiryAdminNotification::class,
            function ($notification, $channels, $notifiable) use ($inquiry) {
                return $notifiable->routes['mail'] === 'admin.inbox@poba.org'
                    && $notification->inquiry->id === $inquiry->id;
            }
        );

        // Verify automated acknowledgement dispatched to sender
        Notification::assertSentOnDemand(
            ContactInquiryConfirmationNotification::class,
            function ($notification, $channels, $notifiable) use ($inquiry) {
                return $notifiable->routes['mail'] === 'ali.khan@example.com'
                    && $notification->inquiry->id === $inquiry->id;
            }
        );
    }

    public function test_contact_form_validates_required_fields()
    {
        $response = $this->post(route('contact.send'), [
            'first_name'    => '',
            'last_name'     => '',
            'email_address' => 'invalid-email',
            'message'       => '',
        ]);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email_address', 'message']);
        $this->assertEquals(0, ContactInquiry::count());
    }

    public function test_admin_can_view_inquiries_list_and_filter_by_status()
    {
        $admin = $this->createAdmin(['contact']);

        $inq1 = ContactInquiry::create([
            'first_name'    => 'Zahid',
            'last_name'     => 'Ahmed',
            'email_address' => 'zahid@test.com',
            'phone_ext'     => '+92',
            'phone'         => '3211112233',
            'message'       => 'Question about batch 24 reunion.',
            'is_read'       => false,
        ]);

        $inq2 = ContactInquiry::create([
            'first_name'    => 'Sara',
            'last_name'     => 'Malik',
            'email_address' => 'sara@test.com',
            'phone_ext'     => '+92',
            'phone'         => '3334445566',
            'message'       => 'Sponsorship inquiry for annual dinner.',
            'is_read'       => true,
        ]);

        // 1. All
        $resAll = $this->actingAs($admin, 'web')->get(route('admin.inquiries.index'));
        $resAll->assertStatus(200);
        $resAll->assertSee('Zahid Ahmed');
        $resAll->assertSee('Sara Malik');
        $resAll->assertSee('1 Unread');
        $resAll->assertSee('inquiries-table-responsive');
        $resAll->assertSee('action-icons-inquiries');
        $resAll->assertSee('btn-view');
        $resAll->assertSee('btn-reply');

        // 2. Unread filter
        $resUnread = $this->actingAs($admin, 'web')->get(route('admin.inquiries.index', ['status' => 'unread']));
        $resUnread->assertSee('Zahid Ahmed');
        $resUnread->assertDontSee('Sara Malik');

        // 3. Read filter
        $resRead = $this->actingAs($admin, 'web')->get(route('admin.inquiries.index', ['status' => 'read']));
        $resRead->assertDontSee('Zahid Ahmed');
        $resRead->assertSee('Sara Malik');
    }

    public function test_admin_viewing_inquiry_marks_it_as_read()
    {
        $admin = $this->createAdmin(['contact']);

        $inquiry = ContactInquiry::create([
            'first_name'    => 'Hamza',
            'last_name'     => 'Tariq',
            'email_address' => 'hamza@test.com',
            'message'       => 'Can I update my contact info?',
            'is_read'       => false,
        ]);

        $this->assertFalse($inquiry->fresh()->is_read);

        $response = $this->actingAs($admin, 'web')->getJson(route('admin.inquiries.show', $inquiry->id));

        $response->assertStatus(200);
        $response->assertJson([
            'id'      => $inquiry->id,
            'name'    => 'Hamza Tariq',
            'is_read' => true,
        ]);

        $this->assertTrue($inquiry->fresh()->is_read);
    }

    public function test_admin_can_toggle_read_and_delete_inquiry()
    {
        $admin = $this->createAdmin(['contact']);

        $inquiry = ContactInquiry::create([
            'first_name'    => 'Usman',
            'last_name'     => 'Bilal',
            'email_address' => 'usman@test.com',
            'message'       => 'Test inquiry.',
            'is_read'       => true,
        ]);

        // Toggle back to unread
        $toggleRes = $this->actingAs($admin, 'web')->post(route('admin.inquiries.toggleRead', $inquiry->id));
        $toggleRes->assertRedirect();
        $this->assertFalse($inquiry->fresh()->is_read);

        // Delete inquiry
        $deleteRes = $this->actingAs($admin, 'web')->delete(route('admin.inquiries.destroy', $inquiry->id));
        $deleteRes->assertRedirect();
        $this->assertDatabaseMissing('contact_inquiries', ['id' => $inquiry->id]);
    }

    public function test_admin_can_send_reply_to_inquiry_and_notification_is_sent()
    {
        Notification::fake();
        CmsSetting::set('contact_email', 'support@poba.org');

        $admin = $this->createAdmin(['contact']);

        $inquiry = ContactInquiry::create([
            'first_name'    => 'Kashif',
            'last_name'     => 'Rehman',
            'email_address' => 'kashif@example.com',
            'phone_ext'     => '+92',
            'phone'         => '3009988776',
            'message'       => 'I need information about the upcoming gala dinner tickets.',
            'is_read'       => false,
        ]);

        $replyPayload = [
            'subject'       => 'Re: Gala dinner tickets inquiry',
            'reply_message' => 'Dear Kashif, tickets are available online at www.poba.org/events.',
        ];

        $response = $this->actingAs($admin, 'web')
            ->post(route('admin.inquiries.reply', $inquiry->id), $replyPayload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check DB updates
        $fresh = $inquiry->fresh();
        $this->assertTrue($fresh->is_read);
        $this->assertNotNull($fresh->responded_at);
        $this->assertEquals('Dear Kashif, tickets are available online at www.poba.org/events.', $fresh->admin_notes);

        // Check notification dispatched to sender
        Notification::assertSentOnDemand(
            ContactInquiryReplyNotification::class,
            function ($notification, $channels, $notifiable) use ($inquiry) {
                return $notifiable->routes['mail'] === 'kashif@example.com'
                    && $notification->emailSubject === 'Re: Gala dinner tickets inquiry'
                    && $notification->replyMessage === 'Dear Kashif, tickets are available online at www.poba.org/events.'
                    && $notification->replyToEmail === 'support@poba.org';
            }
        );
    }

    public function test_admin_reply_validates_required_fields()
    {
        $admin = $this->createAdmin(['contact']);

        $inquiry = ContactInquiry::create([
            'first_name'    => 'Ali',
            'last_name'     => 'Raza',
            'email_address' => 'ali@test.com',
            'message'       => 'Test message',
            'is_read'       => false,
        ]);

        $response = $this->actingAs($admin, 'web')
            ->post(route('admin.inquiries.reply', $inquiry->id), [
                'reply_message' => '',
            ]);

        $response->assertSessionHasErrors(['reply_message']);
        $this->assertNull($inquiry->fresh()->responded_at);
    }

    public function test_admin_can_filter_by_responded_status()
    {
        $admin = $this->createAdmin(['contact']);

        // 1. Unread & not responded
        $unread = ContactInquiry::create([
            'first_name'    => 'Unread',
            'last_name'     => 'Person',
            'email_address' => 'unread@test.com',
            'message'       => 'Unread message',
            'is_read'       => false,
        ]);

        // 2. Read & not responded
        $read = ContactInquiry::create([
            'first_name'    => 'Read',
            'last_name'     => 'Person',
            'email_address' => 'read@test.com',
            'message'       => 'Read message',
            'is_read'       => true,
            'responded_at'  => null,
        ]);

        // 3. Responded
        $responded = ContactInquiry::create([
            'first_name'    => 'Responded',
            'last_name'     => 'Person',
            'email_address' => 'responded@test.com',
            'message'       => 'Responded message',
            'is_read'       => true,
            'responded_at'  => now(),
            'admin_notes'   => 'All answered.',
        ]);

        // Filter: responded
        $res = $this->actingAs($admin, 'web')->get(route('admin.inquiries.index', ['status' => 'responded']));
        $res->assertStatus(200);
        $res->assertSee('Responded Person');
        $res->assertDontSee('Unread Person');
        $res->assertDontSee('Read Person');

        // Filter: read (only read that are not responded)
        $resRead = $this->actingAs($admin, 'web')->get(route('admin.inquiries.index', ['status' => 'read']));
        $resRead->assertStatus(200);
        $resRead->assertSee('Read Person');
        $resRead->assertDontSee('Responded Person');
        $resRead->assertDontSee('Unread Person');

        // Filter: unread
        $resUnread = $this->actingAs($admin, 'web')->get(route('admin.inquiries.index', ['status' => 'unread']));
        $resUnread->assertStatus(200);
        $resUnread->assertSee('Unread Person');
        $resUnread->assertDontSee('Responded Person');
        $resUnread->assertDontSee('Read Person');
    }
}
