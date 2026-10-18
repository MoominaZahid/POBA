<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('alumni')->check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        // 1. Try admin guard first
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // 2. Check alumni account status
        $alumni = \App\Models\AlumniUser::where('email', $request->email)->first();
        if ($alumni) {
            $supportEmail = \App\Models\CmsSetting::get('contact_email') ?: config('mail.from.address', 'info@poba.com');
            $supportPhone = \App\Models\CmsSetting::get('contact_number') ?: '+92 21 123 4567';

            if ($alumni->status === 'rejected') {
                $reasonText = $alumni->rejection_reason ? " Reason: {$alumni->rejection_reason}." : "";
                $msg = "Your account has been rejected.{$reasonText} Please contact support at {$supportEmail} / {$supportPhone}.";
                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => $msg])
                    ->with('blocked_modal', [
                        'title'   => 'Account Rejected',
                        'type'    => 'rejected',
                        'reason'  => $alumni->rejection_reason,
                        'email'   => $supportEmail,
                        'phone'   => $supportPhone,
                        'message' => $msg,
                    ]);
            }

            if (! $alumni->is_active) {
                $reasonText = $alumni->deactivation_reason ? " Reason: {$alumni->deactivation_reason}." : "";
                $msg = "Your account has been deactivated.{$reasonText} Please contact support at {$supportEmail} / {$supportPhone}.";
                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => $msg])
                    ->with('blocked_modal', [
                        'title'   => 'Account Deactivated',
                        'type'    => 'deactivated',
                        'reason'  => $alumni->deactivation_reason,
                        'email'   => $supportEmail,
                        'phone'   => $supportPhone,
                        'message' => $msg,
                    ]);
            }

            if ($alumni->status !== 'approved') {
                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => 'Your account is pending admin approval. You will receive an email once approved.']);
            }
        }

        // 3. Try alumni guard authentication
        if (Auth::guard('alumni')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('alumni')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}