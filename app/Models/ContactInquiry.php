<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email_address',
        'phone_ext',
        'phone',
        'message',
        'is_read',
        'responded_at',
        'admin_notes',
    ];

    protected $casts = [
        'is_read'      => 'boolean',
        'responded_at' => 'datetime',
    ];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFormattedPhoneAttribute(): string
    {
        if (empty($this->phone)) {
            return '—';
        }
        return ($this->phone_ext ? $this->phone_ext . ' ' : '') . $this->phone;
    }
}
