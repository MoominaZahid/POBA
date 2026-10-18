<?php

namespace Tests\Feature;

use App\Models\AlumniUser;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use App\Notifications\EventRegistrationSubmitted;
use App\Notifications\NewEventRegistrationAdminNotification;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EventRegistrationConfirmationTest extends TestCase
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
            'full_name'            => 'Ahmed Ali',
            'email'                => 'ahmed@poba.test',
            'password'             => bcrypt('password123'),
            'entry'                => 15,
            'ccp_no'               => 1234,
            'house'                => 'Jinnah',
            'status'               => 'approved',
            'is_active'            => true,
            'consent_sharing'      => true,
            'agree_terms'          => true,
        ]);
    }

    public function test_event_card_renders_confirmation_message_prompt_on_register_button()
    {
        $alumni = $this->createAlumni();
        $event = Event::create([
            'title'                 => 'Annual Alumni Gala 2026',
            'location'              => 'Islamabad Club',
            'start_date'            => now()->addDays(10)->toDateString(),
            'end_date'              => now()->addDays(10)->toDateString(),
            'registration_required' => true,
            'focal_person_name'     => 'Dr. Tariq',
            'focal_person_number'   => '03001234567',
        ]);

        $response = $this->actingAs($alumni, 'alumni')->get(route('events.index'));
        $response->assertStatus(200);

        // Verify the Register Now button triggers the custom confirmation modal
        $response->assertSee('showEventConfirmModal', false);
        $response->assertSee('id="eventConfirmModal"', false);
        $response->assertSee('Confirm Registration');
    }

    public function test_registering_for_event_stores_pending_participant_and_returns_confirmation_message()
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        Notification::fake();
        $alumni = $this->createAlumni();
        $event = Event::create([
            'title'                 => 'Reunion Dinner',
            'location'              => 'Rawalpindi',
            'start_date'            => now()->addDays(5)->toDateString(),
            'end_date'              => now()->addDays(5)->toDateString(),
            'registration_required' => true,
            'focal_person_name'     => 'Col. Asif',
            'focal_person_number'   => '03007654321',
        ]);

        $response = $this->actingAs($alumni, 'alumni')->post(route('events.register', $event->id));
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Registered successfully! Your status is pending confirmation.');

        $this->assertDatabaseHas('event_participants', [
            'event_id'       => $event->id,
            'alumni_user_id' => $alumni->id,
            'status'         => 'pending',
        ]);
    }

    public function test_registered_event_renders_cancel_button_with_confirmation_prompt()
    {
        $alumni = $this->createAlumni();
        $event = Event::create([
            'title'                 => 'Networking Meetup',
            'location'              => 'Lahore',
            'start_date'            => now()->addDays(7)->toDateString(),
            'end_date'              => now()->addDays(7)->toDateString(),
            'registration_required' => true,
            'focal_person_name'     => 'Engr. Bilal',
            'focal_person_number'   => '03121234567',
        ]);

        EventParticipant::create([
            'event_id'       => $event->id,
            'alumni_user_id' => $alumni->id,
            'status'         => 'pending',
        ]);

        $response = $this->actingAs($alumni, 'alumni')->get(route('events.index'));
        $response->assertStatus(200);
        $response->assertSee('showEventConfirmModal', false);
        $response->assertSee('Cancel Registration');
    }

    public function test_registering_for_event_dispatches_email_notifications_to_alumni_and_event_admins()
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        Notification::fake();

        $alumni = $this->createAlumni();

        $superAdmin = User::create([
            'name'     => 'Chief Superadmin',
            'email'    => 'super@poba.test',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);

        $eventAdmin = User::create([
            'name'        => 'Events Manager',
            'email'       => 'events@poba.test',
            'password'    => bcrypt('password123'),
            'role'        => 'admin',
            'permissions' => ['events'],
        ]);

        $galleryAdmin = User::create([
            'name'        => 'Gallery Manager',
            'email'       => 'gallery@poba.test',
            'password'    => bcrypt('password123'),
            'role'        => 'admin',
            'permissions' => ['gallery'],
        ]);

        $event = Event::create([
            'title'                 => 'Grand Reunion 2026',
            'location'              => 'Islamabad Serena Hotel',
            'start_date'            => now()->addDays(15)->toDateString(),
            'end_date'              => now()->addDays(16)->toDateString(),
            'start_time'            => '18:00:00',
            'end_time'              => '22:00:00',
            'registration_required' => true,
            'focal_person_name'     => 'Brig. Khalid',
            'focal_person_number'   => '03001234567',
        ]);

        $response = $this->actingAs($alumni, 'alumni')->post(route('events.register', $event->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 1. Alumni receives confirmation email
        Notification::assertSentTo($alumni, EventRegistrationSubmitted::class, function ($notification) use ($event, $alumni) {
            $mail = $notification->toMail($alumni);
            $this->assertEquals("Event Registration Received — {$event->title}", $mail->subject);
            return true;
        });

        // 2. Super Admin receives new registration alert
        Notification::assertSentTo($superAdmin, NewEventRegistrationAdminNotification::class, function ($notification) use ($event, $superAdmin) {
            $mail = $notification->toMail($superAdmin);
            $this->assertEquals("New Event Registration — {$event->title}", $mail->subject);
            return true;
        });

        // 3. Sub-Admin with 'events' permission receives alert
        Notification::assertSentTo($eventAdmin, NewEventRegistrationAdminNotification::class);

        // 4. Sub-Admin WITHOUT 'events' permission does NOT receive alert
        Notification::assertNotSentTo($galleryAdmin, NewEventRegistrationAdminNotification::class);
    }
}
