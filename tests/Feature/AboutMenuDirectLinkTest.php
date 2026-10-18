<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutMenuDirectLinkTest extends TestCase
{
    use RefreshDatabase;
    public function test_about_menu_item_is_direct_link_and_not_dropdown(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Verify About Us direct link exists with route('about')
        $response->assertSee('<a href="' . route('about') . '" class="">About Us</a>', false);

        // Verify it is not a dropdown
        $response->assertDontSee('About Us ▾');
        $response->assertDontSee('All Verticals');

        // Verify Alumni dropdown still exists on navRight
        $response->assertSee('Alumni ▾');
    }

    public function test_about_page_accessible_via_direct_link(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
        $response->assertSee('<a href="' . route('about') . '" class="active">About Us</a>', false);
    }
}
