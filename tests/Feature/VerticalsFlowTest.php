<?php

namespace Tests\Feature;

use App\Models\Committee;
use App\Models\CommitteeMember;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerticalsFlowTest extends TestCase
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

    public function test_about_us_page_shows_two_main_committees_with_dynamic_description()
    {
        $exec = Committee::create([
            'title'       => 'Executive Leadership Committee',
            'description' => 'Dynamic executive committee description from database.',
            'type'        => 'executive',
            'parent_id'   => null,
        ]);

        $workingMain = Committee::create([
            'title'       => 'Active Working Committees',
            'description' => 'Dynamic working committee description from database.',
            'type'        => 'working',
            'parent_id'   => null,
        ]);

        $response = $this->get(route('about'));
        $response->assertStatus(200);

        $response->assertSee('Executive Leadership Committee');
        $response->assertSee('Dynamic executive committee description from database.');
        $response->assertSee('Active Working Committees');
        $response->assertSee('Dynamic working committee description from database.');
    }

    public function test_neither_executive_nor_working_main_committee_can_be_deleted()
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $admin = $this->createAdmin();

        $exec = Committee::create([
            'title'       => 'Executive Committee',
            'description' => 'Exec desc',
            'type'        => 'executive',
            'parent_id'   => null,
        ]);

        $workingMain = Committee::create([
            'title'       => 'Working Committees',
            'description' => 'Working desc',
            'type'        => 'working',
            'parent_id'   => null,
        ]);

        // Attempt deleting Executive Committee
        $resExec = $this->actingAs($admin, 'web')->delete(route('admin.cms.verticals.delete', $exec->id));
        $resExec->assertSessionHas('error');
        $this->assertDatabaseHas('committees', ['id' => $exec->id]);

        // Attempt deleting Working Committee
        $resWork = $this->actingAs($admin, 'web')->delete(route('admin.cms.verticals.delete', $workingMain->id));
        $resWork->assertSessionHas('error');
        $this->assertDatabaseHas('committees', ['id' => $workingMain->id]);
    }

    public function test_admin_can_only_add_sub_working_committee_under_working_committee()
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $admin = $this->createAdmin();

        $workingMain = Committee::create([
            'title'       => 'Working Committees',
            'description' => 'Main working desc',
            'type'        => 'working',
            'parent_id'   => null,
        ]);

        $postData = [
            'title'        => 'Cyber Security Committee',
            'description'  => 'Handles IT & security infrastructure',
            'member_names' => ['Jane Doe', 'John Smith'],
            'member_urls'  => ['https://jane.test', 'https://john.test'],
        ];

        $response = $this->actingAs($admin, 'web')->post(route('admin.cms.verticals.store'), $postData);
        $response->assertRedirect(route('admin.cms.verticals'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('committees', [
            'title'     => 'Cyber Security Committee',
            'type'      => 'working',
            'parent_id' => $workingMain->id,
        ]);

        $created = Committee::where('title', 'Cyber Security Committee')->first();
        $this->assertCount(2, $created->members);
    }

    public function test_sub_working_committee_can_be_deleted()
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $admin = $this->createAdmin();

        $workingMain = Committee::create([
            'title'       => 'Working Committees',
            'description' => 'Main working desc',
            'type'        => 'working',
            'parent_id'   => null,
        ]);

        $sub = Committee::create([
            'title'       => 'Event Planning Sub Committee',
            'description' => 'Events',
            'type'        => 'working',
            'parent_id'   => $workingMain->id,
        ]);

        $response = $this->actingAs($admin, 'web')->delete(route('admin.cms.verticals.delete', $sub->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('committees', ['id' => $sub->id]);
    }

    public function test_admin_can_edit_description_for_main_committees()
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $admin = $this->createAdmin();

        $workingMain = Committee::create([
            'title'       => 'Working Committees',
            'description' => 'Old description',
            'type'        => 'working',
            'parent_id'   => null,
        ]);

        $response = $this->actingAs($admin, 'web')->put(route('admin.cms.verticals.update', $workingMain->id), [
            'title'       => 'Working Committees (Updated)',
            'description' => 'Brand new dynamic working committees description.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('Brand new dynamic working committees description.', $workingMain->fresh()->description);
    }

    public function test_admin_can_add_members_for_executive_committee()
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $admin = $this->createAdmin();

        $exec = Committee::create([
            'title'       => 'Executive Committee',
            'description' => 'Exec desc',
            'type'        => 'executive',
            'parent_id'   => null,
        ]);

        $response = $this->actingAs($admin, 'web')->put(route('admin.cms.verticals.update', $exec->id), [
            'title'        => 'Executive Committee',
            'description'  => 'Exec desc',
            'member_names' => ['Admiral Tariq', 'Captain Zahid'],
            'member_urls'  => [null, 'https://poba.test/zahid'],
        ]);

        $response->assertSessionHas('success');
        $this->assertCount(2, $exec->fresh()->members);
        $this->assertEquals('Admiral Tariq', $exec->fresh()->members[0]->member_name);
    }

    public function test_verticals_create_view_has_save_button_member_url_and_no_unwanted_notice()
    {
        $admin = $this->createAdmin();
        Committee::create([
            'title'       => 'Working Committees',
            'description' => 'Main working',
            'type'        => 'working',
            'parent_id'   => null,
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.cms.verticals.create'));
        $response->assertStatus(200);
        $response->assertSee('Save');
        $response->assertDontSee('Create Sub Working Committee');
        $response->assertDontSee('📌 Adding a Sub Working Committee');
        $response->assertSee('name="member_urls[]"', false);
    }

    public function test_sub_working_committee_creation_without_member_urls()
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $admin = $this->createAdmin();

        $workingMain = Committee::create([
            'title'       => 'Working Committees',
            'description' => 'Main working',
            'type'        => 'working',
            'parent_id'   => null,
        ]);

        $response = $this->actingAs($admin, 'web')->post(route('admin.cms.verticals.store'), [
            'title'        => 'Youth Wing',
            'description'  => 'Supports youth initiatives',
            'member_names' => ['Ali Khan', 'Hamza Riaz'],
        ]);

        $response->assertRedirect(route('admin.cms.verticals'));
        $sub = Committee::where('title', 'Youth Wing')->first();
        $this->assertNotNull($sub);
        $this->assertCount(2, $sub->members);
        $this->assertNull($sub->members[0]->member_url);
    }

    public function test_vertical_card_css_has_proper_flex_and_no_aspect_ratio_constraint()
    {
        $css = file_get_contents(public_path('css/poba.css'));
        $this->assertStringNotContainsString('aspect-ratio: 260 / 248', $css);
        $this->assertStringContainsString('.vertical-card .btn-teal', $css);
        $this->assertStringContainsString('margin-top: auto', $css);
    }
}

