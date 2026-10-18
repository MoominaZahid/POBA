<?php

namespace Tests\Feature;

use App\Models\AlumniUser;
use App\Models\Committee;
use App\Models\Event;
use App\Models\GalleryFolder;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnifiedBackButtonTest extends TestCase
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

    private function createAlumni(): AlumniUser
    {
        return AlumniUser::create([
            'full_name'            => 'Zahid Khan',
            'email'                => 'zahid@poba.test',
            'phone_number'         => '+923001112233',
            'entry'                => 12,
            'ccp_no'               => '9876',
            'class_year'           => 2018,
            'house'                => 'Jinnah',
            'education'            => 'Bachelors',
            'field_of_study'       => 'Computer Science',
            'current_country'      => 'Pakistan',
            'current_city'         => 'Islamabad',
            'designation'          => 'Software Engineer',
            'organization'         => 'TechCorp',
            'field_of_work'        => 'IT',
            'status'               => 'approved',
            'is_active'            => true,
            'visible_fields'       => ['email', 'phone_number', 'city', 'country', 'designation', 'organization', 'education', 'field_of_study', 'field_of_work'],
            'consent_sharing'      => true,
            'password'             => bcrypt('password123'),
        ]);
    }

    public function test_customer_pages_have_unified_back_button()
    {
        // 1. Become a member (Figma design: no back button, centered heading with orange underline)
        $res = $this->get(route('member.index'));
        $res->assertStatus(200);
        $res->assertSee('Become a Member');
        $res->assertSee('bam-underline');
        $res->assertDontSee('class="btn-back"', false);

        // 2. Privacy Policy
        $res = $this->get(route('privacy'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 3. Terms & Conditions
        $res = $this->get(route('terms'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 4. News Detail
        $news = News::create([
            'title'        => 'Test Annual Gala Announcement',
            'description'  => '<p>Details about the upcoming annual gala event.</p>',
            'type'         => 'News',
            'published_at' => now(),
        ]);
        $res = $this->get(route('news.show', $news->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);
        $res->assertSee('Back to News</span>', false);

        // 5. Gallery Detail
        $folder = GalleryFolder::create([
            'folder_name' => 'Campus Reunion 2026',
            'description' => 'Memories from the annual gathering.',
        ]);
        $res = $this->get(route('gallery.show', $folder->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);
        $res->assertSee('Back to Gallery</span>', false);

        // 6. Alumni Directory Profile Show
        $alumni = $this->createAlumni();
        $res = $this->actingAs($alumni, 'alumni')->get(route('alumni.show', $alumni->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 7. Alumni Profile Edit
        $res = $this->actingAs($alumni, 'alumni')->get(route('profile.edit'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 8. Verticals Executive
        $committee = Committee::create([
            'title'       => 'Executive Committee',
            'description' => 'Main Executive Board',
            'type'        => 'executive',
        ]);
        $res = $this->get(route('verticals.executive'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);
        $res->assertSee('Back to Verticals</span>', false);

        // 9. Verticals Working
        $workingMain = Committee::create([
            'title'       => 'Working Committees',
            'description' => 'Operational Committees',
            'type'        => 'working',
        ]);
        $res = $this->get(route('verticals.working'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);
        $res->assertSee('Back to Verticals</span>', false);
    }

    public function test_admin_pages_have_unified_back_button()
    {
        $admin = $this->createAdmin();

        // 1. Admin Alumni Show
        $alumni = $this->createAlumni();
        $res = $this->actingAs($admin)->get(route('admin.alumni.show', $alumni->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 2. Admin Events Create
        $res = $this->actingAs($admin)->get(route('admin.events.create'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 3. Admin Events Edit & Participants
        $event = Event::create([
            'title'                 => 'Grand Alumni Dinner',
            'location'              => 'Islamabad Club',
            'start_date'            => now()->addDays(5)->toDateString(),
            'end_date'              => now()->addDays(5)->toDateString(),
            'start_time'            => '19:00',
            'end_time'              => '22:00',
            'registration_required' => true,
        ]);
        $res = $this->actingAs($admin)->get(route('admin.events.edit', $event->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        $res = $this->actingAs($admin)->get(route('admin.events.participants', $event->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 4. Admin Gallery Create, Edit, Images
        $res = $this->actingAs($admin)->get(route('admin.gallery.create'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        $folder = GalleryFolder::create([
            'folder_name' => 'Sports Gala 2026',
            'description' => 'Sports tournament pictures.',
        ]);
        $res = $this->actingAs($admin)->get(route('admin.gallery.edit', $folder->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        $res = $this->actingAs($admin)->get(route('admin.gallery.images', $folder->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 5. Admin Admin Users Create
        $res = $this->actingAs($admin)->get(route('admin.admin-users.create'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 6. Admin News Create, Edit, Show
        $res = $this->actingAs($admin)->get(route('admin.cms.news.create'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        $news = News::create([
            'title'       => 'Admin Test News Item',
            'description' => '<p>News content goes here.</p>',
            'type'        => 'News',
        ]);
        $res = $this->actingAs($admin)->get(route('admin.cms.news.edit', $news->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        $res = $this->actingAs($admin)->get(route('admin.cms.news.show', $news->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        // 7. Admin Verticals Create, Edit, Show
        $res = $this->actingAs($admin)->get(route('admin.cms.verticals.create'));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        $comm = Committee::create([
            'title'       => 'Finance Committee',
            'description' => 'Budgeting & financial audit',
            'type'        => 'working',
        ]);
        $res = $this->actingAs($admin)->get(route('admin.cms.verticals.edit', $comm->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);

        $res = $this->actingAs($admin)->get(route('admin.cms.verticals.show', $comm->id));
        $res->assertStatus(200);
        $res->assertSee('class="btn-back"', false);
        $res->assertSee('Back to Verticals</span>', false);
    }

    public function test_back_button_component_and_partial_render_properly()
    {
        $renderedComponent = (string) \Illuminate\Support\Facades\Blade::render(
            '<x-back-button href="https://example.com/target" label="Return Home" />'
        );

        $this->assertStringContainsString('class="btn-back"', $renderedComponent);
        $this->assertStringContainsString('href="https://example.com/target"', $renderedComponent);
        $this->assertStringContainsString('Return Home</span>', $renderedComponent);
        $this->assertStringContainsString('<svg', $renderedComponent);
        $this->assertStringContainsString('stroke-width="2.5"', $renderedComponent);
        $this->assertStringContainsString('<line x1="20" y1="12" x2="4" y2="12"></line>', $renderedComponent);
        $this->assertStringContainsString('<polyline points="10 18 4 12 10 6"></polyline>', $renderedComponent);

        $renderedPartial = (string) \Illuminate\Support\Facades\Blade::render(
            '@include("partials.back-button", ["href" => "https://example.com/partial", "label" => "Back"])'
        );

        $this->assertStringContainsString('class="btn-back', $renderedPartial);
        $this->assertStringContainsString('href="https://example.com/partial"', $renderedPartial);
        $this->assertStringContainsString('Back</span>', $renderedPartial);
        $this->assertStringContainsString('<svg', $renderedPartial);
        $this->assertStringContainsString('stroke-width="2.5"', $renderedPartial);
        $this->assertStringContainsString('<line x1="20" y1="12" x2="4" y2="12"></line>', $renderedPartial);
        $this->assertStringContainsString('<polyline points="10 18 4 12 10 6"></polyline>', $renderedPartial);
    }

    public function test_navbar_auth_links_render_as_standard_links_without_button_background()
    {
        // Guest view: CTA button exists for Become a Member
        $resGuest = $this->get(route('home'));
        $resGuest->assertStatus(200);
        $resGuest->assertSee('Become a Member');
        $resGuest->assertSee('class="btn-teal-nav"', false);
        $resGuest->assertDontSee('My Profile');
        $resGuest->assertDontSee('Logout');

        // Authenticated Alumni view: My Profile and Logout are regular links without btn-teal-nav
        $alumni = $this->createAlumni();
        $resAuth = $this->actingAs($alumni, 'alumni')->get(route('home'));
        $resAuth->assertStatus(200);
        $resAuth->assertSee('My Profile');
        $resAuth->assertSee('Logout');
        $resAuth->assertSee('document.getElementById(\'alumni-logout-form\').submit()', false);
        $this->assertFalse(str_contains($resAuth->getContent(), 'class="btn-teal-nav">My Profile'));
        $this->assertFalse(str_contains($resAuth->getContent(), 'class="btn-teal-nav">Logout'));
    }
}
