<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        // Snapshot of the size at purchase time. Deliberately stored alongside
        // variant_id rather than joined for: a discontinued size can be deleted
        // from the catalog without rewriting what old orders say was bought,
        // exactly as `price` is a copy rather than a live lookup.
        'variant_label',
        'quantity',
        'price',
        'vat',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * What was bought, including the size — "Lintel (1.2m)".
     *
     * Reads the stored label, not the relation, so it still reads correctly after
     * the variant row is gone.
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->product->name ?? 'Product';

        return $this->variant_label ? $name.' ('.$this->variant_label.')' : $name;
    }
}
