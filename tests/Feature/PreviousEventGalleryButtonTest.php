<?php

namespace Tests\Feature;

use App\Models\AlumniUser;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreviousEventGalleryButtonTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    private function createAlumni(): AlumniUser
    {
        return AlumniUser::create([
            'full_name'            => 'Usman Khan',
            'email'                => 'usman@poba.test',
            'password'             => bcrypt('secret123'),
            'entry'                => 15,
            'ccp_no'               => 5678,
            'house'                => 'Iqbal',
            'status'               => 'approved',
            'is_active'            => true,
            'consent_sharing'      => true,
            'agree_terms'          => true,
        ]);
    }

    public function test_previous_event_without_gallery_link_does_not_show_gallery_button_or_link()
    {
        $alumni = $this->createAlumni();

        // Ended event with no gallery_link
        $event = Event::create([
            'title'                 => 'Past Reunion Gala 2023',
            'location'              => 'Lahore Hall',
            'start_date'            => now()->subDays(30)->toDateString(),
            'end_date'              => now()->subDays(29)->toDateString(),
            'start_time'            => '10:00:00',
            'end_time'              => '18:00:00',
            'registration_required' => false,
            'focal_person_name'     => 'Capt. Tariq',
            'focal_person_number'   => '03001234567',
            'gallery_link'          => null,
        ]);

        $this->assertNull($event->formatted_gallery_url);

        $response = $this->actingAs($alumni, 'alumni')->get(route('events.index'));
        $response->assertStatus(200);

        // Previous tab is rendered in HTML
        $response->assertSee('Past Reunion Gala 2023');
        // Ensure "View Gallery" button is NOT rendered for this event
        $response->assertDontSee('View Gallery');
        $response->assertDontSee('🖼 View Gallery →');
    }

    public function test_previous_event_with_empty_string_gallery_link_does_not_show_gallery_button()
    {
        $alumni = $this->createAlumni();

        $event = Event::create([
            'title'                 => 'Past Reunion 2022',
            'location'              => 'Karachi Club',
            'start_date'            => now()->subDays(40)->toDateString(),
            'end_date'              => now()->subDays(39)->toDateString(),
            'registration_required' => false,
            'focal_person_name'     => 'Capt. Asim',
            'focal_person_number'   => '03009876543',
            'gallery_link'          => '   ',
        ]);

        $this->assertNull($event->formatted_gallery_url);

        $response = $this->actingAs($alumni, 'alumni')->get(route('events.index'));
        $response->assertStatus(200);

        $response->assertSee('Past Reunion 2022');
        $response->assertDontSee('View Gallery');
        $response->assertDontSee('🖼 View Gallery →');
    }

    public function test_previous_event_with_gallery_link_shows_gallery_button_and_collapsible_link()
    {
        $alumni = $this->createAlumni();

        $event = Event::create([
            'title'                 => 'Winter Gala 2024',
            'location'              => 'Islamabad Serena',
            'start_date'            => now()->subDays(10)->toDateString(),
            'end_date'              => now()->subDays(9)->toDateString(),
            'registration_required' => false,
            'focal_person_name'     => 'Maj. Bilal',
            'focal_person_number'   => '03111234567',
            'gallery_link'          => 'https://photos.google.com/share/winter-gala-2024',
        ]);

        $this->assertEquals('https://photos.google.com/share/winter-gala-2024', $event->formatted_gallery_url);

        $response = $this->actingAs($alumni, 'alumni')->get(route('events.index'));
        $response->assertStatus(200);

        $response->assertSee('Winter Gala 2024');
        $response->assertSee('View Gallery');
        $response->assertSee('🖼 View Gallery →');
        $response->assertSee('https://photos.google.com/share/winter-gala-2024');
    }

    public function test_previous_event_with_internal_gallery_folder_id_generates_correct_url()
    {
        $alumni = $this->createAlumni();

        $event = Event::create([
            'title'                 => 'Silver Jubilee Dinner',
            'location'              => 'Peshawar Club',
            'start_date'            => now()->subDays(5)->toDateString(),
            'end_date'              => now()->subDays(4)->toDateString(),
            'registration_required' => false,
            'focal_person_name'     => 'Lt. Col. Farooq',
            'focal_person_number'   => '03221234567',
            'gallery_link'          => '7',
        ]);

        $this->assertEquals(route('gallery.show', 7), $event->formatted_gallery_url);

        $response = $this->actingAs($alumni, 'alumni')->get(route('events.index'));
        $response->assertStatus(200);

        $response->assertSee('Silver Jubilee Dinner');
        $response->assertSee('View Gallery');
        $response->assertSee(route('gallery.show', 7));
    }

    public function test_gallery_show_resolves_by_slug_or_id_publicly()
    {
        $folder = \App\Models\GalleryFolder::create([
            'folder_name' => 'test',
            'slug'        => 'test',
            'type'        => 'Public',
            'class_year'  => '2024',
        ]);

        // Resolving by slug
        $responseSlug = $this->get(route('gallery.show', 'test'));
        $responseSlug->assertStatus(200);
        $responseSlug->assertSee('test');

        // Resolving by integer id
        $responseId = $this->get(route('gallery.show', $folder->id));
        $responseId->assertStatus(200);
        $responseId->assertSee('test');
    }

    public function test_previous_event_with_gallery_slug_url_resolves_cleanly()
    {
        \App\Models\GalleryFolder::create([
            'folder_name' => 'test',
            'slug'        => 'test',
            'type'        => 'Public',
            'class_year'  => '2024',
        ]);

        $event = Event::create([
            'title'                 => 'ww',
            'location'              => 'Islamabad',
            'start_date'            => now()->subDays(5)->toDateString(),
            'end_date'              => now()->subDays(4)->toDateString(),
            'registration_required' => false,
            'focal_person_name'     => 'Focal',
            'focal_person_number'   => '03001234567',
            'gallery_link'          => 'http://127.0.0.1:8000/gallery/test',
        ]);

        $this->assertEquals(route('gallery.show', 'test'), $event->formatted_gallery_url);

        $response = $this->get($event->formatted_gallery_url);
        $response->assertStatus(200);
        $response->assertSee('test');
    }

    public function test_private_gallery_redirects_guest_to_login()
    {
        \App\Models\GalleryFolder::create([
            'folder_name' => 'Secret Reunion',
            'slug'        => 'secret-reunion',
            'type'        => 'Private',
            'class_year'  => '2020',
        ]);

        $response = $this->get(route('gallery.show', 'secret-reunion'));
        $response->assertRedirect(route('login'));
    }

    public function test_nonexistent_gallery_returns_404()
    {
        $response = $this->get('/gallery/nonexistent-album-xyz');
        $response->assertStatus(404);
    }
}
