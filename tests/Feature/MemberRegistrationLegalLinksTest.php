<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRegistrationLegalLinksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }
    public function test_become_a_member_page_contains_privacy_policy_and_terms_links()
    {
        $response = $this->get(route('member.index'));

        $response->assertStatus(200);
        $response->assertSee('Become a Member');
        
        // Assert links to privacy policy and terms are present in the form
        $response->assertSee(route('privacy'));
        $response->assertSee(route('terms'));
        $response->assertSee('Privacy Policy');
        $response->assertSee('Terms &amp; Conditions', false);
        $response->assertSee('Please review POBA policies before submitting:');
    }

    public function test_privacy_policy_page_loads_successfully()
    {
        $response = $this->get(route('privacy'));

        $response->assertStatus(200);
        $response->assertSee('Privacy Policy');
        $response->assertSee('Information We Collect');
        $response->assertSee('Member Privacy Controls');
    }

    public function test_terms_and_conditions_page_loads_successfully()
    {
        $response = $this->get(route('terms'));

        $response->assertStatus(200);
        $response->assertSee('Terms &amp; Conditions', false);
        $response->assertSee('Membership Eligibility &amp; Verification', false);
        $response->assertSee('Community Code of Conduct');
    }

    public function test_footer_renders_privacy_and_terms_links()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(route('privacy'));
        $response->assertSee(route('terms'));
    }
}
