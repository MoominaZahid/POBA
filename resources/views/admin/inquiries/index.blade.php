{{-- FILE: resources/views/admin/inquiries/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Contact Inquiries - Admin')
@section('page-title', 'Contact Inquiries')
@section('content')

<style>
.modal-overlay {
    display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65);
    z-index: 99999; align-items: flex-start; justify-content: center;
    overflow-y: auto; padding: 40px 16px; box-sizing: border-box;
    backdrop-filter: blur(2px);
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: #fff; border-radius: 14px; padding: 24px 28px; width: 640px;
    max-width: 96vw; max-height: calc(100vh - 80px); overflow-y: auto; position: relative;
    margin: 0 auto; box-shadow: 0 20px 50px rgba(0,0,0,.35);
}
.modal-close {
    position: absolute; top: 16px; right: 18px; background: none; border: none;
    font-size: 22px; cursor: pointer; color: #64748b; line-height: 1;
}
.modal-close:hover { color: #1e293b; }
.inquiry-unread-row {
    background: #f0fdfa !important;
    font-weight: 600;
}
.badge-responded {
    background: #dcfce7 !important; color: #15803d !important;
    border: 1px solid #86efac !important;
}
.badge-unread {
    background: #fef3c7 !important; color: #92400e !important;
    border: 1px solid #fcd34d !important;
}
.badge-read {
    background: #f1f5f9 !important; color: #64748b !important;
    border: 1px solid #cbd5e1 !important;
}
/* Inquiries table sizing and responsive scroll */
.inquiries-table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.inquiries-table th {
    padding: 12px 10px !important;
}
.inquiries-table td {
    padding: 12px 10px !important;
}
.action-icons-inquiries {
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    justify-content: flex-end !important;
}
.action-icons-inquiries .btn-reply {
    width: auto !important;
    min-width: unset !important;
    height: 26px !important;
    padding: 0 8px !important;
    white-space: nowrap !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    line-height: 1 !important;
    background: #e6f7f5 !important;
    border: 1px solid #99f6e4 !important;
    color: #0d9488 !important;
    border-radius: 6px !important;
    box-sizing: border-box !important;
    cursor: pointer !important;
    transition: background .15s !important;
}
.action-icons-inquiries .btn-reply:hover {
    background: #ccfbf1 !important;
    color: #0f766e !important;
}
.action-icons-inquiries .btn-reply svg {
    flex-shrink: 0 !important;
}
.action-icons-inquiries .btn-icon-action {
    width: 28px !important;
    height: 28px !important;
    border-radius: 6px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: none !important;
    background: transparent !important;
    cursor: pointer !important;
    transition: background .15s !important;
    padding: 0 !important;
    flex-shrink: 0 !important;
}
.action-icons-inquiries .btn-icon-action:hover {
    background: #e2e8f0 !important;
}
</style>

<div class="admin-table-wrap">
    <div style="padding:20px 24px 0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <div style="font-size:15px;font-weight:700;color:var(--text-dark,#222);display:flex;align-items:center;gap:8px;">
            <span>Incoming Website Inquiries</span>
            @if($unreadCount > 0)
                <span style="display:inline-block;padding:3px 9px;border-radius:12px;background:#0d9488;color:#fff;font-size:11px;font-weight:700;">
                    {{ $unreadCount }} Unread
                </span>
            @endif
        </div>
    </div>

    <div class="admin-table-toolbar">
        <form method="GET" action="{{ route('admin.inquiries.index') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <input type="text" name="search" class="search-input" placeholder="Search by name, email, phone, message..." value="{{ request('search') }}" style="width:280px">
            <select name="status" onchange="this.form.submit()" class="filter-select"
                    style="min-width:160px;height:42px;padding:9px 36px 9px 16px;font-size:13px;border-radius:30px;border:1.5px solid var(--border);background-color:#fff;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;box-sizing:border-box;">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Statuses</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread Only</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read Only</option>
                <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Responded Only</option>
            </select>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.inquiries.index') }}" style="font-size:12px;color:var(--text-muted);text-decoration:none;">Clear Filters</a>
            @endif
        </form>
    </div>

    <div class="inquiries-table-responsive">
        <table class="admin-table inquiries-table">
            <thead>
                <tr>
                    <th style="width:115px;white-space:nowrap;">Date <span class="sort-icon">⇅</span></th>
                    <th style="min-width:110px;">Sender Name <span class="sort-icon">⇅</span></th>
                    <th style="min-width:150px;">Email Address <span class="sort-icon">⇅</span></th>
                    <th style="width:110px;white-space:nowrap;">Phone Number <span class="sort-icon">⇅</span></th>
                    <th>Message Preview <span class="sort-icon">⇅</span></th>
                    <th style="width:105px;text-align:center;white-space:nowrap;">Status <span class="sort-icon">⇅</span></th>
                    <th style="min-width:170px;text-align:right;padding-right:16px;white-space:nowrap;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $inq)
                <tr id="inquiry-row-{{ $inq->id }}" class="{{ !$inq->is_read ? 'inquiry-unread-row' : '' }}">
                    <td style="font-size:12px;color:var(--text-muted);white-space:nowrap;">
                        {{ $inq->created_at->format('M d, Y') }}<br>
                        <span style="font-size:11px;">{{ $inq->created_at->format('h:i A') }}</span>
                    </td>
                    <td>
                        <div style="font-weight:700;">{{ $inq->full_name }}</div>
                    </td>
                    <td>
                        <button type="button"
                                onclick="openInquiryModal({{ $inq->id }}, @js($inq->full_name), @js($inq->email_address), @js($inq->formatted_phone), @js($inq->created_at->format('M d, Y h:i A')), @js($inq->message), {{ $inq->is_read ? 'true' : 'false' }}, @js($inq->responded_at?->format('M d, Y h:i A')), @js($inq->admin_notes), true)"
                                style="background:none;border:none;padding:0;color:#0d9488;cursor:pointer;font-size:13px;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:4px;"
                                title="Click to view & compose reply">
                            <span style="max-width:160px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;vertical-align:bottom;" title="{{ $inq->email_address }}">{{ $inq->email_address }}</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </button>
                    </td>
                    <td style="white-space:nowrap;">{{ $inq->formatted_phone }}</td>
                    <td style="max-width:180px;">
                        <div style="font-size:12.5px;color:#475569;line-height:1.4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $inq->message }}">
                            {{ $inq->message }}
                        </div>
                    </td>
                    <td style="text-align:center;white-space:nowrap;">
                        @if($inq->responded_at)
                            <span id="badge-{{ $inq->id }}" class="badge badge-responded"
                                  style="font-size:11px;padding:3px 10px;border-radius:12px;"
                                  title="Responded on {{ $inq->responded_at->format('M d, Y h:i A') }}">
                                ✓ Responded
                            </span>
                        @elseif($inq->is_read)
                            <span id="badge-{{ $inq->id }}" class="badge badge-read"
                                  style="font-size:11px;padding:3px 10px;border-radius:12px;">
                                Read
                            </span>
                        @else
                            <span id="badge-{{ $inq->id }}" class="badge badge-unread"
                                  style="font-size:11px;padding:3px 10px;border-radius:12px;">
                                Unread
                            </span>
                        @endif
                    </td>
                    <td style="white-space:nowrap;text-align:right;padding-right:16px;">
                        <div class="action-icons action-icons-inquiries">
                            {{-- Direct Reply Button --}}
                            <button type="button" class="btn-reply" title="Compose & Send Email Reply"
                                    onclick="openInquiryModal({{ $inq->id }}, @js($inq->full_name), @js($inq->email_address), @js($inq->formatted_phone), @js($inq->created_at->format('M d, Y h:i A')), @js($inq->message), {{ $inq->is_read ? 'true' : 'false' }}, @js($inq->responded_at?->format('M d, Y h:i A')), @js($inq->admin_notes), true)">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                <span>Reply</span>
                            </button>

                            {{-- View Full Message Modal --}}
                            <button type="button" class="btn-view btn-icon-action" title="View Details"
                                    onclick="openInquiryModal({{ $inq->id }}, @js($inq->full_name), @js($inq->email_address), @js($inq->formatted_phone), @js($inq->created_at->format('M d, Y h:i A')), @js($inq->message), {{ $inq->is_read ? 'true' : 'false' }}, @js($inq->responded_at?->format('M d, Y h:i A')), @js($inq->admin_notes), false)"
                                    style="color:#0d9488;">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>

                            {{-- Toggle Read / Unread --}}
                            <form method="POST" action="{{ route('admin.inquiries.toggleRead', $inq->id) }}" style="display:inline;margin:0;">
                                @csrf
                                <button type="submit" class="btn-icon-action" title="{{ $inq->is_read ? 'Mark as Unread' : 'Mark as Read' }}"
                                        style="color:#64748b;">
                                    @if($inq->is_read)
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    @endif
                                </button>
                            </form>

                            {{-- Delete Inquiry --}}
                            <form method="POST" action="{{ route('admin.inquiries.destroy', $inq->id) }}"
                                  onsubmit="return confirm('Delete this inquiry from {{ $inq->full_name }}?');" style="display:inline;margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon-action" title="Delete Inquiry"
                                        style="color:#e53e3e;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">
                        No inquiries found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="admin-table-footer">
        <div>
            <span>Result per page</span>
            <select style="border:1px solid var(--border);border-radius:6px;padding:4px 8px;margin-left:8px;font-size:13px">
                <option>15</option><option>25</option><option>50</option>
            </select>
        </div>
        <div>{{ $inquiries->links('vendor.pagination.simple-default') }}</div>
        <div>{{ $inquiries->firstItem() ?? 0 }}-{{ $inquiries->lastItem() ?? 0 }} of {{ $inquiries->total() }}</div>
    </div>
</div>

{{-- ── Inquiry View & In-App Reply Modal ── --}}
<div class="modal-overlay" id="inquiryModal">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="closeInquiryModal()">&times;</button>
        
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:40px;height:40px;border-radius:50%;background:#e6f7f5;color:#0d9488;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div>
                <h3 style="margin:0;font-size:18px;font-weight:700;color:#0f766e;">Website Inquiry &amp; Reply</h3>
                <span id="modalDate" style="font-size:12px;color:var(--text-muted);"></span>
            </div>
        </div>

        {{-- Sender Details Card --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;margin-bottom:14px;display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:13px;">
            <div>
                <span style="color:#64748b;display:block;font-size:11px;font-weight:600;text-transform:uppercase;">Sender</span>
                <strong id="modalName" style="color:#1e293b;"></strong>
            </div>
            <div>
                <span style="color:#64748b;display:block;font-size:11px;font-weight:600;text-transform:uppercase;">Phone</span>
                <span id="modalPhone" style="color:#1e293b;"></span>
            </div>
            <div style="grid-column:1/-1;">
                <span style="color:#64748b;display:block;font-size:11px;font-weight:600;text-transform:uppercase;">Email</span>
                <span id="modalEmail" style="color:#0d9488;font-weight:600;"></span>
            </div>
        </div>

        {{-- Original Message --}}
        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:11px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px;">Original Message</label>
            <div id="modalMessage" style="background:#fff;border:1px solid #cbd5e1;border-radius:8px;padding:12px 14px;font-size:13px;color:#1e293b;line-height:1.6;white-space:pre-wrap;max-height:140px;overflow-y:auto;"></div>
        </div>

        {{-- Previous Response Alert (if already replied) --}}
        <div id="modalPreviousResponseCard" style="display:none;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 14px;margin-bottom:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                <span style="font-size:12px;font-weight:700;color:#166534;display:flex;align-items:center;gap:6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Previous Reply Sent
                </span>
                <span id="modalRespondedAt" style="font-size:11px;color:#15803d;font-weight:600;"></span>
            </div>
            <div id="modalPreviousNotes" style="font-size:13px;color:#166534;white-space:pre-wrap;background:#fff;padding:8px 10px;border-radius:6px;border:1px solid #dcfce7;max-height:100px;overflow-y:auto;"></div>
            <div style="margin-top:8px;text-align:right;">
                <button type="button" id="btnToggleReplyAgain" onclick="toggleReplyForm(true)" style="background:none;border:none;color:#0d9488;font-size:12px;font-weight:700;cursor:pointer;text-decoration:underline;">
                    Send another reply &rarr;
                </button>
            </div>
        </div>

        {{-- Direct In-App Reply Form --}}
        <div id="replyFormSection" style="margin-top:14px;border-top:1px dashed #cbd5e1;padding-top:14px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#0f766e;text-transform:uppercase;letter-spacing:0.5px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Compose Email Reply
                </label>
                <span style="font-size:11px;color:#64748b;">Directly sends email to sender</span>
            </div>

            <form id="inquiryReplyForm" method="POST" action="" onsubmit="handleReplySubmit(event)">
                @csrf
                <div style="margin-bottom:10px;">
                    <label style="display:block;font-size:11px;color:#64748b;font-weight:600;margin-bottom:4px;">Email Subject</label>
                    <input type="text" name="subject" id="replySubject" required
                           style="width:100%;box-sizing:border-box;border:1px solid #cbd5e1;border-radius:6px;padding:8px 12px;font-size:13px;color:#1e293b;outline:none;"
                           placeholder="Re: Inquiry to POBA">
                </div>

                <div style="margin-bottom:10px;">
                    <label style="display:block;font-size:11px;color:#64748b;font-weight:600;margin-bottom:4px;">Reply Message *</label>
                    <textarea name="reply_message" id="replyMessageText" rows="4" required
                              style="width:100%;box-sizing:border-box;border:1px solid #cbd5e1;border-radius:6px;padding:10px 12px;font-size:13px;color:#1e293b;outline:none;resize:vertical;line-height:1.5;"
                              placeholder="Write your response here... It will be emailed to the sender with the original message quoted."></textarea>
                </div>

                <div style="background:#f0fdfa;border:1px solid #ccfbf1;border-radius:6px;padding:8px 12px;margin-bottom:16px;font-size:11px;color:#0f766e;line-height:1.4;">
                    ✉ The reply will be emailed from the official POBA contact address with your reply message and the sender's quoted inquiry.
                </div>

                <div style="display:flex;justify-content:flex-end;align-items:center;gap:10px;">
                    <button type="button" onclick="closeInquiryModal()" style="padding:8px 16px;background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                        Cancel
                    </button>
                    <button type="submit" id="btnSubmitReply" style="padding:9px 20px;background:#0d9488;color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:8px;box-shadow:0 2px 6px rgba(13,148,136,.3);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        <span id="btnSubmitReplyText">Send Email Reply</span>
                    </button>
                </div>
            </form>
        </div>

        <div id="modalCloseOnlySection" style="display:none;justify-content:flex-end;align-items:center;margin-top:14px;">
            <button type="button" onclick="closeInquiryModal()" style="padding:8px 18px;background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentInquiryId = null;

function openInquiryModal(id, name, email, phone, date, message, isRead, respondedAt, adminNotes, focusReply = false) {
    currentInquiryId = id;
    document.getElementById('modalName').textContent = name;
    document.getElementById('modalEmail').textContent = email;
    document.getElementById('modalPhone').textContent = phone;
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalMessage').textContent = message;

    // Set reply form action & default subject
    const form = document.getElementById('inquiryReplyForm');
    form.action = '/admin/inquiries/' + id + '/reply';
    document.getElementById('replySubject').value = 'Re: Inquiry to POBA from ' + name;
    document.getElementById('replyMessageText').value = '';

    // Handle previous response display
    const prevCard = document.getElementById('modalPreviousResponseCard');
    const replyFormSection = document.getElementById('replyFormSection');
    const closeOnlySection = document.getElementById('modalCloseOnlySection');

    if (respondedAt) {
        prevCard.style.display = 'block';
        document.getElementById('modalRespondedAt').textContent = respondedAt;
        document.getElementById('modalPreviousNotes').textContent = adminNotes || '(No reply notes saved)';
        if (focusReply) {
            replyFormSection.style.display = 'block';
            closeOnlySection.style.display = 'none';
        } else {
            replyFormSection.style.display = 'none';
            closeOnlySection.style.display = 'flex';
        }
    } else {
        prevCard.style.display = 'none';
        replyFormSection.style.display = 'block';
        closeOnlySection.style.display = 'none';
    }

    document.getElementById('inquiryModal').classList.add('open');

    if (focusReply) {
        setTimeout(() => {
            const ta = document.getElementById('replyMessageText');
            if (ta) ta.focus();
        }, 150);
    }

    // Mark as read in background if unread
    if (!isRead) {
        fetch('/admin/inquiries/' + id, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            const row = document.getElementById('inquiry-row-' + id);
            const badge = document.getElementById('badge-' + id);
            if (row) row.classList.remove('inquiry-unread-row');
            if (badge && !respondedAt) {
                badge.className = 'badge badge-read';
                badge.textContent = 'Read';
            }
        })
        .catch(() => {});
    }
}

function toggleReplyForm(show) {
    document.getElementById('replyFormSection').style.display = show ? 'block' : 'none';
    document.getElementById('modalCloseOnlySection').style.display = show ? 'none' : 'flex';
    if (show) {
        setTimeout(() => {
            document.getElementById('replyMessageText').focus();
        }, 100);
    }
}

function handleReplySubmit(e) {
    const btn = document.getElementById('btnSubmitReply');
    const btnText = document.getElementById('btnSubmitReplyText');
    btn.disabled = true;
    btn.style.opacity = '0.7';
    btnText.textContent = 'Sending Email...';
}

function closeInquiryModal() {
    document.getElementById('inquiryModal').classList.remove('open');
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeInquiryModal();
});
document.getElementById('inquiryModal').addEventListener('click', function(e) {
    if (e.target === this) closeInquiryModal();
});
</script>
@endpush
@endsection
