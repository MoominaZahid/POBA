<?php

namespace Tests\Feature;

use App\Models\AlumniUser;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EventBatchesSequentialOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Unit/Feature tests should only run on isolated sqlite database.');
        }
    }

    private function createAdmin(): User
    {
        return User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@poba.test',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);
    }

    private function createAlumni(int $entry = 15): AlumniUser
    {
        return AlumniUser::create([
            'full_name'            => 'Tariq Mehmood',
            'email'                => 'tariq@poba.test',
            'password'             => bcrypt('secret123'),
            'entry'                => $entry,
            'ccp_no'               => 4321,
            'house'                => 'Jinnah',
            'status'               => 'approved',
            'is_active'            => true,
            'consent_sharing'      => true,
            'agree_terms'          => true,
        ]);
    }

    public function test_admin_creating_event_with_unsorted_batches_saves_in_sequential_order()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'web')->post(route('admin.events.store'), [
            'title'                 => 'Grand Reunion 2026',
            'description'           => 'Annual reunion gathering',
            'start_date'            => now()->addDays(10)->toDateString(),
            'end_date'              => now()->addDays(11)->toDateString(),
            'start_time'            => '18:00',
            'location'              => 'Islamabad',
            'focal_person_name'     => 'Col. Asif',
            'focal_person_number'   => '03001234567',
            'registration_required' => 1,
            'logo'                  => UploadedFile::fake()->createWithContent('logo.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAA=')),
            'entry_batches'         => [23, 5, 1, 9], // Unsorted input
        ]);

        $response->assertRedirect(route('admin.events.index'));

        $event = Event::first();
        $this->assertNotNull($event);
        $this->assertEquals([1, 5, 9, 23], $event->entry_batches);
    }

    public function test_admin_updating_event_with_unsorted_batches_saves_in_sequential_order()
    {
        $admin = $this->createAdmin();

        $event = Event::create([
            'title'                 => 'Winter Gala',
            'description'           => 'Winter festival',
            'start_date'            => now()->addDays(5)->toDateString(),
            'end_date'              => now()->addDays(6)->toDateString(),
            'start_time'            => '17:00',
            'location'              => 'Lahore',
            'focal_person_name'     => 'Maj. Bilal',
            'focal_person_number'   => '03111234567',
            'registration_required' => true,
            'logo'                  => 'events/sample_logo.png',
            'entry_batches'         => [10],
        ]);

        $response = $this->actingAs($admin, 'web')->put(route('admin.events.update', $event->id), [
            'title'                 => 'Winter Gala Updated',
            'description'           => 'Winter festival updated',
            'start_date'            => now()->addDays(5)->toDateString(),
            'end_date'              => now()->addDays(6)->toDateString(),
            'start_time'            => '17:00',
            'location'              => 'Lahore',
            'focal_person_name'     => 'Maj. Bilal',
            'focal_person_number'   => '03111234567',
            'registration_required' => 1,
            'entry_batches'         => [45, 12, 3, 28], // Unsorted
        ]);

        $response->assertRedirect(route('admin.events.index'));

        $event->refresh();
        $this->assertEquals([3, 12, 28, 45], $event->entry_batches);
    }

    public function test_event_model_accessor_returns_batches_in_sequential_order_even_if_raw_db_was_unsorted()
    {
        // Directly insert raw unsorted JSON into database
        $id = DB::table('events')->insertGetId([
            'title'                 => 'Raw DB Event',
            'description'           => 'Direct DB insertion',
            'start_date'            => now()->addDays(20)->toDateString(),
            'end_date'              => now()->addDays(21)->toDateString(),
            'start_time'            => '10:00',
            'location'              => 'Rawalpindi',
            'focal_person_name'     => 'Dr. Farooq',
            'focal_person_number'   => '03221234567',
            'registration_required' => 1,
            'entry_batches'         => json_encode([30, 2, 17, 8]),
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        $event = Event::findOrFail($id);
        $this->assertEquals([2, 8, 17, 30], $event->entry_batches);
    }

    public function test_admin_events_index_displays_batches_in_sequential_order()
    {
        $admin = $this->createAdmin();

        Event::create([
            'title'                 => 'Spring Festival',
            'description'           => 'Festival',
            'start_date'            => now()->addDays(10)->toDateString(),
            'end_date'              => now()->addDays(11)->toDateString(),
            'start_time'            => '12:00',
            'location'              => 'Peshawar',
            'focal_person_name'     => 'Capt. Zaid',
            'focal_person_number'   => '03331234567',
            'registration_required' => true,
            'entry_batches'         => [23, 5, 1],
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.events.index'));
        $response->assertStatus(200);
        $response->assertSee('1, 5, 23');
        $response->assertDontSee('23, 5, 1');
    }

    public function test_alumni_events_page_displays_batches_in_sequential_order()
    {
        $alumni = $this->createAlumni(15);

        Event::create([
            'title'                 => 'Alumni Dinner 2026',
            'description'           => 'Annual dinner gathering',
            'start_date'            => now()->addDays(10)->toDateString(),
            'end_date'              => now()->addDays(11)->toDateString(),
            'start_time'            => '19:00',
            'location'              => 'Islamabad',
            'focal_person_name'     => 'Maj. Bilal',
            'focal_person_number'   => '03111234567',
            'registration_required' => true,
            'entry_batches'         => [23, 5, 1],
        ]);

        $response = $this->actingAs($alumni, 'alumni')->get(route('events.index'));
        $response->assertStatus(200);

        // Check "Eligible Batches: 1, 5, 23"
        $response->assertSee('Eligible Batches:</strong> 1, 5, 23', false);
        $response->assertDontSee('23, 5, 1');

        // Check ineligible message "Open to batches: 1, 5, 23"
        $response->assertSee('Open to batches: 1, 5, 23');
    }
}
