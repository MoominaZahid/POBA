<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AlumniUser;
use App\Models\City;
use App\Models\Country;
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

        $countries = Country::orderBy('name')->get(['id', 'name']);

        return view('customer.member.register', compact('n1', 'n2', 'countries'));
    }

    public function citiesByCountry(Country $country)
    {
        $cities = City::where('country_id', $country->id)
            ->orderBy('name')
            ->pluck('name');

        return response()->json($cities);
    }

    public function store(Request $request)
    {
        if (\Illuminate\Support\Facades\Auth::guard('alumni')->check()) {
            return redirect()->route('home')->with('info', 'You are already a registered and logged-in member.');
        }
        $expectedCaptcha = session('captcha_ans');

        // Normalize email before validation to ensure case-insensitive uniqueness
        if ($request->filled('email')) {
            $request->merge(['email' => trim(strtolower($request->email))]);
        }

        // Intercept rejected or deactivated alumni attempting to register again
        $email = $request->filled('email') ? trim(strtolower($request->email)) : null;
        $ccpNo = $request->input('ccp_no');

        $blockedErrors = [];
        $existingBlocked = null;
        $blockedField = null;

        $supportEmail = \App\Models\CmsSetting::get('contact_email') ?: config('mail.from.address', 'info@poba.com');
        $supportPhone = \App\Models\CmsSetting::get('contact_number') ?: '+92 21 123 4567';

        // 1. Check email
        if ($email) {
            $userByEmail = AlumniUser::where('email', $email)->first();
            if ($userByEmail && ($userByEmail->status === 'rejected' || ! $userByEmail->is_active)) {
                $statusType = $userByEmail->status === 'rejected' ? 'rejected' : 'deactivated';
                $reasonText = ($statusType === 'rejected' ? $userByEmail->rejection_reason : $userByEmail->deactivation_reason);
                $reasonSnippet = $reasonText ? " Reason: {$reasonText}." : "";
                $blockedErrors['email'] = "Your account with this email address has been {$statusType}.{$reasonSnippet} Please contact support at {$supportEmail} / {$supportPhone}.";
                $existingBlocked = $userByEmail;
                $blockedField = 'email';
            }
        }

        // 2. Check CCP No.
        if ($ccpNo) {
            $userByCcp = AlumniUser::where('ccp_no', $ccpNo)->first();
            if ($userByCcp && ($userByCcp->status === 'rejected' || ! $userByCcp->is_active)) {
                $statusType = $userByCcp->status === 'rejected' ? 'rejected' : 'deactivated';
                $reasonText = ($statusType === 'rejected' ? $userByCcp->rejection_reason : $userByCcp->deactivation_reason);
                $reasonSnippet = $reasonText ? " Reason: {$reasonText}." : "";
                $blockedErrors['ccp_no'] = "An account with this CCP No. has been {$statusType}.{$reasonSnippet} Please contact support at {$supportEmail} / {$supportPhone}.";
                if (!$existingBlocked) {
                    $existingBlocked = $userByCcp;
                    $blockedField = 'ccp_no';
                }
            }
        }

        // 3. Check Phone
        if ($request->filled('phone_number')) {
            $cleanDigits = preg_replace('/\D/', '', $request->phone_number);
            $significant = ltrim($cleanDigits, '0');
            if (strlen($significant) >= 7) {
                $candidate = AlumniUser::where(function ($q) use ($significant) {
                    $q->where('phone_number', 'like', "%{$significant}%")
                      ->orWhereRaw("REPLACE(REPLACE(REPLACE(phone_number, ' ', ''), '-', ''), '+', '') LIKE ?", ["%{$significant}%"]);
                })->first();
                if ($candidate && ($candidate->status === 'rejected' || ! $candidate->is_active)) {
                    $statusType = $candidate->status === 'rejected' ? 'rejected' : 'deactivated';
                    $reasonText = ($statusType === 'rejected' ? $candidate->rejection_reason : $candidate->deactivation_reason);
                    $reasonSnippet = $reasonText ? " Reason: {$reasonText}." : "";
                    $blockedErrors['phone_number'] = "An account with this phone number has been {$statusType}.{$reasonSnippet} Please contact support at {$supportEmail} / {$supportPhone}.";
                    if (!$existingBlocked) {
                        $existingBlocked = $candidate;
                        $blockedField = 'phone_number';
                    }
                }
            }
        }

        if (! empty($blockedErrors)) {
            $statusType = $existingBlocked->status === 'rejected' ? 'rejected' : 'deactivated';
            $primaryMsg = reset($blockedErrors);
            $primaryReason = $statusType === 'rejected' ? $existingBlocked->rejection_reason : $existingBlocked->deactivation_reason;

            return back()
                ->withInput()
                ->withErrors($blockedErrors)
                ->with('blocked_modal', [
                    'title'   => ($blockedField === 'ccp_no' ? 'CCP Number' : ($blockedField === 'phone_number' ? 'Phone Number' : 'Account')) . ' ' . ucfirst($statusType),
                    'type'    => $statusType,
                    'field'   => $blockedField,
                    'reason'  => $primaryReason,
                    'email'   => $supportEmail,
                    'phone'   => $supportPhone,
                    'message' => $primaryMsg,
                ]);
        }

        $request->validate([
            'full_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:alumni_users,email|unique:users,email',
            'phone_code'      => ['required', 'string', 'regex:/^\+[0-9]{1,4}$/'],
            'phone_number'    => [
                'required',
                'string',
                'regex:/^[0-9]+$/',
                function ($attribute, $value, $fail) use ($request) {
                    if (AlumniUser::phoneAlreadyExists($value, $request->phone_code)) {
                        $fail('This phone number is already associated with an existing account.');
                    }
                },
            ],
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
            'profile_photo'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'captcha_answer'  => 'required|in:' . ($expectedCaptcha ?? 'none'),
            'consent_sharing' => 'accepted',
            'agree_terms'     => 'accepted',
        ], [
            'email.unique'            => 'This email address is already associated with an existing account.',
            'phone_number.regex'      => 'The phone number must contain only digits.',
            'ccp_no.unique'           => 'This CCP No. is already associated with an existing active user account.',
            'captcha_answer.required' => 'Please solve the Security Captcha verification.',
            'captcha_answer.in'       => 'Incorrect Security Captcha answer. Please try again.',
            'cnic_file.required'      => 'Please upload your CNIC or identity document.',
            'cnic_file.file'          => 'Invalid file format. Only PDF, JPG, and PNG are allowed.',
            'cnic_file.mimes'         => 'Invalid file format. Only PDF, JPG, and PNG are allowed.',
            'cnic_file.max'           => 'File size exceeds 5MB limit. Please choose a smaller file.',
            'profile_photo.file'      => 'Invalid file format. Only PDF, JPG, and PNG are allowed.',
            'profile_photo.mimes'     => 'Invalid file format. Only PDF, JPG, and PNG are allowed.',
            'profile_photo.max'       => 'File size exceeds 5MB limit. Please choose a smaller file.',
        ]);

        // ── Combine phone code and number (strip leading zeros for clean international standard) ──
        $cleanNumber = preg_replace('/\D/', '', $request->phone_number);
        $significantNumber = ltrim($cleanNumber, '0');
        $fullPhone = $request->phone_code . $significantNumber;

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
            $request->file('profile_photo')->storeAs('profiles', $filename);
            $alumni->update(['profile_photo' => 'profiles/' . $filename]);
        }

        if ($request->hasFile('cnic_file')) {
            $ext      = $request->file('cnic_file')->getClientOriginalExtension();
            $filename = 'cnic_' . $alumni->id . '_' . time() . '.' . $ext;
            $request->file('cnic_file')->storeAs('cnics', $filename);
            $alumni->update(['cnic_file' => 'cnics/' . $filename]);
        }

        // ── Notify all Super Admins about new registration ────────────────────
        $superAdmins = User::where('role', 'superadmin')->get();
        Notification::send($superAdmins, new AlumniRegistrationReceived($alumni));

        return redirect()->route('home')
                         ->with('registration_success', 'Your application has been submitted successfully! You will receive an email once your account is approved.');
    }
}