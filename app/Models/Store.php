<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'address',
        'province',
        'lat',
        'lng',
        'contact_details',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStoreStock::class);
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'store_user');
    }
}
