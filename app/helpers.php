<?php

use Illuminate\Support\Str;

if (! function_exists('image_relative_path')) {
    /**
     * Resolve a stored image path to one that actually exists, relative to public/.
     *
     * The app has three historical storage schemes and rows of every kind are live:
     *   - `images/foo.webp`          legacy seeded assets in public/images/
     *   - `uploads/banners/foo.jpg`  older banners written with ->move()
     *   - `products/foo.webp`        uploads via the 'public' disk, whose root is
     *                               overridden to public_path('') in config/filesystems.php
     *
     * Returns null when the file cannot be found anywhere.
     */
    function image_relative_path(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $path = ltrim($path, '/');

        if (is_file(public_path($path))) {
            return $path;
        }

        // Safety net for any row written while the 'public' disk still pointed at
        // Laravel's stock location. Reachable through the public/storage symlink.
        if (is_file(storage_path('app/public/'.$path))) {
            return 'storage/'.$path;
        }

        return null;
    }
}

if (! function_exists('image_url')) {
    /**
     * Browser-facing URL for a stored image path, falling back to the placeholder.
     *
     * This is the only sanctioned way to render a stored image path in a view — it
     * replaces the ad-hoc file_exists()/Str::contains()/'images/' prefixing that
     * previously differed in every template.
     */
    function image_url(?string $path, string $fallback = 'images/placeholder.webp'): string
    {
        if (filled($path) && Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        $resolved = image_relative_path($path) ?? $fallback;

        // Filenames can contain '+' and spaces; encode each segment so the browser
        // does not read '+' as a space. Separators are left intact.
        return asset(implode('/', array_map('rawurlencode', explode('/', $resolved))));
    }
}

if (! function_exists('image_path')) {
    /**
     * Absolute filesystem path for a stored image.
     *
     * For consumers that cannot fetch a URL — DomPDF will not reliably resolve an
     * http(s) src while rendering an invoice.
     */
    function image_path(?string $path, ?string $fallback = 'images/placeholder.webp'): ?string
    {
        $resolved = image_relative_path($path);

        if ($resolved !== null) {
            return Str::startsWith($resolved, 'storage/')
                ? storage_path('app/public/'.Str::after($resolved, 'storage/'))
                : public_path($resolved);
        }

        if ($fallback && is_file(public_path($fallback))) {
            return public_path($fallback);
        }

        return null;
    }
}
