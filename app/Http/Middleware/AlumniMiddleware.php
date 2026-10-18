<?php
//  controllers/Middleware/AlumniMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AlumniMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('alumni')->check()) {
            // redirect()->guest() stores the current URL in the
            // 'url.intended' session key, so LoginController's
            // redirect()->intended() can send the user back here
            // after a successful login instead of falling back to home.
            return redirect()->guest(route('login'))
                ->with('error', 'Please login to continue.');
        }

        $alumni = Auth::guard('alumni')->user();

        if (! $alumni->is_active || $alumni->status !== 'approved') {
            Auth::guard('alumni')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $supportEmail = \App\Models\CmsSetting::get('contact_email') ?: config('mail.from.address', 'info@poba.com');
            $supportPhone = \App\Models\CmsSetting::get('contact_number') ?: '+92 21 123 4567';

            if ($alumni->status === 'rejected') {
                $reasonText = $alumni->rejection_reason ? " Reason: {$alumni->rejection_reason}." : "";
                $msg = "Your account has been rejected.{$reasonText} Please contact support at {$supportEmail} / {$supportPhone}.";
                return redirect()->route('login')
                    ->with('error', $msg)
                    ->with('blocked_modal', [
                        'title'   => 'Account Rejected',
                        'type'    => 'rejected',
                        'reason'  => $alumni->rejection_reason,
                        'email'   => $supportEmail,
                        'phone'   => $supportPhone,
                        'message' => $msg,
                    ]);
            }

            $reasonText = $alumni->deactivation_reason ? " Reason: {$alumni->deactivation_reason}." : "";
            $msg = "Your account has been deactivated.{$reasonText} Please contact support at {$supportEmail} / {$supportPhone}.";
            return redirect()->route('login')
                ->with('error', $msg)
                ->with('blocked_modal', [
                    'title'   => 'Account Deactivated',
                    'type'    => 'deactivated',
                    'reason'  => $alumni->deactivation_reason,
                    'email'   => $supportEmail,
                    'phone'   => $supportPhone,
                    'message' => $msg,
                ]);
        }

        return $next($request);
    }
}