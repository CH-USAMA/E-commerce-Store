<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'message',
        'url',
        'status',
        'recipients_count',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];
}
