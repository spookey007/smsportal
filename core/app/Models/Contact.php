<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\FileExport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use FileExport, Searchable,HasFactory;

    public function groupContact()
    {
        return $this->belongsToMany(Group::class, 'group_contacts', 'contact_id');
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }
}
