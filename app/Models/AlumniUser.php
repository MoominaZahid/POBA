<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AlumniUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'alumni_users';
    protected $guard = 'alumni';

    protected $fillable = [
        'full_name', 'email', 'password', 'entry', 'ccp_no', 'house',
        'education', 'field_of_study', 'field_of_work', 'current_city',
        'current_country', 'current_designation', 'current_organization',
        'phone_number', 'achievements', 'profile_photo', 'cnic_file',
        'consent_sharing', 'agree_terms', 'privacy_settings', 'status',
        'is_active', 'is_star_alumni', 'star_description', 'featured_text',
        'class_year', 'rejection_reason', 'deactivation_reason',
    ];

    protected $casts = [
        'privacy_settings' => 'array',
        'consent_sharing'  => 'boolean',
        'agree_terms'      => 'boolean',
        'is_active'        => 'boolean',
        'is_star_alumni'   => 'boolean',
        'password'         => 'hashed',   // Laravel 10+ auto-hashing
    ];

    protected $hidden = ['password', 'remember_token'];

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_participants')
                    ->withPivot('status')->withTimestamps();
    }

    /**
     * Check if a given phone number (with optional country code) already exists.
     *
     * @param string $phoneNumber
     * @param string|null $phoneCode
     * @param int|null $ignoreId
     * @return bool
     */
    public static function phoneAlreadyExists(string $phoneNumber, ?string $phoneCode = null, ?int $ignoreId = null): bool
    {
        $cleanDigits = preg_replace('/\D/', '', $phoneNumber);
        $significant = ltrim($cleanDigits, '0');

        if (empty($significant)) {
            return false;
        }

        $codeDigits = $phoneCode ? preg_replace('/\D/', '', $phoneCode) : '';
        $fullPhoneWithPlus = ($codeDigits ? '+' . $codeDigits : '') . $significant;
        $fullDigits = $codeDigits . $significant;

        $directMatches = array_filter([
            $fullPhoneWithPlus,
            $fullDigits,
            $cleanDigits,
            $significant,
            '0' . $significant,
            $phoneCode ? $phoneCode . $cleanDigits : null,
            $phoneCode ? $phoneCode . ' ' . $cleanDigits : null,
            $phoneCode ? $phoneCode . $significant : null,
            $phoneCode ? $phoneCode . ' ' . $significant : null,
        ]);

        $query = static::whereIn('phone_number', $directMatches);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            return true;
        }

        if (strlen($significant) >= 7) {
            $suffix = substr($significant, -7);
            $candidateQuery = static::query();
            if ($ignoreId) {
                $candidateQuery->where('id', '!=', $ignoreId);
            }

            $candidateQuery->where(function ($q) use ($suffix) {
                $q->where('phone_number', 'like', "%{$suffix}%")
                  ->orWhereRaw("REPLACE(REPLACE(REPLACE(phone_number, ' ', ''), '-', ''), '+', '') LIKE ?", ["%{$suffix}%"]);
            });

            foreach ($candidateQuery->get(['id', 'phone_number']) as $candidate) {
                $candidateDigits = preg_replace('/\D/', '', $candidate->phone_number);
                if (empty($candidateDigits)) {
                    continue;
                }

                if (
                    (!empty($fullDigits) && $candidateDigits === $fullDigits) ||
                    $candidateDigits === $cleanDigits ||
                    $candidateDigits === $significant ||
                    (strlen($candidateDigits) >= 7 && str_ends_with($candidateDigits, $significant)) ||
                    (strlen($significant) >= 7 && str_ends_with($significant, $candidateDigits))
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}