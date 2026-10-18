<?php

namespace Tests\Feature;

use App\Models\Committee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutVerticalCardMobileResponsiveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    public function test_about_page_renders_vertical_cards_with_fallback_titles_when_empty()
    {
        // Create committees with empty whitespace titles
        Committee::create([
            'title'       => '   ',
            'type'        => 'executive',
            'description' => '   ',
        ]);

        Committee::create([
            'title'       => '',
            'type'        => 'working',
            'description' => '',
        ]);

        $response = $this->get(route('about'));
        $response->assertStatus(200);

        // Should fall back to default titles and descriptions rather than blank
        $response->assertSee('Executive Committee');
        $response->assertSee('Working Committees');
        $response->assertSee('Executive Committee coordinates leadership, governance, and overall strategy.');
        $response->assertSee('Working committees manage active operational programs and alumni initiatives.');
    }

    public function test_poba_css_contains_mobile_responsive_rules_for_vertical_cards_and_top_banner()
    {
        $css = file_get_contents(public_path('css/poba.css'));

        // Check vertical card mobile overrides
        $this->assertStringContainsString('.verticals-grid {', $css);
        $this->assertStringContainsString('.vertical-card {', $css);
        $this->assertStringContainsString('.vertical-card::before {', $css);
        $this->assertStringContainsString('aspect-ratio: auto !important;', $css);
        $this->assertStringContainsString('scroll-padding-top: 80px;', $css);
        $this->assertStringContainsString('word-break: break-word !important;', $css);
    }
}
