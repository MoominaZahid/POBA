<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryFolder extends Model
{
    protected $fillable = ['folder_name', 'description', 'type', 'class_year', 'slug', 'banner_image'];

    public function images()
    {
        return $this->hasMany(GalleryImage::class)->orderBy('sort_order');
    }

    public function getCardImageUrlAttribute(): string
    {
        if (!empty($this->banner_image)) {
            return media_url($this->banner_image);
        }

        $first = $this->images->first();
        if ($first) {
            return media_url($first->image_path);
        }

        return 'https://placehold.co/400x220/e8f5f5/1a7a7a?text=Gallery';
    }
}
