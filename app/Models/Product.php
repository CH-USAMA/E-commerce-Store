<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->uuid)) {
                $product->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $casts = [
        'has_variants' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'subcategory_id',
        'brand_id',
        'sku',
        'price',
        'has_variants',
        'vat_rate',
        'is_featured',
        'is_top_selling',
        'is_new_arrival',
        'image',
        // Was missing, so `status` silently ignored every write and stayed at the column
        // default 'active' forever — the documented active/inactive switch never worked.
        'status',
    ];

    /**
     * Only products that should be publicly visible.
     *
     * `status` existed and was documented as controlling storefront visibility, but no
     * public query ever filtered on it — so deactivating a product hid it from nowhere.
     * Apply this to every customer-facing query.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStoreStock::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Size variants, already in display order.
     *
     * Ordering lives on the relation so every `with('variants')` gets the admin's
     * chosen sequence — sizes sort badly alphabetically (50MM after 150MM).
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->ordered();
    }

    /** The sizes a customer may actually choose. */
    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->active()->ordered();
    }

    /**
     * Does this product offer a choice of sizes right now?
     *
     * Both halves matter: the admin's switch AND at least one live size. A product
     * flagged `has_variants` whose sizes were all deactivated must fall back to
     * behaving like a simple product, or the storefront would render an empty
     * picker and nothing could be bought.
     */
    public function offersVariants(): bool
    {
        return $this->has_variants && $this->activeVariants->isNotEmpty();
    }

    /**
     * Cheapest live size, or the product's own price when there are none.
     *
     * Listing cards show this as "From R x" — with sizes there is no single price
     * to print, and the lowest is the honest one to lead with.
     */
    public function getDisplayPriceAttribute(): float
    {
        if (! $this->offersVariants()) {
            return (float) $this->price;
        }

        return (float) $this->activeVariants->min('price');
    }

    /** True when the live sizes do not all cost the same, so "From" is warranted. */
    public function hasPriceRange(): bool
    {
        if (! $this->offersVariants()) {
            return false;
        }

        $prices = $this->activeVariants->pluck('price');

        return $prices->min() != $prices->max();
    }
}
