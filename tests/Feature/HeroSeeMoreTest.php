<?php

namespace Tests\Feature;

use App\Models\CmsSetting;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroSeeMoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_hero_description_within_500_chars(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'superadmin',
        ]);

        $validDesc = str_repeat('A', 500);

        $response = $this->actingAs($admin, 'web')->post(route('admin.cms.homepage.save'), [
            'hero_title'       => 'Title',
            'hero_tagline'     => 'Tagline',
            'hero_description' => $validDesc,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals($validDesc, CmsSetting::get('hero_description'));
    }

    public function test_admin_cannot_save_hero_description_exceeding_500_chars(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'superadmin',
        ]);

        $tooLongDesc = str_repeat('A', 501);

        $response = $this->actingAs($admin, 'web')->post(route('admin.cms.homepage.save'), [
            'hero_title'       => 'Title',
            'hero_tagline'     => 'Tagline',
            'hero_description' => $tooLongDesc,
        ]);

        $response->assertSessionHasErrors(['hero_description']);
    }

    public function test_admin_homepage_renders_maxlength_500_and_counter(): void
    {
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'superadmin',
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.cms.homepage'));

        $response->assertStatus(200);
        $response->assertSee('maxlength="500"', false);
        $response->assertSee('id="heroDescCounter"', false);
    }

    public function test_customer_homepage_renders_hero_see_more_toggle(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('id="heroDescText"', false);
        $response->assertSee('data-target="heroDescText"', false);
        $response->assertSee('id="aboutDescText"', false);
        $response->assertSee('data-target="aboutDescText"', false);
    }
}
