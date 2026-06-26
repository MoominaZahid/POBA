<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{
    const PER_PAGE = 5;

    public function index(Request $request)
    {
        $now     = Carbon::now();
        $today   = $now->toDateString();   // e.g. "2026-06-26"
        $timeNow = $now->toTimeString();   // e.g. "17:00:00"

        // ─────────────────────────────────────────────────────────────────
        // UPCOMING: event has NOT yet ended
        //   • end_date is AFTER today, OR
        //   • end_date IS today AND end_time >= current time, OR
        //   • end_date IS today AND end_time IS NULL  (treat as 23:59:59 – still running)
        // ─────────────────────────────────────────────────────────────────
        $upcomingQuery = Event::where(function ($q) use ($today, $timeNow) {
            // Ends on a future date
            $q->where('end_date', '>', $today)
              // OR ends today and hasn't finished yet (or has no end_time)
              ->orWhere(function ($q2) use ($today, $timeNow) {
                  $q2->where('end_date', '=', $today)
                     ->where(function ($q3) use ($timeNow) {
                         $q3->whereNull('end_time')
                            ->orWhere('end_time', '>=', $timeNow);
                     });
              });
        })->orderBy('start_date')->orderBy('start_time');

        // ─────────────────────────────────────────────────────────────────
        // PREVIOUS: event has already ended
        //   • end_date is BEFORE today, OR
        //   • end_date IS today AND end_time < current time (and end_time is not null)
        // ─────────────────────────────────────────────────────────────────
        $previousQuery = Event::where(function ($q) use ($today, $timeNow) {
            // Ended on a past date
            $q->where('end_date', '<', $today)
              // OR ended today and the end_time has already passed
              ->orWhere(function ($q2) use ($today, $timeNow) {
                  $q2->where('end_date', '=', $today)
                     ->whereNotNull('end_time')
                     ->where('end_time', '<', $timeNow);
              });
        })->orderByDesc('end_date')->orderByDesc('end_time');

        $upcoming = $upcomingQuery->paginate(self::PER_PAGE, ['*'], 'upcoming_page');
        $previous = $previousQuery->paginate(self::PER_PAGE, ['*'], 'previous_page');

        // ── Fetch this alumni's registrations ────────────────────────────
        $myEventIds       = [];
        $myParticipations = [];

        if (Auth::guard('alumni')->check()) {
            $rows = EventParticipant::where('alumni_user_id', Auth::guard('alumni')->id())
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->get(['event_id', 'status']);

            foreach ($rows as $r) {
                $myEventIds[]                  = $r->event_id;
                $myParticipations[$r->event_id] = $r->status;
            }
        }

        // ── AJAX load-more ───────────────────────────────────────────────
        if ($request->ajax()) {
            $tab        = $request->get('tab', 'upcoming');
            $events     = $tab === 'upcoming' ? $upcoming : $previous;
            $isPrevious = $tab === 'previous';

            return response()->json([
                'html'    => view('customer.events._event_cards', compact(
                    'events', 'myEventIds', 'myParticipations', 'isPrevious'
                ))->render(),
                'hasMore' => $events->hasMorePages(),
            ]);
        }

        return view('customer.events.index', compact(
            'upcoming', 'previous', 'myEventIds', 'myParticipations'
        ));
    }

    // ── Register ─────────────────────────────────────────────────────────

    public function register($id)
    {
        $alumni = Auth::guard('alumni')->user();
        $event  = Event::findOrFail($id);

        if (!$event->registration_required) {
            return back()->with('error', 'This event does not require registration.');
        }

        // Block if the event has already ended
        $now         = Carbon::now();
        $endDateTime = Carbon::parse(
            $event->end_date . ' ' . ($event->end_time ?? '23:59:59')
        );
        if ($endDateTime->lt($now)) {
            return back()->with('error', 'Registration is closed – this event has already ended.');
        }

        // Batch eligibility check
        if (!empty($event->entry_batches)) {
            if (!in_array((int) $alumni->entry, $event->entry_batches)) {
                return back()->with('error',
                    'You are not eligible for this event. It is open to batches: '
                    . implode(', ', $event->entry_batches) . '.'
                );
            }
        }

        $existing = EventParticipant::where('event_id', $id)
                        ->where('alumni_user_id', $alumni->id)
                        ->first();

        if ($existing) {
            if ($existing->status !== 'cancelled') {
                return back()->with('error', 'You are already registered for this event.');
            }
            $existing->update(['status' => 'pending']);
        } else {
            EventParticipant::create([
                'event_id'       => $id,
                'alumni_user_id' => $alumni->id,
                'status'         => 'pending',
            ]);
        }

        return back()->with('success', 'Registered successfully! Your status is pending confirmation.');
    }

    // ── Cancel ───────────────────────────────────────────────────────────

    public function cancel($id)
    {
        $alumniId    = Auth::guard('alumni')->id();
        $participant = EventParticipant::where('event_id', $id)
                            ->where('alumni_user_id', $alumniId)
                            ->whereIn('status', ['pending', 'confirmed'])
                            ->first();

        if (!$participant) {
            return back()->with('error', 'No active registration found for this event.');
        }

        $participant->update(['status' => 'cancelled']);
        return back()->with('success', 'Your registration has been cancelled.');
    }
}