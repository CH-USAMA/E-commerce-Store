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
}
