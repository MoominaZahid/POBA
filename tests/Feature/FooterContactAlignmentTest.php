<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterContactAlignmentTest extends TestCase
{
    use RefreshDatabase;
    public function test_homepage_renders_footer_with_contact_and_socials(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('footer-contact', false);
        $response->assertSee('footer-socials', false);
        $response->assertSee('footer-bottom', false);
        $response->assertSee('alignFooterContact', false);
    }

    public function test_poba_css_has_proper_alignment_rules_for_footer(): void
    {
        $cssPath = public_path('css/poba.css');
        $this->assertFileExists($cssPath);

        $css = file_get_contents($cssPath);

        // Check footer-bottom has justify-content: flex-start
        $this->assertStringContainsString('.footer-bottom', $css);
        $this->assertMatchesRegularExpression('/\.footer-bottom\s*\{[^}]*justify-content:\s*flex-start/s', $css);

        // Check mobile footer-contact reset
        $this->assertMatchesRegularExpression('/\.footer-contact\s*\{[^}]*margin-left:\s*0\s*!important/s', $css);
    }
}
