<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineEndTileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    public function test_admin_cms_about_renders_end_tile_textarea_and_no_individual_details_textareas()
    {
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.cms.about'));
        $response->assertStatus(200);

        // Should have the end tile textarea and fields
        $response->assertSee('history_end_tile_text');
        $response->assertSee('history_end_tile_year');
        $response->assertSee('history_end_tile_heading');

        // Should NOT have per-row details[] textarea
        $response->assertDontSee('name="details[]"', false);
    }

    public function test_admin_can_save_history_end_tile_settings()
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);

        $postData = [
            'history_title'            => 'Our Journey',
            'history_description'      => 'Our milestones description',
            'history_end_tile_year'    => '2026+',
            'history_end_tile_heading' => 'Beyond the Horizon',
            'history_end_tile_text'    => 'This is the long detailed text for the single final history tile at the end of the timeline.',
            'years'                    => ['1947', '1965'],
            'headings'                 => ['Foundation', 'First Network'],
            'descriptions'             => ['Desc 1', 'Desc 2'],
        ];

        $response = $this->actingAs($admin, 'web')->post(route('admin.cms.about.save'), $postData);
        $response->assertSessionHas('success');

        $this->assertEquals('2026+', CmsSetting::get('history_end_tile_year'));
        $this->assertEquals('Beyond the Horizon', CmsSetting::get('history_end_tile_heading'));
        $this->assertEquals('This is the long detailed text for the single final history tile at the end of the timeline.', CmsSetting::get('history_end_tile_text'));

        $timeline = json_decode(CmsSetting::get('history_timeline'), true);
        $this->assertCount(2, $timeline);
        $this->assertEquals('1947', $timeline[0]['year']);
        $this->assertArrayNotHasKey('detail', $timeline[0]);
    }

    public function test_customer_about_page_renders_end_tile_when_configured()
    {
        CmsSetting::set('history_end_tile_year', 'Present');
        CmsSetting::set('history_end_tile_heading', 'Continuing the Legacy');
        CmsSetting::set('history_end_tile_text', 'Long detailed info about future vision and ongoing alumni support.');

        $response = $this->get(route('about'));
        $response->assertStatus(200);

        $response->assertSee('timeline-item-end');
        $response->assertSee('Continuing the Legacy');
        $response->assertSee('Long detailed info about future vision and ongoing alumni support.');
        $response->assertSee('Present');

        // Should not see old read more buttons on timeline
        $response->assertDontSee('timeline-more-toggle');
    }

    public function test_customer_about_page_does_not_render_end_tile_when_empty()
    {
        CmsSetting::set('history_end_tile_text', '');

        $response = $this->get(route('about'));
        $response->assertStatus(200);

        $response->assertDontSee('timeline-item-end');
    }

    public function test_css_has_auto_increase_height_rules_for_timeline_box()
    {
        $css = file_get_contents(public_path('css/poba.css'));

        // Verify .timeline-box uses min-height and height: auto
        $this->assertMatchesRegularExpression('/\.timeline-box\s*\{[^}]*min-height:\s*110px/s', $css);
        $this->assertMatchesRegularExpression('/\.timeline-box\s*\{[^}]*height:\s*auto/s', $css);
        $this->assertMatchesRegularExpression('/\.timeline-box\s*\{[^}]*position:\s*relative/s', $css);
        $this->assertMatchesRegularExpression('/\.timeline-box\s*\{[^}]*margin-top:\s*41px/s', $css);
    }
}
