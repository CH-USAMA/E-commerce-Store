<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Generates a compressed WebP thumbnail from an already-stored image.
 *
 * Written against PHP's bundled GD rather than pulling in intervention/image.
 * GD with WebP support is present on both local and production (checked
 * 2026-08-24; live also has Imagick), so a package would add a composer
 * dependency — and therefore a `composer install` step on every deploy — to do
 * what four native calls already do. If richer processing is ever needed, this is
 * the one place to swap.
 *
 * The specials flyers are the motivating case: they are ~2245x1587 PNGs of 4-5MB
 * each, rendered into a card only 512px tall. Shipping the flyer to the grid meant
 * ~15MB to view one page.
 *
 * Every failure path returns null rather than throwing. A missing thumbnail is a
 * cosmetic problem the caller handles by falling back to the full image; it must
 * never cost the admin their upload.
 */
class ImageThumbnailer
{
    /** Longest edge of the generated thumbnail, in pixels. */
    public const MAX_EDGE = 1400;

    /** WebP quality, 0-100. */
    public const QUALITY = 82;

    /**
     * Build a WebP thumbnail next to the source and return its path relative to
     * public/, or null if one could not be produced.
     *
     * @param  string  $relativePath  path as returned by ValidatesImageUploads::storeImage()
     */
    public static function generate(string $relativePath, string $directory): ?string
    {
        if (! function_exists('imagewebp')) {
            Log::warning('ImageThumbnailer: GD WebP support unavailable, skipping thumbnail.');

            return null;
        }

        $source = image_relative_path($relativePath);

        if ($source === null) {
            Log::warning('ImageThumbnailer: source not found.', ['path' => $relativePath]);

            return null;
        }

        $absolute = public_path($source);

        $image = self::read($absolute);

        if ($image === null) {
            return null;
        }

        try {
            $width = imagesx($image);
            $height = imagesy($image);
            $longest = max($width, $height);

            // Never upscale — a small upload is already its own thumbnail, and
            // enlarging it would cost bytes to look worse.
            $scale = $longest > self::MAX_EDGE ? self::MAX_EDGE / $longest : 1.0;

            $targetWidth = max(1, (int) round($width * $scale));
            $targetHeight = max(1, (int) round($height * $scale));

            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

            // Flyers are often PNGs with transparency; without this the alpha
            // channel is flattened to black.
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);

            imagecopyresampled($canvas, $image, 0, 0, 0, 0,
                $targetWidth, $targetHeight, $width, $height);

            ob_start();
            $ok = imagewebp($canvas, null, self::QUALITY);
            $binary = ob_get_clean();

            imagedestroy($canvas);

            if (! $ok || $binary === false || $binary === '') {
                Log::warning('ImageThumbnailer: WebP encode produced nothing.', ['path' => $relativePath]);

                return null;
            }

            $thumbPath = rtrim($directory, '/').'/'.Str::uuid().'.webp';

            // The 'public' disk sets 'throw' => false, so a failed write returns
            // false rather than raising — see MEMORY.md Rule 3.
            if (Storage::disk('public')->put($thumbPath, $binary) === false) {
                Log::warning('ImageThumbnailer: could not write thumbnail.', ['path' => $thumbPath]);

                return null;
            }

            return $thumbPath;
        } catch (\Throwable $e) {
            Log::warning('ImageThumbnailer: '.$e->getMessage(), ['path' => $relativePath]);

            return null;
        } finally {
            imagedestroy($image);
        }
    }

    /**
     * Decode a file into a GD resource, picking the reader from its real type.
     *
     * getimagesize() reports the actual format, so a mislabelled extension cannot
     * send a PNG to the JPEG reader.
     */
    private static function read(string $absolute): ?\GdImage
    {
        $info = @getimagesize($absolute);

        if ($info === false) {
            Log::warning('ImageThumbnailer: unreadable image.', ['path' => $absolute]);

            return null;
        }

        $reader = match ($info[2]) {
            IMAGETYPE_JPEG => 'imagecreatefromjpeg',
            IMAGETYPE_PNG => 'imagecreatefrompng',
            IMAGETYPE_WEBP => 'imagecreatefromwebp',
            IMAGETYPE_GIF => 'imagecreatefromgif',
            IMAGETYPE_AVIF => 'imagecreatefromavif',
            default => null,
        };

        if ($reader === null || ! function_exists($reader)) {
            Log::warning('ImageThumbnailer: no GD reader for this format.', [
                'path' => $absolute,
                'type' => $info[2],
            ]);

            return null;
        }

        $image = @$reader($absolute);

        return $image instanceof \GdImage ? $image : null;
    }
}
