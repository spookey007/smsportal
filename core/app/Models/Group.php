<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function scopeEmail($query)
    {
        $query->where('type', 1);
    }
    public function scopeSms($query)
    {
        $query->where('type', 2);
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function scopeBanned($query)
    {
        return $query->where('status', 0);
    }
    public function contact()
    {
        return $this->belongsToMany(Contact::class, 'group_contacts', 'group_id');
    }
}
