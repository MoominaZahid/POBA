<?php

namespace Tests\Feature;

use App\Models\AlumniUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRegistrationPhoneCountryCodeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    public function test_become_a_member_page_renders_phone_flag_and_country_label_elements()
    {
        $response = $this->get(route('member.index'));

        $response->assertStatus(200);

        // Check flag and country label spans
        $response->assertSee('id="phone_flag"', false);
        $response->assertSee('id="phone_country_label"', false);
        $response->assertSee('id="phone_code"', false);
        $response->assertSee('id="phone_hint"', false);

        // Check South Africa (+27) option and metadata
        $response->assertSee('value="+27"', false);
        $response->assertSee('data-flag="🇿🇦"', false);
        $response->assertSee('data-name="South Africa"', false);
        $response->assertSee('data-iso="ZA"', false);

        // Check Afghanistan (+93) option and metadata
        $response->assertSee('value="+93"', false);
        $response->assertSee('data-flag="🇦🇫"', false);
        $response->assertSee('data-name="Afghanistan"', false);
        $response->assertSee('data-iso="AF"', false);

        // Check Pakistan (+92) option and metadata
        $response->assertSee('value="+92"', false);
        $response->assertSee('data-flag="🇵🇰"', false);
        $response->assertSee('data-name="Pakistan"', false);
        $response->assertSee('data-iso="PK"', false);

        // Check script metadata definition and sync helper
        $response->assertSee('phoneCountries', false);
        $response->assertSee('updatePhoneLimits', false);
        $response->assertSee('syncPhoneWithSelectedCountry', false);
        $response->assertSee('phone_flag_img', false);
        $response->assertSee('phone_code_badge', false);
        $response->assertSee('South Africa', false);
        $response->assertSee('Afghanistan', false);
    }

    public function test_customer_profile_edit_renders_dynamic_phone_flag_and_country_label()
    {
        $alumni = AlumniUser::create([
            'full_name'    => 'Ali Khan',
            'email'        => 'ali.za@example.com',
            'phone_number' => '+27821234567',
            'password'     => bcrypt('Secret123!'),
            'status'       => 'approved',
            'is_approved'  => true,
            'is_active'    => true,
            'entry'        => 20,
            'current_city' => 'Johannesburg',
            'current_country' => 'South Africa',
        ]);

        $response = $this->actingAs($alumni, 'alumni')
                         ->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('id="pf_phone_flag"', false);
        $response->assertSee('id="pf_phone_country_label"', false);
        $response->assertSee('id="pf_phone_code"', false);
        $response->assertSee('updatePfPhoneLimits', false);
    }
}
