<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One purchasable size of a product — "1.2m", "100MM".
 *
 * Variants carry a price and an optional code, nothing else. They are not
 * stock-bearing; see the create_product_variants_table migration for why.
 */
class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'label', 'sku', 'price', 'is_active', 'sort_order'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Display order, lowest first.
     *
     * Explicit rather than alphabetical because sizes sort badly as text — the
     * live catalog's "Paint Brush 50MM / 100MM / 150MM" would list 100, 150, 50.
     * `id` breaks ties so the order never appears to reshuffle between requests.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** Only sizes that should be offered to customers. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
