<?php

namespace App\Models;

use App\Models\Concerns\FlushesContentCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use FlushesContentCache;

    /** Homepage category slider — see HomeController::index(). */
    protected static array $contentCacheKeys = ['categories_top'];

    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->uuid)) {
                $category->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = ['name', 'slug', 'parent_id', 'image', 'sort_order'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subProducts()
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Sub-categories, already in display order.
     *
     * Ordering lives on the relation rather than at each call site so that every
     * `with('children')` — the homepage, the /products sidebar, the admin product
     * dropdowns — gets the admin's chosen sequence without having to remember.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->ordered();
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSubCategories($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Display order, lowest first.
     *
     * `id` is the tie-breaker so the order is always deterministic — two categories
     * sharing a sort_order (legacy rows can) would otherwise come back in whatever
     * order the database chose, and could appear to reshuffle between requests.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Next position at the end of a sibling group, for newly created categories.
     *
     * The order is per-parent, so "the end" means the end of that parent's children
     * (or the end of the top level when $parentId is null) — not the end of the table.
     */
    public static function nextSortOrder($parentId = null): int
    {
        return (int) static::query()
            ->where('parent_id', $parentId)
            ->max('sort_order') + 1;
    }
}
