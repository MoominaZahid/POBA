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

    public function setEntryBatchesAttribute($value)
    {
        if (is_array($value)) {
            $batches = array_values(array_unique(array_map('intval', $value)));
            sort($batches, SORT_NUMERIC);
            $this->attributes['entry_batches'] = json_encode($batches);
        } elseif (is_string($value) && ($decoded = json_decode($value, true)) && is_array($decoded)) {
            $batches = array_values(array_unique(array_map('intval', $decoded)));
            sort($batches, SORT_NUMERIC);
            $this->attributes['entry_batches'] = json_encode($batches);
        } else {
            $this->attributes['entry_batches'] = $value;
        }
    }

    public function getEntryBatchesAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        $batches = is_array($value) ? $value : json_decode($value, true);
        if (is_array($batches)) {
            $batches = array_values(array_unique(array_map('intval', $batches)));
            sort($batches, SORT_NUMERIC);
            return $batches;
        }
        return $batches;
    }

    public function getFormattedGalleryUrlAttribute()
    {
        if ($this->gallery_link !== null && trim($this->gallery_link) !== '') {
            $link = trim($this->gallery_link);

            // If it contains /gallery/{id_or_slug} or matches route structure
            if (preg_match('~/gallery/([^/?#]+)~i', $link, $matches)) {
                return route('gallery.show', $matches[1]);
            }

            // If it's just a folder ID like "2"
            if (is_numeric($link)) {
                return route('gallery.show', $link);
            }

            // If it matches an existing gallery folder slug or folder_name directly (e.g. "test")
            if ($folder = GalleryFolder::where('slug', $link)->orWhere('folder_name', $link)->first()) {
                return route('gallery.show', $folder->slug ?: $folder->id);
            }

            // If it starts with http:// or https://
            if (preg_match('#^https?://#i', $link)) {
                return $link;
            }

            // If it starts with www.
            if (preg_match('#^www\.#i', $link)) {
                return 'https://' . $link;
            }

            // Relative path
            return url('/' . ltrim($link, '/'));
        }

        return null;
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
