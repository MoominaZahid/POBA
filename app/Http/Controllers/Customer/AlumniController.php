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
            ->where('is_active', true)
            ->where('is_star_alumni', false);

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

        // Read the target's privacy settings (already cast as array in the model)
        $privacySettings = $alumni->privacy_settings ?? [];

        // Build a simple true/false map for the blade to use
        $visibleFields = [
            'phone_number'         => $isOwnProfile || ($privacySettings['phone_number']         ?? true),
            'email'                => $isOwnProfile || ($privacySettings['email']                ?? true),
            'current_city'          => $isOwnProfile || ($privacySettings['current_city']          ?? true),
            'current_organization' => $isOwnProfile || ($privacySettings['current_organization'] ?? true),
            'current_designation'  => $isOwnProfile || ($privacySettings['current_designation']  ?? true),
            'achievements'         => $isOwnProfile || ($privacySettings['achievements']          ?? true),
            'cnic_file'            => $isOwnProfile || ($privacySettings['cnic_file']             ?? false),
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
