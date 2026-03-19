<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'store_id',
        'total',
        'vat',
        'status',
        'payment_method',
        'order_type',
        'notes',
        'lat',
        'lng',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_city',
        'customer_postal_code',
        'payment_screenshot',
        'payment_confirmed_at',
    ];

    protected $casts = [
        'payment_confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getManifestAttribute()
    {
        return $this->items->loadMissing('product')->map(function($item) {
            return ($item->product->name ?? 'Unknown') . ' (x' . $item->quantity . ')';
        })->implode(', ');
    }
}
