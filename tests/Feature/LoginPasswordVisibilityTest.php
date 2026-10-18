<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginPasswordVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    public function test_login_page_renders_properly_aligned_password_eye_toggle()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        // Check password input field & wrapper
        $response->assertSee('class="login-pw-wrap"', false);
        $response->assertSee('id="pwField"', false);
        $response->assertSee('class="login-pw-toggle"', false);

        // Check outline SVG icons (viewBox 0 0 24 24)
        $response->assertSee('id="pwIconShow"', false);
        $response->assertSee('id="pwIconHide"', false);
        $response->assertSee('viewBox="0 0 24 24"', false);

        // Check togglePw javascript handler
        $response->assertSee('function togglePw()', false);
    }
}
