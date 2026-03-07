<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_name',
        'type',
        'address_line_1',
        'address_line_2',
        'city',
        'province',
        'postal_code',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
