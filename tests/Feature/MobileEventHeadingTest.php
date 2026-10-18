<?php

namespace Tests\Feature;

use App\Models\AlumniUser;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileEventHeadingTest extends TestCase
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
            'full_name'       => 'Test Cadet',
            'email'           => 'cadet@poba.test',
            'password'        => bcrypt('secret123'),
            'entry'           => 15,
            'ccp_no'          => 1234,
            'house'           => 'Jinnah',
            'status'          => 'approved',
            'is_active'       => true,
            'consent_sharing' => true,
            'agree_terms'     => true,
        ]);
    }

    public function test_event_card_renders_heading_in_mobile_title_wrap_and_desktop_body()
    {
        $alumni = $this->createAlumni();

        $event = Event::create([
            'title'                 => 'New Event Oct',
            'location'              => 'Jinnah House Cadet College Palandri',
            'start_date'            => now()->addDays(5)->toDateString(),
            'end_date'              => now()->addDays(6)->toDateString(),
            'start_time'            => '20:36:00',
            'end_time'              => '14:36:00',
            'registration_required' => false,
            'focal_person_name'     => 'MR A',
            'focal_person_number'   => '03456857585',
        ]);

        $response = $this->actingAs($alumni, 'alumni')->get(route('events.index'));
        $response->assertStatus(200);

        // Verify that the mobile title container exists with the title next to date badge
        $response->assertSee('ec-mobile-title-wrap', false);
        $response->assertSee('ec-title-mobile', false);
        $response->assertSee('New Event Oct');

        // Verify that desktop title exists in ec-body
        $response->assertSee('ec-title-desktop', false);

        // Verify responsive styles are loaded
        $response->assertSee('.ec-mobile-title-wrap', false);
        $response->assertSee('@media (max-width: 768px)', false);
    }
}
