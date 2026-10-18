<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $fillable = ['title', 'description', 'type', 'parent_id'];

    public function members()
    {
        return $this->hasMany(CommitteeMember::class)->orderBy('sort_order');
    }

    public function parent()
    {
        return $this->belongsTo(Committee::class, 'parent_id');
    }

    public function subCommittees()
    {
        return $this->hasMany(Committee::class, 'parent_id')->orderBy('id');
    }

    public function isMain(): bool
    {
        return is_null($this->parent_id);
    }
}
