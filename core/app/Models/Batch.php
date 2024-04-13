<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    public function scopeEmail($query)
    {
        return $query->where('type', 1);
    }
    public function scopeSms($query)
    {
        return $query->where('type', 2);
    }
}
