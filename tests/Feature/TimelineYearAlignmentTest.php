<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineYearAlignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_poba_css_centrally_aligns_timeline_year_vertically(): void
    {
        $cssPath = public_path('css/poba.css');
        $this->assertFileExists($cssPath);

        $css = file_get_contents($cssPath);

        // Check .timeline-ribbon uses flex alignment
        $this->assertMatchesRegularExpression('/\.timeline-ribbon\s*\{[^}]*align-items:\s*center/s', $css);
        $this->assertMatchesRegularExpression('/\.timeline-ribbon\s*\{[^}]*justify-content:\s*center/s', $css);

        // Check .timeline-year has line-height: 1 and does not have hardcoded top: 8px
        $this->assertMatchesRegularExpression('/\.timeline-year\s*\{[^}]*line-height:\s*1/s', $css);
        $this->assertDoesNotMatchRegularExpression('/\.timeline-year\s*\{[^}]*top:\s*8px/s', $css);
    }

    public function test_about_page_renders_timeline_ribbon_and_year(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('timeline-ribbon', false);
        $response->assertSee('timeline-year', false);
    }
}
