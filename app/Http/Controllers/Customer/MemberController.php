<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AlumniUser;
use App\Models\User;
use App\Notifications\AlumniRegistrationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index()
    {
        if (\Illuminate\Support\Facades\Auth::guard('alumni')->check()) {
            return redirect()->route('home')->with('info', 'You are already a registered and logged-in member.');
        }

        // Generate math captcha challenge for security verification
        $n1 = rand(1, 9);
        $n2 = rand(1, 9);
        session([
            'captcha_n1'  => $n1,
            'captcha_n2'  => $n2,
            'captcha_ans' => $n1 + $n2,
        ]);

        return view('customer.member.register', compact('n1', 'n2'));
    }

    public function store(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::guard('alumni')->check()) {
            return redirect()->route('home')->with('info', 'You are already a registered and logged-in member.');
        }
        $expectedCaptcha = session('captcha_ans');

        $request->validate([
            'full_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:alumni_users,email',
            'phone_code'      => 'required|string|in:+92,+1,+44,+971,+966,+91,+86,+81,+61,+49,+33,+39,+34,+55,+27',
            'phone_number'    => ['required', 'string', 'regex:/^[0-9]+$/'],
            'entry'           => 'required|string|max:20',
            'class_year'      => 'required|numeric|digits:4',
            'ccp_no'          => 'required|string|max:50|unique:alumni_users,ccp_no',
            'house'           => 'required|string|max:100',
            'education'       => 'required|string|max:100',
            'field_of_study'  => 'required|string|max:100',
            'field_of_work'   => 'required|string|max:100',
            'current_city'    => 'required|string|max:100',
            'current_country' => 'required|string|max:100',
            'cnic_file'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'profile_photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'captcha_answer'  => 'required|in:' . ($expectedCaptcha ?? 'none'),
            'consent_sharing' => 'accepted',
            'agree_terms'     => 'accepted',
        ], [
            'phone_number.regex'   => 'The phone number must contain only digits.',
            'ccp_no.unique'        => 'This CCP No. is already associated with an existing active user account.',
            'captcha_answer.required' => 'Please solve the Security Captcha verification.',
            'captcha_answer.in'       => 'Incorrect Security Captcha answer. Please try again.',
        ]);

        // ── Combine phone code and number ─────────────────────────────────────
        $fullPhone = $request->phone_code . $request->phone_number;

        // ── Prepare data (exclude files and privacy, they are handled later) ──
        $data = $request->except(['_token', 'profile_photo', 'cnic_file', 'privacy_hide', 'phone_code']);
        $data['phone_number'] = $fullPhone;          // store full number
        $data['password']     = \Illuminate\Support\Str::random(10); // placeholder
        $data['status']       = 'pending';
        $data['is_active']    = false;

        if ($request->filled('privacy_hide')) {
            $data['privacy_settings'] = $request->privacy_hide;
        }

        // ── Create alumni record ──────────────────────────────────────────────
        $alumni = AlumniUser::create($data);

        // ── Store files using the alumni ID and timestamp ─────────────────────
        if ($request->hasFile('profile_photo')) {
            $ext      = $request->file('profile_photo')->getClientOriginalExtension();
            $filename = 'profile_' . $alumni->id . '_' . time() . '.' . $ext;
            $request->file('profile_photo')->storeAs('profiles', $filename, 'public');
            $alumni->update(['profile_photo' => 'profiles/' . $filename]);
        }

        if ($request->hasFile('cnic_file')) {
            $ext      = $request->file('cnic_file')->getClientOriginalExtension();
            $filename = 'cnic_' . $alumni->id . '_' . time() . '.' . $ext;
            $request->file('cnic_file')->storeAs('cnics', $filename, 'public');
            $alumni->update(['cnic_file' => 'cnics/' . $filename]);
        }

        // ── Notify all Super Admins about new registration ────────────────────
        $superAdmins = User::where('role', 'superadmin')->get();
        Notification::send($superAdmins, new AlumniRegistrationReceived($alumni));

        return redirect()->route('home')
                         ->with('registration_success', 'Your application has been submitted successfully! You will receive an email once your account is approved.');
    }
}