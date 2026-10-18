<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventLogoMandatoryTest extends TestCase
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

    public function test_event_creation_fails_when_logo_is_missing()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'web')->post(route('admin.events.store'), [
            'title'                 => 'Alumni Gala 2026',
            'description'           => 'Annual reunion gala',
            'start_date'            => now()->addDays(10)->toDateString(),
            'end_date'              => now()->addDays(11)->toDateString(),
            'start_time'            => '18:00',
            'location'              => 'Islamabad',
            'focal_person_name'     => 'Col. Asif',
            'focal_person_number'   => '03001234567',
            'registration_required' => 1,
            'entry_batches'         => [5, 10],
            // 'logo' omitted
        ]);

        $response->assertSessionHasErrors([
            'logo' => 'The event logo is required.',
        ]);

        $this->assertDatabaseCount('events', 0);
    }

    public function test_event_creation_fails_when_logo_is_not_an_image()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'web')->post(route('admin.events.store'), [
            'title'                 => 'Alumni Gala 2026',
            'description'           => 'Annual reunion gala',
            'start_date'            => now()->addDays(10)->toDateString(),
            'end_date'              => now()->addDays(11)->toDateString(),
            'start_time'            => '18:00',
            'location'              => 'Islamabad',
            'focal_person_name'     => 'Col. Asif',
            'focal_person_number'   => '03001234567',
            'registration_required' => 1,
            'entry_batches'         => [5, 10],
            'logo'                  => UploadedFile::fake()->create('document.pdf', 500),
        ]);

        $response->assertSessionHasErrors('logo');
        $this->assertDatabaseCount('events', 0);
    }

    private function createFakeImage(string $name = 'logo.png'): UploadedFile
    {
        $content = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAA=');
        return UploadedFile::fake()->createWithContent($name, $content);
    }

    public function test_event_creation_succeeds_when_valid_logo_is_provided()
    {
        Storage::fake('public');
        $admin = $this->createAdmin();

        $file = $this->createFakeImage('event_banner.png');

        $response = $this->actingAs($admin, 'web')->post(route('admin.events.store'), [
            'title'                 => 'Alumni Gala 2026',
            'description'           => 'Annual reunion gala',
            'start_date'            => now()->addDays(10)->toDateString(),
            'end_date'              => now()->addDays(11)->toDateString(),
            'start_time'            => '18:00',
            'location'              => 'Islamabad',
            'focal_person_name'     => 'Col. Asif',
            'focal_person_number'   => '03001234567',
            'registration_required' => 1,
            'entry_batches'         => [5, 10],
            'logo'                  => $file,
        ]);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success');

        $event = Event::first();
        $this->assertNotNull($event);
        $this->assertNotNull($event->logo);
        Storage::disk('public')->assertExists($event->logo);
    }

    public function test_event_update_does_not_require_reuploading_logo_if_already_present()
    {
        $admin = $this->createAdmin();

        $event = Event::create([
            'title'                 => 'Original Title',
            'description'           => 'Original Description',
            'start_date'            => now()->addDays(5)->toDateString(),
            'end_date'              => now()->addDays(6)->toDateString(),
            'start_time'            => '10:00',
            'location'              => 'Lahore',
            'focal_person_name'     => 'Maj. Bilal',
            'focal_person_number'   => '03111234567',
            'registration_required' => true,
            'entry_batches'         => [5],
            'logo'                  => 'events/existing_logo.png',
        ]);

        $response = $this->actingAs($admin, 'web')->put(route('admin.events.update', $event->id), [
            'title'                 => 'Updated Title',
            'description'           => 'Updated Description',
            'start_date'            => now()->addDays(5)->toDateString(),
            'end_date'              => now()->addDays(6)->toDateString(),
            'start_time'            => '10:00',
            'location'              => 'Lahore',
            'focal_person_name'     => 'Maj. Bilal',
            'focal_person_number'   => '03111234567',
            'registration_required' => 1,
            'entry_batches'         => [5],
            // 'logo' omitted intentionally
        ]);

        $response->assertRedirect(route('admin.events.index'));
        $event->refresh();
        $this->assertEquals('Updated Title', $event->title);
        $this->assertEquals('events/existing_logo.png', $event->logo);
    }

    public function test_event_update_fails_if_event_has_no_logo_and_none_provided()
    {
        $admin = $this->createAdmin();

        $event = Event::create([
            'title'                 => 'Legacy Event Without Logo',
            'description'           => 'Legacy Description',
            'start_date'            => now()->addDays(5)->toDateString(),
            'end_date'              => now()->addDays(6)->toDateString(),
            'start_time'            => '10:00',
            'location'              => 'Lahore',
            'focal_person_name'     => 'Maj. Bilal',
            'focal_person_number'   => '03111234567',
            'registration_required' => true,
            'entry_batches'         => [5],
            'logo'                  => null,
        ]);

        $response = $this->actingAs($admin, 'web')->put(route('admin.events.update', $event->id), [
            'title'                 => 'Legacy Event Updated',
            'description'           => 'Legacy Description',
            'start_date'            => now()->addDays(5)->toDateString(),
            'end_date'              => now()->addDays(6)->toDateString(),
            'start_time'            => '10:00',
            'location'              => 'Lahore',
            'focal_person_name'     => 'Maj. Bilal',
            'focal_person_number'   => '03111234567',
            'registration_required' => 1,
            'entry_batches'         => [5],
        ]);

        $response->assertSessionHasErrors([
            'logo' => 'The event logo is required.',
        ]);
    }
}
