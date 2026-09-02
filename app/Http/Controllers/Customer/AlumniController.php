<?php
// FILE: app/Http/Controllers/Customer/AlumniController.php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AlumniUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    // ── Alumni Directory ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = AlumniUser::where('status', 'approved')
            ->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('class_year')) {
            $query->where('class_year', $request->class_year);
        }
        if ($request->filled('field_work')) {
            $query->where('field_of_work', $request->field_work);
        }
        if ($request->filled('city')) {
            $query->where('current_city', $request->city);
        }

        $alumni = $query->orderByDesc('created_at')->paginate(8);
        return view('customer.alumni.index', compact('alumni'));
    }

    // ── Alumni Profile Detail ─────────────────────────────────────────────────
    public function show($id)
    {
        $viewer = Auth::guard('alumni')->user();

        $alumni = AlumniUser::where('status', 'approved')
            ->where('is_active', true)
            ->findOrFail($id);

        // Always show full profile when viewing your own page
        $isOwnProfile = $viewer && $viewer->id === $alumni->id;

        // privacy_settings stores the human-readable labels chosen in the form
        // (e.g. "Email Address", "Phone Number", "City", "Designation", etc.)
        // We normalise to a flat array of hidden labels for easy lookup.
        $hidden = [];
        if (!$isOwnProfile) {
            $raw = $alumni->privacy_settings ?? [];
            // Stored as a flat array of label strings: ["Email Address","Phone Number",…]
            if (is_array($raw)) {
                $hidden = array_map('strtolower', $raw);
            }
        }

        // Map each label (lower-cased) to the DB column it controls.
        // A field is VISIBLE when its label is NOT in the hidden list.
        $isHidden = function (string $label) use ($hidden): bool {
            return in_array(strtolower($label), $hidden, true);
        };

        $visibleFields = [
            'email'                => $isOwnProfile || !$isHidden('email address'),
            'phone_number'         => $isOwnProfile || !$isHidden('phone number'),
            'current_city'         => $isOwnProfile || !$isHidden('city'),
            'current_designation'  => $isOwnProfile || !$isHidden('designation'),
            'current_organization' => $isOwnProfile || !$isHidden('organization'),
            'field_of_study'       => $isOwnProfile || !$isHidden('field of study'),
            'field_of_work'        => $isOwnProfile || !$isHidden('field of work'),
            'achievements'         => $isOwnProfile || !$isHidden('achievements'),
            'cnic_file'            => false, // always hidden from public
        ];

        return view('customer.alumni.show', compact('alumni', 'visibleFields', 'isOwnProfile'));
    }

    // ── Star Alumni Listing ───────────────────────────────────────────────────
    public function starAlumni(Request $request)
    {
        $query = AlumniUser::where('status', 'approved')
            ->where('is_active', true)
            ->where('is_star_alumni', true);

        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('class_year')) {
            $query->where('class_year', $request->class_year);
        }
        if ($request->filled('field_work')) {
            $query->where('field_of_work', $request->field_work);
        }
        if ($request->filled('city')) {
            $query->where('current_city', $request->city);
        }

        $alumni = $query->orderByDesc('created_at')->paginate(8);
        return view('customer.star-alumni.index', compact('alumni'));
    }
}
