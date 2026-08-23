<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use ReflectionClass;
use Tests\TestCase;

/**
 * Regression guard for the /products outage of 2026-08-23.
 *
 * The 'public' filesystem disk is rooted at public_path(''), so an image uploaded
 * through ValidatesImageUploads::storeImage($request, $field, 'products') is written
 * to public/products/ -- inside the document root, next to index.php.
 *
 * Apache/LiteSpeed then treats /products as a real directory: mod_dir 301s it to
 * /products/, Options -Indexes refuses the listing, and the visitor gets a bare 403.
 * The front controller never runs, because its RewriteCond requires the request NOT
 * to resolve to a directory. Nothing is logged, and the page that broke has no visible
 * connection to the upload that broke it.
 *
 * public/.htaccess carries a rule naming the segments that must reach Laravel anyway.
 * These tests keep that list honest: add a new upload directory whose name matches a
 * route, and the build fails here instead of the page failing in production.
 */
class PublicUploadFolderCollisionTest extends TestCase
{
    public function test_the_public_disk_still_writes_into_the_document_root(): void
    {
        // The whole hazard rests on this. If the disk is ever re-rooted at
        // storage_path('app/public') the collision cannot happen any more and this file
        // can go -- but that should be a decision someone makes, not a surprise from a
        // red build.
        $this->assertSame(
            public_path(''),
            config('filesystems.disks.public.root'),
            "The 'public' disk no longer writes into the document root. If that was "
            .'deliberate, uploads can no longer shadow a route and this test file is obsolete.'
        );
    }

    public function test_every_upload_directory_that_shadows_a_route_is_guarded(): void
    {
        $collisions = array_intersect($this->uploadSegments(), $this->routeSegments());
        $unguarded = array_values(array_diff($collisions, $this->guardedSegments()));
        sort($unguarded);

        $this->assertSame([], $unguarded, $this->explainUnguarded($unguarded));
    }

    public function test_the_guard_runs_before_the_directory_checks_it_exists_to_defeat(): void
    {
        $htaccess = $this->htaccess();

        $guard = $this->guardOffset($htaccess);
        $trailingSlash = strpos($htaccess, '# Redirect Trailing Slashes If Not A Directory');
        $frontController = strpos($htaccess, '# Send Requests To Front Controller');

        $this->assertNotFalse($trailingSlash, 'The trailing-slash block vanished from public/.htaccess.');
        $this->assertNotFalse($frontController, 'The front-controller block vanished from public/.htaccess.');

        // Both later blocks are gated on RewriteCond %{REQUEST_FILENAME} !-d, which is
        // exactly the condition an uploaded folder fails. The guard is only load-bearing
        // while it runs first and carries [L].
        $this->assertLessThan(
            $trailingSlash,
            $guard,
            'The upload-folder guard must precede the trailing-slash redirect, or mod_dir '
            .'reaches the request first and 301s it into a 403.'
        );
        $this->assertLessThan($frontController, $guard);
        $this->assertMatchesRegularExpression(
            '#RewriteRule\s+\^\([a-z0-9|_-]+\)/\?\$\s+index\.php\s+\[L\]#i',
            $htaccess,
            'The guard lost its [L] flag; without it processing continues into the rules it overrides.'
        );
    }

    public function test_directory_listings_stay_disabled(): void
    {
        // -Indexes is what turns a shadowed route into a 403 rather than a public listing
        // of every image ever uploaded. The guard fixes the routing; this keeps the
        // fallback from becoming a disclosure.
        $this->assertStringContainsString(
            'Options -MultiViews -Indexes',
            $this->htaccess(),
            'Directory listings must stay off: public/ upload folders would otherwise be browsable.'
        );
    }

    private function explainUnguarded(array $unguarded): string
    {
        if ($unguarded === []) {
            return '';
        }

        $lines = array_map(
            fn (string $segment) => "  - public/{$segment}/ will 403 the /{$segment} page "
                .'as soon as the first image is uploaded there',
            $unguarded
        );

        $names = implode('|', array_merge($this->guardedSegments(), $unguarded));

        return "Unguarded upload directories collide with live routes:\n"
            .implode("\n", $lines)
            ."\n\nAdd them to the RewriteRule in public/.htaccess:\n"
            ."  RewriteRule ^({$names})/?\$ index.php [L]\n";
    }

    /** Top-level directory segments the app writes uploads into. */
    private function uploadSegments(): array
    {
        $segments = [];

        foreach ($this->appSourceFiles() as $file) {
            $source = file_get_contents($file);

            preg_match_all(
                '/(?:storeImage\(\s*\$request\s*,\s*[^,]+,|ImageThumbnailer::generate\(\s*[^,]+,)'
                .'\s*(?<dir>\'[^\']*\'|"[^"]*"|self::[A-Z_]+)\s*\)/',
                $source,
                $matches
            );

            foreach ($matches['dir'] as $argument) {
                $directory = $this->resolveDirectoryArgument($argument, $file);
                $segments[] = explode('/', trim($directory, '/'))[0];
            }
        }

        $this->assertNotEmpty(
            $segments,
            'Found no upload directories at all -- the scanner stopped matching the call sites '
            .'it is meant to watch, so this test is no longer protecting anything.'
        );

        return array_values(array_unique($segments));
    }

    /**
     * Reads the real value behind a self::CONST argument instead of duplicating it here,
     * so renaming the constant fails loudly rather than dropping a directory from the scan.
     */
    private function resolveDirectoryArgument(string $argument, string $file): string
    {
        if (str_starts_with($argument, "'") || str_starts_with($argument, '"')) {
            return trim($argument, '\'"');
        }

        $class = $this->classDeclaredIn($file);
        $constant = substr($argument, strlen('self::'));

        $this->assertNotNull($class, "Could not work out the class declared in {$file}.");

        $constants = (new ReflectionClass($class))->getConstants();
        $this->assertArrayHasKey(
            $constant,
            $constants,
            "{$class} passes self::{$constant} as an upload directory but does not define it."
        );

        return (string) $constants[$constant];
    }

    private function classDeclaredIn(string $file): ?string
    {
        $source = file_get_contents($file);

        if (! preg_match('/^namespace\s+([^;]+);/m', $source, $namespace)) {
            return null;
        }

        if (! preg_match('/^(?:final\s+|abstract\s+)?class\s+(\w+)/m', $source, $class)) {
            return null;
        }

        return trim($namespace[1]).'\\'.$class[1];
    }

    /** First path segment of every statically-addressable route. */
    private function routeSegments(): array
    {
        $segments = [];

        foreach (Route::getRoutes() as $route) {
            $first = explode('/', trim($route->uri(), '/'))[0];

            if ($first === '' || str_contains($first, '{')) {
                continue;
            }

            $segments[] = $first;
        }

        return array_values(array_unique($segments));
    }

    private function guardedSegments(): array
    {
        preg_match(
            '#RewriteRule\s+\^\((?<names>[a-z0-9|_-]+)\)/\?\$\s+index\.php#i',
            $this->htaccess(),
            $matches
        );

        return isset($matches['names']) ? explode('|', $matches['names']) : [];
    }

    private function guardOffset(string $htaccess): int
    {
        $found = preg_match(
            '#RewriteRule\s+\^\([a-z0-9|_-]+\)/\?\$\s+index\.php#i',
            $htaccess,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        $this->assertSame(1, $found, 'The upload-folder guard is missing from public/.htaccess.');

        return $matches[0][1];
    }

    private function htaccess(): string
    {
        $path = public_path('.htaccess');
        $this->assertFileExists($path);

        return file_get_contents($path);
    }

    /** @return iterable<string> */
    private function appSourceFiles(): iterable
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(app_path(), \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                yield $file->getPathname();
            }
        }
    }
}
