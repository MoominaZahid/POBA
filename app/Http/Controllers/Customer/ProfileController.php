<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AlumniUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ── Show profile edit page ────────────────────────────────────────────────

    public function edit()
    {
        $alumni = Auth::guard('alumni')->user();
        return view('customer.profile.edit', compact('alumni'));
    }

    // ── Update general info ───────────────────────────────────────────────────

    public function update(Request $request)
    {
        $user = AlumniUser::findOrFail(Auth::guard('alumni')->id());

        $request->validate([
            'full_name'            => 'sometimes|required|string|max:255',
            'phone_number'         => 'sometimes|required|string|max:20',
            'entry'                => 'nullable|string|max:20',
            'class_year'           => 'nullable|numeric|digits:4',
            'ccp_no'               => ['sometimes', 'required', 'string', 'max:50', \Illuminate\Validation\Rule::unique('alumni_users', 'ccp_no')->ignore($user->id)],
            'house'                => 'nullable|string|max:100',
            'education'            => 'nullable|string|max:100',
            'field_of_study'       => 'nullable|string|max:100',
            'field_of_work'        => 'nullable|string|max:100',
            'current_city'         => 'nullable|string|max:100',
            'current_country'      => 'nullable|string|max:100',
            'current_designation'  => 'nullable|string|max:150',
            'current_organization' => 'nullable|string|max:150',
            'achievements'         => 'nullable|string|max:2000',
            'profile_photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'cnic_file'            => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'ccp_no.unique' => 'This CCP No. is already registered to another user.',
        ]);

        $data = $request->except(['_token', '_method', 'profile_photo', 'cnic_file', 'privacy_hide', 'phone_code']);

        // Format phone number with country code if provided
        if ($request->filled('phone_number')) {
            $phone = $request->phone_number;
            if ($request->filled('phone_code') && !str_starts_with($phone, '+')) {
                $phone = $request->phone_code . $phone;
            }
            $data['phone_number'] = $phone;
        }

        // Profile photo
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $ext      = $request->file('profile_photo')->getClientOriginalExtension();
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $ext;
            $request->file('profile_photo')->storeAs('profiles', $filename, 'public');
            $data['profile_photo'] = 'profiles/' . $filename;
        }

        // CNIC file
        if ($request->hasFile('cnic_file')) {
            if ($user->cnic_file) {
                Storage::disk('public')->delete($user->cnic_file);
            }
            $ext      = $request->file('cnic_file')->getClientOriginalExtension();
            $filename = 'cnic_' . $user->id . '_' . time() . '.' . $ext;
            $request->file('cnic_file')->storeAs('cnics', $filename, 'public');
            $data['cnic_file'] = 'cnics/' . $filename;
        }

        // Privacy settings (only update if privacy_hide parameter was present in request)
        if ($request->has('privacy_hide')) {
            $data['privacy_settings'] = $request->privacy_hide ?? [];
        }

        // Save to database
        $user->fill($data)->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // ── Change password ───────────────────────────────────────────────────────

    public function changePassword(Request $request)
    {
        $alumni = Auth::guard('alumni')->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $alumni->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $alumni->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('password_success', 'Password changed successfully.');
    }
}