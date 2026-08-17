# Technical Architecture — Jabulani Store

---

## 1. Middleware Stack (in Kernel registration order)

### Global HTTP Middleware (applied to every request)
- `TrustProxies`
- `PreventRequestsDuringMaintenance`
- `ValidatePostSize`
- `TrimStrings`
- `ConvertEmptyStringsToNull`
- `SecurityHeaders` ← Custom: sets CSP, HSTS, X-Frame, X-XSS, X-Content-Type, Referrer-Policy

### `web` Group Middleware
- `EncryptCookies`
- `AddQueuedCookiesToResponse`
- `StartSession`
- `ShareErrorsFromSession`
- `VerifyCsrfToken`
- `SubstituteBindings`

### Route-Level Middleware
| Alias | Class | Purpose |
|:---|:---|:---|
| `auth` | `\Illuminate\Auth\Middleware\Authenticate` | Must be logged in |
| `verified` | `\Illuminate\Auth\Middleware\EnsureEmailIsVerified` | Email must be confirmed |
| `role:admin` | Custom | `users.role === 'admin'` |
| `role:manager` | Custom | `users.role === 'manager'` |
| `role:user` | Custom | `users.role === 'user'` |
| `permission:{name}` | Custom | Checks `users.permissions` JSON for `{name}` |
| `profile.complete` | Custom | Redirects to `/profile/complete` if phone missing |
| `throttle:6,1` | Built-in | Rate limiting (6 req/min) on auth and contact forms |
| `signed` | Built-in | Email verification link validation |

---

## 2. Route Groups Summary

| Prefix | Name Prefix | Middleware Stack | Purpose |
|:---|:---|:---|:---|
| `/` | (none) | `web` | Public frontend |
| `/email` | `verification.` | `auth` | Email verification |
| `auth/google` | `auth.google` | `web` | Google OAuth |
| `/` | (none) | `throttle:6,1` | Login/Register |
| `/profile` | `profile.` | `auth` | Profile management |
| `/cart` | `cart.` | `web` | Cart (session-based) |
| `/checkout` | `checkout.` | `profile.complete` | Checkout (guest or auth) |
| `/user` | `user.` | `auth,verified,role:user,profile.complete` | Customer portal |
| `/admin` | `admin.` | `auth,role:admin,permission:{module}` | Admin portal (PBAC) |
| `/branch` | `branch.` | `auth,role:manager` | Branch manager portal |

---

## 3. Controller → Model Touch Points Matrix

| Controller | Primary Models Used |
|:---|:---|
| `CartController` | `Product`, `Order`, `OrderItem`, `Store`, `Setting`, `User`, `Address` |
| `Admin\OrderController` | `Order`, `OrderItem`, `ActivityLog`, `Setting` |
| `Admin\ProductController` | `Product`, `Category`, `Brand`, `Store`, `ProductStoreStock` |
| `Admin\StoreController` | `Store`, `User` |
| `Admin\SystemController` | `Setting` |
| `Admin\MarketingController` | `Campaign`, `User` (notifications) |
| `Admin\UserController` | `User` |
| `Admin\DashboardController` | `Order`, `User`, `Product`, `Store` |
| `Branch\OrderController` | `Order`, `OrderItem`, `Store` |
| `Branch\StockController` | `ProductStoreStock`, `Store`, `Product` |
| `User\OrderController` | `Order`, `OrderItem` |
| `User\DashboardController` | `Order`, `User` |
| `User\NotificationController` | `Notification` (DB) |
| `HomeController` | `Category`, `Product`, `Store`, `Brand`, `Banner`, `BlogPost`, `Service`, `TeamMember` |
| `AuthController` | `User` |
| `Auth\SocialAuthController` | `User` (Socialite) |
| `ProfileController` | `User`, `Address` |
| `OrderTrackingController` | `Order` |

---

## 4. Service Layer

| Service | File | Purpose |
|:---|:---|:---|
| `StoreService` | `app/Services/StoreService.php` | Finds nearest branch via Haversine formula |
| `ActivityLog` | `app/Models/ActivityLog.php` | Static `record()` helper for audit logs |

### Shared controller concerns

| Trait | File | Used by |
|:---|:---|:---|
| `ValidatesImageUploads` | `app/Http/Controllers/Concerns/ValidatesImageUploads.php` | all 10 `Admin\*` upload controllers |
| `FlushesContentCache` | `app/Models/Concerns/FlushesContentCache.php` | `Banner`, `Store`, `Brand`, `Category`, `TeamMember`, `GalleryItem`, `BlogPost` |

### Public content cache (added 2026-08-17)

`HomeController` wraps public content in `Cache::remember(..., 3600, ...)`. Those keys
originally had **no invalidation**, so an admin save left the site serving a stale copy for
up to an hour — it presented as "I uploaded it and nothing changed". A 5th banner sat in the
live database while the homepage rendered 4 slides.

`FlushesContentCache` hooks the models' `saved` and `deleted` events, so **every** write path
invalidates: admin CRUD, tinker, seeders, CSV import. Models declare
`protected static array $contentCacheKeys = [...]`, or override `contentCacheKeys()` when the
key depends on the row.

| Key | Owner model | Read by |
|:---|:---|:---|
| `banners` | `Banner` | `HomeController::index` |
| `stores_all` | `Store` | `HomeController::index` |
| `stores_page` | `Store` | `HomeController::stores` |
| `brands` | `Brand` | `HomeController::index` |
| `categories_top` | `Category` | `HomeController::index` |
| `team_about` | `TeamMember` | `HomeController::about` (first 4 — an intentional limit) |
| `team_all` | `TeamMember` | `HomeController::team` |
| `gallery_all` | `GalleryItem` | `HomeController::gallery` |
| `blog_post_{slug}` | `BlogPost` | `HomeController::blogDetail` |

`BlogPost` also clears the **previous** slug's key via `getOriginal('slug')` — otherwise a
renamed post keeps serving its pre-edit copy on the old URL.

**Adding a new cached key**: register it on the owning model's `$contentCacheKeys`, or the
same stale-content bug returns. Caching without invalidation is the failure mode to avoid,
not caching itself — the TTL stays at 3600 because invalidation now handles freshness.

Single source of truth for image upload rules and storage. Provides:

| Member | Purpose |
|:---|:---|
| `IMAGE_MIMES` | `jpg,jpeg,png,gif,webp,avif` — SVG deliberately excluded (can carry embedded JS) |
| `IMAGE_MAX_KB` | `8192` (8MB) |
| `imageRules(bool $required, ?int $maxKb)` | returns e.g. `nullable\|mimes:…\|max:8192` |
| `imageMessages(string $field, ?int $maxKb)` | plain-language `mimes`/`max`/`uploaded` messages |
| `storeImage($request, $field, $dir)` | uuid filename, checks the `throw => false` return, aborts 500 on failure |

**Never write `image|mimes:…` together.** As of Laravel 12 the `image` rule resolves to
`jpg,jpeg,png,gif,bmp,webp` and no longer implies `svg` (that needs `image:allow_svg`), so
combining them intersects the two lists and yields a rule whose error message contradicts
itself. `mimes` alone already validates the file's real guessed type, not the client name.
`CartController`'s `payment_screenshot` is the one field that does not use the trait — it
also accepts `pdf`.

---

## 5. Config File Map

| Config | Key Settings |
|:---|:---|
| `config/logging.php` | `default = daily` (rotates logs by date, 14-day retention) |
| `config/auth.php` | `guards.web` uses `users` table providers |
| `config/mail.php` | Driven by `.env` — `log` locally, SMTP on production |
| `config/cache.php` | `driver = file` (both local and production) |
| `config/session.php` | `driver = file` |
| `config/queue.php` | `connection = sync` (no queue worker needed) |

---

## 6. Frontend Technology

| Area | Technology |
|:---|:---|
| Admin Dashboard | Vanilla CSS (Carbon Pro design system) + Bootstrap |
| Frontend Pages | Tailwind CSS (CDN) |
| Interactive Elements | Alpine.js (`x-data`, `x-show`, `x-model`) |
| PDF Generation | DomPDF (`barryvdh/laravel-dompdf`) |
| Admin Selects | Select2 |
| Icons | Font Awesome 6 |
| Fonts | Google Fonts (Inter/Outfit) |

---

## 7. File Storage Paths

> ⚠️ **Corrected 2026-08-17.** This table previously claimed uploads live under
> `public/storage/…` / `storage/app/public/…`. That is wrong for this project.

**The `public` disk root is overridden.** `config/filesystems.php` sets

```php
'public' => [
    'driver' => 'local',
    'root'   => public_path(''),      // NOT Laravel's default storage_path('app/public')
    'url'    => env('APP_URL'),
    'throw'  => false,               // failed writes return false, they do NOT raise
],
```

So `$file->store('products', 'public')` writes to **`public/products/`**, served at
`/products/{file}` — there is no `/storage/` segment. The `public/storage` symlink is
vestigial and nothing depends on it.

`'throw' => false` means **every write must have its return value checked** — an unchecked
`false` saves an empty path while still reporting success. Use
`ValidatesImageUploads::storeImage()`, which does this.

### Three storage schemes are live simultaneously

| Scheme | Example DB value | On disk | Origin |
|:---|:---|:---|:---|
| Legacy seeded | `images/Cement.webp` | `public/images/` | original seed data (~629 tracked files, incl. `public/images/products/` with 310) |
| Banners | `uploads/banners/{uuid}.webp` | `public/uploads/banners/` | `BannerController` |
| Disk uploads | `products/{uuid}.webp` | `public/products/` | all other upload controllers |

| Asset | DB value / location | Access URL |
|:---|:---|:---|
| Product images | `products/{uuid}.{ext}` | `/products/{file}` |
| Banners | `uploads/banners/{uuid}.{ext}` | `/uploads/banners/{file}` |
| Categories / brands / gallery / services / team / stores / blog | `{folder}/{uuid}.{ext}` | `/{folder}/{file}` |
| Invoice logo | `settings/{uuid}.{ext}` | `/settings/{file}` |
| Payment screenshots (EFT proof) | `public/payments/` | `/payments/{file}` |
| Daily logs | `storage/logs/laravel-YYYY-MM-DD.log` | Server only |

### Never resolve an image path by hand

`app/helpers.php` (loaded via `composer.json` `autoload.files` **and**
`AppServiceProvider::register()`) is the only sanctioned resolver:

| Helper | Returns | Use for |
|:---|:---|:---|
| `image_url($path, $fallback)` | browser URL, placeholder if missing | every Blade `<img src>` |
| `image_path($path, $fallback)` | absolute filesystem path | DomPDF (`pdf/invoice.blade.php`) — it cannot fetch an http src |
| `image_relative_path($path)` | path relative to `public/`, or null | existence checks |

It resolves all three schemes above, passes absolute URLs through untouched, and
`rawurlencode`s each path segment so a `+` in a filename is not read as a space.

The dual registration is deliberate: `autoload.files` is baked into
`vendor/composer/autoload_files.php` and only refreshed by `composer dump-autoload`, and
`vendor/` is gitignored — so on a host with no shell, a bare `git pull` would otherwise
leave `image_url()` undefined and fatal every view that renders an image. Each function is
wrapped in `function_exists()`, so the double load is harmless.
