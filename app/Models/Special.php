<?php

namespace App\Models;

use App\Models\Concerns\FlushesContentCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Special extends Model
{
    use HasFactory;
    use FlushesContentCache;

    /** Seasonal specials grid — see HomeController::specials(). */
    protected static array $contentCacheKeys = ['specials'];

    protected $fillable = ['title', 'subtitle', 'image', 'image_full', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Display order for the specials grid, lowest first.
     *
     * `id` is the tie-breaker so the order is always deterministic — two rows
     * sharing a sort_order would otherwise come back in whatever order the
     * database chose, and could appear to reshuffle between requests.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** Only the specials that should be visible on the storefront. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Next position at the end of the list, for newly created specials. */
    public static function nextSortOrder(): int
    {
        return (int) static::max('sort_order') + 1;
    }

    /**
     * The image the lightbox should open.
     *
     * Always the full-resolution flyer — the grid thumbnail exists only to keep
     * the listing light, and would be unreadable blown up to full screen.
     */
    public function getLightboxImageAttribute(): string
    {
        return $this->image_full;
    }

    /**
     * The image the grid should render.
     *
     * Falls back to the full flyer when no thumbnail was generated (GD missing, a
     * format it could not decode, a failed write). Heavier than intended, but the
     * card still shows the right picture instead of a placeholder.
     */
    public function getGridImageAttribute(): string
    {
        return $this->image ?: $this->image_full;
    }
}
