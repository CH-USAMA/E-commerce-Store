<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ValidatesImageUploads
{
    /**
     * Accepted upload formats.
     *
     * Deliberately NOT combined with Laravel's `image` rule. As of Laravel 12 the
     * `image` rule resolves to jpg,jpeg,png,gif,bmp,webp and no longer implies svg
     * (that needs an explicit `image:allow_svg`), so the old
     * `image|mimes:jpeg,png,jpg,gif,svg` rejected BOTH webp and svg — while the
     * error message it printed advertised svg as valid. `mimes` alone checks the
     * file's real guessed type, not the client-supplied name.
     *
     * SVG stays out entirely: it can carry embedded JavaScript.
     */
    protected const IMAGE_MIMES = 'jpg,jpeg,png,gif,webp,avif';

    /**
     * Max upload size in KB.
     *
     * Must stay BELOW upload_max_filesize in public/.user.ini (16M). If PHP's
     * ceiling is the lower of the two it aborts the request before Laravel runs,
     * and the admin gets a discarded POST / 419 Page Expired instead of a message.
     */
    protected const IMAGE_MAX_KB = 8192;

    protected function imageRules(bool $required = false, ?int $maxKb = null): string
    {
        return ($required ? 'required' : 'nullable')
            .'|mimes:'.self::IMAGE_MIMES
            .'|max:'.($maxKb ?? self::IMAGE_MAX_KB);
    }

    /**
     * Plain-language messages, so a rejection reads as guidance instead of a list
     * of MIME tokens. `uploaded` fires when PHP rejected the file outright.
     */
    protected function imageMessages(string $field = 'image', ?int $maxKb = null): array
    {
        $kb = $maxKb ?? self::IMAGE_MAX_KB;

        // Report KB below a megabyte, otherwise rounding renders e.g. 512KB as "0MB".
        $limit = $kb >= 1024
            ? rtrim(rtrim(number_format($kb / 1024, 1, '.', ''), '0'), '.').'MB'
            : $kb.'KB';

        return [
            $field.'.mimes' => 'Please upload a JPG, PNG, GIF, WebP or AVIF image.',
            $field.'.max' => "Image must be {$limit} or smaller.",
            $field.'.uploaded' => "The image was too large for the server to accept. Please use a file under {$limit}.",
        ];
    }

    /**
     * Store an upload on the 'public' disk, returning its path relative to public/.
     *
     * The disk is configured with 'throw' => false (config/filesystems.php), so a
     * failed write returns false instead of raising. Unchecked, the record saves
     * with an empty image and still reports success — which is how uploads went
     * missing with no error.
     */
    protected function storeImage(Request $request, string $field, string $directory): string
    {
        $file = $request->file($field);

        // Prefer the guessed extension; fall back to the client's if fileinfo has
        // no mapping. A time()-based name would collide for two uploads in the
        // same second, so the name is a uuid.
        $extension = $file->extension() ?: $file->getClientOriginalExtension();

        $path = $file->storeAs($directory, Str::uuid().'.'.$extension, 'public');

        abort_if(
            $path === false,
            500,
            "Could not write the upload to public/{$directory}. Check filesystem permissions."
        );

        return $path;
    }
}
