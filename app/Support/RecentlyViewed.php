<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * "Recently viewed products", kept in the session.
 *
 * Session rather than a `users` column on purpose: it works for guests as well as
 * logged-in customers, needs no migration, and adds no database write to a page that
 * is otherwise read-only. The only thing it gives up is cross-device continuity — add
 * a `users.recently_viewed` JSON column later if that turns out to matter.
 *
 * Only IDs are stored. Serialising the models would freeze prices, names and images at
 * view time and re-display products that have since been edited or removed.
 */
class RecentlyViewed
{
    private const SESSION_KEY = 'recently_viewed';

    /** How many to remember. Kept small — this is a nudge, not a browsing history. */
    private const LIMIT = 8;

    public static function record(Product $product): void
    {
        $ids = session()->get(self::SESSION_KEY, []);

        // Move to the front on a repeat visit rather than duplicating it.
        $ids = array_values(array_diff($ids, [$product->id]));
        array_unshift($ids, $product->id);

        session()->put(self::SESSION_KEY, array_slice($ids, 0, self::LIMIT));
    }

    /**
     * Products to display, newest first.
     *
     * `$excludeId` keeps the product currently being viewed out of its own strip.
     * Inactive and deleted products fall away here, so the list self-heals without
     * needing to rewrite the session.
     */
    public static function products(?int $excludeId = null): Collection
    {
        $ids = array_values(array_diff(
            session()->get(self::SESSION_KEY, []),
            array_filter([$excludeId])
        ));

        if (empty($ids)) {
            return collect();
        }

        $products = Product::active()
            ->with('category', 'subcategory')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        // whereIn() returns database order; re-sort to the session's most-recent-first.
        return collect($ids)
            ->map(fn ($id) => $products->get($id))
            ->filter()
            ->values();
    }

    public static function forget(): void
    {
        session()->forget(self::SESSION_KEY);
    }
}
