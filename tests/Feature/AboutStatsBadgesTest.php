<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutStatsBadgesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_no_stat_badges_when_none_added_in_admin(): void
    {
        // When about_stats is null/empty
        CmsSetting::where('key', 'about_stats')->delete();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('stats-grid-custom', false);
        $response->assertDontSee('In Service & Leadership');
        $response->assertDontSee('Strong Alumni Network');
        $response->assertDontSee('Worldwide Presence');
        $response->assertDontSee('Honor & Commitment');
    }

    public function test_homepage_shows_no_stat_badges_when_empty_array_saved(): void
    {
        CmsSetting::set('about_stats', json_encode([]));

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('stats-grid-custom', false);
        $response->assertDontSee('In Service & Leadership');
    }

    public function test_homepage_shows_stat_badges_when_configured_in_admin(): void
    {
        $customStats = [
            [
                'icon'       => null,
                'heading'    => 'Naval Leadership',
                'subheading' => 'Courage and Duty',
            ],
        ];

        CmsSetting::set('about_stats', json_encode($customStats));

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('stats-grid-custom', false);
        $response->assertSee('Naval Leadership');
        $response->assertSee('Courage and Duty');
    }

    public function test_admin_saving_no_stats_persists_empty_array(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'superadmin',
        ]);

        // Submit form without 'stats' field (e.g. all rows deleted)
        $response = $this->actingAs($admin, 'web')->post(route('admin.cms.homepage.save'), [
            'about_title' => 'About POBA Test',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('[]', CmsSetting::get('about_stats'));
    }
}
