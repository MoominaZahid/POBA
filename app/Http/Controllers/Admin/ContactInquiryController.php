<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Notifications\ContactInquiryReplyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ContactInquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactInquiry::query();

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true)->whereNull('responded_at');
            } elseif ($request->status === 'responded') {
                $query->whereNotNull('responded_at');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->orderByDesc('created_at')->paginate(15);
        $unreadCount = ContactInquiry::where('is_read', false)->count();

        return view('admin.inquiries.index', compact('inquiries', 'unreadCount'));
    }

    public function show($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        if (! $inquiry->is_read) {
            $inquiry->update(['is_read' => true]);
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'id'            => $inquiry->id,
                'name'          => $inquiry->full_name,
                'email'         => $inquiry->email_address,
                'phone'         => $inquiry->formatted_phone,
                'message'       => $inquiry->message,
                'date'          => $inquiry->created_at->format('M d, Y h:i A'),
                'is_read'       => $inquiry->is_read,
                'responded_at'  => $inquiry->responded_at?->format('M d, Y h:i A'),
                'admin_notes'   => $inquiry->admin_notes,
                'unread_count'  => ContactInquiry::where('is_read', false)->count(),
            ]);
        }

        return back();
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string|max:5000',
            'subject'       => 'nullable|string|max:255',
        ], [
            'reply_message.required' => 'Please enter your reply message.',
        ]);

        $inquiry = ContactInquiry::findOrFail($id);
        $subject = $request->input('subject') ?: "Re: Inquiry to POBA from {$inquiry->full_name}";
        $replyMessage = $request->input('reply_message');

        try {
            Notification::route('mail', $inquiry->email_address)
                ->notify(new ContactInquiryReplyNotification($inquiry, $subject, $replyMessage));
        } catch (\Throwable $e) {
            Log::warning('Sending inquiry reply email failed: ' . $e->getMessage());
            return back()->with('error', 'Could not deliver email: ' . $e->getMessage());
        }

        $inquiry->update([
            'is_read'      => true,
            'responded_at' => now(),
            'admin_notes'  => $replyMessage,
        ]);

        return back()->with('success', "Reply successfully sent to {$inquiry->email_address}.");
    }

    public function toggleRead($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->update([
            'is_read' => ! $inquiry->is_read,
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success'      => true,
                'is_read'      => $inquiry->is_read,
                'unread_count' => ContactInquiry::where('is_read', false)->count(),
            ]);
        }

        return back()->with('success', 'Inquiry status updated.');
    }

    public function destroy($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->delete();

        return back()->with('success', 'Inquiry deleted successfully.');
    }
}
