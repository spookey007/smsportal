<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailHistory extends Model
{
    use HasFactory, Searchable;

    protected $casts = [
        'schedule' => 'datetime'
    ];

    public function smtp()
    {
        return $this->belongsTo(Smtp::class, 'email_host_id');
    }
}
