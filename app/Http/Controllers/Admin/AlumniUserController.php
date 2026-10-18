<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniUser;
use App\Models\Country;
use App\Notifications\AlumniApproved;
use App\Notifications\AlumniRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlumniUserController extends Controller
{
    // ── Approved alumni list ──────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = AlumniUser::where('status', 'approved');

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'deactivated') {
                $query->where('is_active', false);
            }
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('ccp_no', 'like', "%{$request->search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(10);
        return view('admin.alumni.index', compact('users'));
    }

    // ── Pending approvals ─────────────────────────────────────────────────────

    public function approvals(Request $request)
    {
        $query = AlumniUser::where('status', 'pending');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('ccp_no', 'like', "%{$request->search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(10);
        return view('admin.alumni.approvals', compact('users'));
    }

    // ── Rejected alumni list ──────────────────────────────────────────────────

    public function rejected(Request $request)
    {
        $query = AlumniUser::where('status', 'rejected');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('ccp_no', 'like', "%{$request->search}%")
                  ->orWhere('rejection_reason', 'like', "%{$request->search}%");
            });
        }

        $users = $query->orderByDesc('updated_at')->paginate(10);
        return view('admin.alumni.rejected', compact('users'));
    }

    // ── Show single alumni ────────────────────────────────────────────────────

    public function show($id)
    {
        $user = AlumniUser::findOrFail($id);
        $countries = Country::orderBy('name')->get(['id', 'name']);
        return view('admin.alumni.show', compact('user', 'countries'));
    }

    // ── Update alumni profile ─────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $user = AlumniUser::findOrFail($id);
        $data = $request->except(['_token', '_method', 'profile_photo', 'cnic_file', 'phone_code']);

        if ($request->filled('phone_number')) {
            $phone = $request->phone_number;
            if ($request->filled('phone_code') && !str_starts_with($phone, '+')) {
                $clean = ltrim(preg_replace('/\D/', '', $phone), '0');
                $phone = $request->phone_code . $clean;
            }
            $data['phone_number'] = $phone;
        }

        if ($request->hasFile('profile_photo')) {
            // Delete old file if exists
            if ($user->profile_photo) {
                Storage::delete($user->profile_photo);
            }
            // Meaningful name: profile_{user_id}_{timestamp}.{ext}
            // e.g. profile_42_1718123456.jpg
            // Using user ID ensures it's always tied to the right person.
            // Timestamp ensures no collision on re-upload.
            $ext = $request->file('profile_photo')->getClientOriginalExtension();
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $ext;
            $request->file('profile_photo')->storeAs('profiles', $filename);
            $data['profile_photo'] = 'profiles/' . $filename;
        }

        if ($request->hasFile('cnic_file')) {
            // Delete old file if exists
            if ($user->cnic_file) {
                Storage::delete($user->cnic_file);
            }
            // Meaningful name: cnic_{user_id}_{timestamp}.{ext}
            // e.g. cnic_42_1718123456.png
            $ext = $request->file('cnic_file')->getClientOriginalExtension();
            $filename = 'cnic_' . $user->id . '_' . time() . '.' . $ext;
            $request->file('cnic_file')->storeAs('cnics', $filename);
            $data['cnic_file'] = 'cnics/' . $filename;
        }

        $user->update($data);
        return back()->with('success', 'Alumni profile updated successfully.');
    }

    // ── Approve ───────────────────────────────────────────────────────────────

    public function approve($id)
    {
        $user = AlumniUser::findOrFail($id);

        // Generate a random password
        $plainPassword = Str::random(10);

        $user->update([
            'status'           => 'approved',
            'is_active'        => true,
            'password'         => $plainPassword,   // model casts handle hashing
            'rejection_reason' => null,             // clear previous rejection reason
        ]);

        // Send approval email with credentials
        $user->notify(new AlumniApproved($plainPassword));

        return back()->with('success', "Alumni approved. Login credentials emailed to {$user->email}.");
    }

    // ── Reject ────────────────────────────────────────────────────────────────

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ], [
            'reason.required' => 'Please provide a reason for rejecting this application.',
        ]);

        $user = AlumniUser::findOrFail($id);
        $user->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        // Send rejection email with reason and support contacts
        $user->notify(new AlumniRejected($request->reason));

        return back()->with('success', 'Alumni application rejected. Rejection email sent with reason.');
    }

    // ── Toggle active/suspended ───────────────────────────────────────────────

    public function toggleStatus(Request $request, $id)
    {
        $user = AlumniUser::findOrFail($id);

        if ($user->is_active) {
            // Deactivating
            $request->validate([
                'reason' => 'required|string|max:1000',
            ], [
                'reason.required' => 'A deactivation reason is required.',
            ]);

            $user->update([
                'is_active'           => false,
                'deactivation_reason' => $request->reason,
            ]);
        } else {
            // Reactivating
            $user->update([
                'is_active'           => true,
                'deactivation_reason' => null,
            ]);
        }

        return response()->json([
            'success'   => true,
            'is_active' => $user->is_active,
            'reason'    => $user->deactivation_reason,
        ]);
    }

    // ── Mark as Star Alumni ───────────────────────────────────────────────────

    public function markStar(Request $request, $id)
    {
        $request->validate([
            'star_description' => 'required|string|max:1000',
        ]);

        $user = AlumniUser::findOrFail($id);
        $user->update([
            'is_star_alumni'   => true,
            'star_description' => $request->star_description,
        ]);

        return back()->with('success', "{$user->full_name} marked as Star Alumni.");
    }

// ── Update Star Description (without toggling star status) ──
public function updateStarDescription(Request $request, $id)
{
    $request->validate([
        'star_description' => 'required|string|max:1000',
    ]);

    $user = AlumniUser::findOrFail($id);
    $user->update([
        'star_description' => $request->star_description,
    ]);

    return back()->with('success', "Star description updated for {$user->full_name}.");
}

// ── Remove Star Status ────────────────────────────────────────────
public function removeStar($id)
{
    $user = AlumniUser::findOrFail($id);
    $user->update([
        'is_star_alumni'   => false,
        'star_description' => null, // optional – clear the description
    ]);

    return back()->with('success', "{$user->full_name} is no longer a Star Alumni.");
}

    // ── Export CSV ────────────────────────────────────────────────────────────

    public function export()
    {
        $users   = AlumniUser::where('status', 'approved')->get();
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=alumni_users_' . now()->format('Y-m-d') . '.csv',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone', 'CCP No', 'Entry', 'House', 'City', 'Country', 'Designation', 'Organization', 'Status', 'Star Alumni']);
            foreach ($users as $u) {
                fputcsv($file, [
                    $u->full_name, $u->email, $u->phone_number, $u->ccp_no,
                    $u->entry, $u->house, $u->current_city, $u->current_country,
                    $u->current_designation, $u->current_organization,
                    $u->status, $u->is_star_alumni ? 'Yes' : 'No',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}