<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'location', 'start_date', 'end_date',
        'start_time', 'end_time', 'registration_required', 'focal_person_name',
        'focal_person_number', 'entry_batches', 'gallery_link', 'logo', 'is_upcoming',
    ];

    protected $casts = [
        'entry_batches'         => 'array',
        'registration_required' => 'boolean',
        'is_upcoming'           => 'boolean',
    ];

    public function getFormattedGalleryUrlAttribute()
    {
        if (!empty($this->gallery_link)) {
            $link = trim($this->gallery_link);

            // If it's just a folder ID like "2"
            if (is_numeric($link)) {
                return route('gallery.show', $link);
            }

            // If it contains /gallery/{id} or matches route structure
            if (preg_match('#/gallery/(\d+)#i', $link, $matches)) {
                return route('gallery.show', $matches[1]);
            }

            // If it starts with http:// or https://
            if (preg_match('#^https?://#i', $link)) {
                return $link;
            }

            // Relative path like /gallery/2 or gallery/2
            return url('/' . ltrim($link, '/'));
        }

        // Default to all gallery folders index page if no specific gallery_link attached
        return route('gallery.index');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function alumniUsers()
    {
        return $this->belongsToMany(AlumniUser::class, 'event_participants')
                    ->withPivot('status')->withTimestamps();
    }
}
