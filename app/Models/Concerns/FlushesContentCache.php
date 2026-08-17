<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

/**
 * Invalidates the `Cache::remember(..., 3600, ...)` keys that `HomeController`
 * uses for public content.
 *
 * Those caches previously had no invalidation at all, so saving a banner (or store,
 * brand, category, team member, gallery item, blog post) left the site serving a
 * stale copy for up to an hour. It presented as "I uploaded it and nothing changed" —
 * e.g. a 5th banner sitting in the database while the homepage rendered 4 slides.
 *
 * Invalidation lives on the model rather than in the controllers so that EVERY write
 * path is covered — admin CRUD, tinker, seeders and the CSV import alike.
 *
 * Implementing models declare:
 *
 *     protected static array $contentCacheKeys = ['banners'];
 *
 * or override contentCacheKeys() when a key depends on the row (see BlogPost).
 */
trait FlushesContentCache
{
    public static function bootFlushesContentCache(): void
    {
        // `saved` covers both created and updated.
        static::saved(fn ($model) => $model->flushContentCache());
        static::deleted(fn ($model) => $model->flushContentCache());
    }

    public function flushContentCache(): void
    {
        foreach ($this->contentCacheKeys() as $key) {
            Cache::forget($key);
        }
    }

    /**
     * @return array<int, string>
     */
    protected function contentCacheKeys(): array
    {
        return static::$contentCacheKeys ?? [];
    }
}
