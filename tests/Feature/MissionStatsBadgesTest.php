<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionStatsBadgesTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_shows_no_stat_badges_when_none_added_in_admin(): void
    {
        CmsSetting::where('key', 'mission_stats')->delete();

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertDontSee('stats-grid-custom', false);
        $response->assertDontSee('In Service & Leadership');
        $response->assertDontSee('Strong Alumni Network');
        $response->assertDontSee('Worldwide Presence');
        $response->assertDontSee('Honor & Commitment');
    }

    public function test_about_page_shows_no_stat_badges_when_empty_array_saved(): void
    {
        CmsSetting::set('mission_stats', json_encode([]));

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertDontSee('stats-grid-custom', false);
        $response->assertDontSee('In Service & Leadership');
    }

    public function test_about_page_shows_stat_badges_when_configured_in_admin(): void
    {
        $customStats = [
            [
                'icon'       => null,
                'heading'    => 'Naval Integrity',
                'subheading' => 'Steadfast Commitment',
            ],
        ];

        CmsSetting::set('mission_stats', json_encode($customStats));

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('stats-grid-custom', false);
        $response->assertSee('Naval Integrity');
        $response->assertSee('Steadfast Commitment');
    }

    public function test_admin_saving_no_mission_stats_persists_empty_array(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'superadmin',
        ]);

        $response = $this->actingAs($admin, 'web')->post(route('admin.cms.about.save'), [
            'mission_title' => 'Our Mission Test',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('[]', CmsSetting::get('mission_stats'));
    }

    public function test_admin_about_cms_does_not_force_default_row_when_empty(): void
    {
        CmsSetting::set('mission_stats', json_encode([]));

        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'superadmin',
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.cms.about'));

        $response->assertStatus(200);
        $response->assertDontSee('name="mission_stats[0][heading]"', false);
    }
}
