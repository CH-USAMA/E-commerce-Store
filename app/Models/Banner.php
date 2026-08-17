<?php

namespace App\Models;

use App\Models\Concerns\FlushesContentCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    use FlushesContentCache;

    /** Homepage hero slider — see HomeController::index(). */
    protected static array $contentCacheKeys = ['banners'];

    protected $fillable = ['title', 'subtitle', 'description', 'image', 'image_mobile', 'link', 'sort_order'];

    /**
     * Display order for the hero slider, lowest first.
     *
     * `id` is the tie-breaker so the order is always deterministic — two banners
     * sharing a sort_order would otherwise come back in whatever order the database
     * chose, and could appear to reshuffle between requests.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** Next position at the end of the list, for newly created banners. */
    public static function nextSortOrder(): int
    {
        return (int) static::max('sort_order') + 1;
    }
}
