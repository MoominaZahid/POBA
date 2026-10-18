<?php

namespace Tests\Feature;

use App\Models\SiteTheme;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendThemeApplicationTest extends TestCase
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
            'email'    => 'admin' . uniqid() . '@poba.test',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);
    }

    public function test_default_theme_renders_theme_css_and_custom_variables_on_home_page()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Verify dynamic theme style block is present
        $response->assertSee('poba-dynamic-theme-css');
        $response->assertSee('--teal:');
        $response->assertSee('--orange:');
        $response->assertSee('--heading-font:');
        $response->assertSee('--body-font:');
    }

    public function test_admin_can_update_theme_colors_and_fonts_and_they_apply_to_frontend()
    {
        $admin = $this->createAdmin();

        $themeData = [
            'primary_color'   => '#2563eb',
            'secondary_color' => '#d97706',
            'heading_font'    => 'Montserrat',
            'heading_size'    => '36px',
            'subheading_size' => '28px',
            'body_size'       => '16px',
        ];

        $response = $this->actingAs($admin)->put(route('admin.theme.update'), $themeData);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify SiteTheme model was updated
        $this->assertEquals('#2563eb', SiteTheme::get('primary_color'));
        $this->assertEquals('#d97706', SiteTheme::get('secondary_color'));
        $this->assertEquals('Montserrat', SiteTheme::get('heading_font'));
        $this->assertEquals('Montserrat', SiteTheme::get('body_font'));

        // Verify Home page renders new theme settings
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeContent = $homeResponse->getContent();

        $this->assertStringContainsString('family=Montserrat:wght@300;400;500;600;700;800', $homeContent);
        $this->assertStringContainsString('--teal:         #2563eb !important;', $homeContent);
        $this->assertStringContainsString('--orange:       #d97706 !important;', $homeContent);
        $this->assertStringContainsString("font-family: 'Montserrat', sans-serif !important;", $homeContent);
        $this->assertStringContainsString('.top-banner {', $homeContent);
        $this->assertStringContainsString('background: #2563eb !important;', $homeContent);
        $this->assertStringContainsString('background: #d97706 !important;', $homeContent);

        // Verify About page renders new theme settings
        $aboutResponse = $this->get(route('about'));
        $aboutResponse->assertStatus(200);
        $this->assertStringContainsString('#2563eb', $aboutResponse->getContent());
        $this->assertStringContainsString('Montserrat', $aboutResponse->getContent());

        // Verify Events page renders new theme settings
        $eventsResponse = $this->get(route('events.index'));
        $eventsResponse->assertStatus(200);
        $this->assertStringContainsString('#2563eb', $eventsResponse->getContent());
        $this->assertStringContainsString('Montserrat', $eventsResponse->getContent());

        // Verify News page renders new theme settings
        $newsResponse = $this->get(route('news.index'));
        $newsResponse->assertStatus(200);
        $this->assertStringContainsString('#2563eb', $newsResponse->getContent());
        $this->assertStringContainsString('Montserrat', $newsResponse->getContent());
    }

    public function test_admin_reset_restores_default_theme()
    {
        $admin = $this->createAdmin();

        // First set custom colors
        SiteTheme::saveAll([
            'primary_color'   => '#9333ea',
            'secondary_color' => '#10b981',
            'heading_font'    => 'Outfit',
        ]);

        $this->assertEquals('#9333ea', SiteTheme::get('primary_color'));

        // Reset theme
        $response = $this->actingAs($admin)->delete(route('admin.theme.reset'));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('#1a7a7a', SiteTheme::get('primary_color'));
        $this->assertEquals('#e87722', SiteTheme::get('secondary_color'));
        $this->assertEquals('Poppins', SiteTheme::get('heading_font'));

        $homeResponse = $this->get(route('home'));
        $homeContent = $homeResponse->getContent();
        $this->assertStringContainsString('#1a7a7a', $homeContent);
        $this->assertStringContainsString('#e87722', $homeContent);
    }

    public function test_poba_css_uses_theme_variables_instead_of_hardcoded_palette()
    {
        $css = file_get_contents(public_path('css/poba.css'));

        $this->assertStringContainsString('.section-title {', $css);
        $this->assertStringContainsString('color: var(--teal, #02828e);', $css);
        $this->assertStringContainsString('background: var(--orange, #ff7e40);', $css);
        $this->assertStringContainsString('.top-banner {', $css);
        $this->assertStringContainsString('background: var(--teal, #02828e);', $css);
        $this->assertStringContainsString('.navbar-brand-centered {', $css);
        $this->assertStringContainsString('background: var(--teal, #02828e);', $css);
        $this->assertStringContainsString('.btn-teal-nav {', $css);
        $this->assertStringContainsString('background: var(--teal, #02828e) !important;', $css);
        $this->assertStringContainsString('footer {', $css);
        $this->assertStringContainsString('background: var(--teal, #02828e);', $css);
    }
}
