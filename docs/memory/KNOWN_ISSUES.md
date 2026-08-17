# Known Issues, Bugs & Gotchas

> This file is a living document. Add new findings immediately when discovered.
> Format: `## [YYYY-MM-DD] — Issue Title`

---

## Active Issues

### ⚠️ EFT Screenshots Are World-Readable
- **Risk**: Proof-of-payment uploads land in `public/payments/` and are served directly, so
  anyone who guesses or is given a filename can read a customer's bank details
- **Fix**: serve them through an authenticated controller route instead of the public dir
- **Status**: Open (Phase 5)

### ⚠️ Payment Method Alias: `payfast` = Stripe
- **Issue**: The Stripe Online payment option uses `value="payfast"` in the HTML form and stores `payfast` in `orders.payment_method`
- **Reason**: Legacy naming from an earlier PayFast gateway integration
- **Impact**: Any code checking `payment_method === 'stripe'` will FAIL — must check `=== 'payfast'`
- **Fixed In**: `CartController@processCheckout` now checks both `payfast` AND `stripe`
- **Documentation**: This is intentional and known — do NOT rename without migrating DB values

### ⚠️ Stripe Class Not Found After Fresh Clone
- **Issue**: `Class "Stripe\Stripe" not found` on checkout
- **Cause**: `vendor/` is in `.gitignore` — Composer packages not cloned
- **Fix**: Run `composer install --no-dev` after every fresh clone
- **Already Resolved On**: Local machine (2026-04-02)

### ⚠️ Mail in Log Mode Locally
- **Issue**: Emails (verification, order confirmation) are NOT sent locally
- **Where They Go**: `storage/logs/laravel-YYYY-MM-DD.log` — search for "From:"
- **Production**: Correctly configured with SMTP via Hostinger
- **Local Testing**: Check log file for email content and verification links

### ⚠️ Notification URLs — Legacy Integer IDs
- **Issue**: Old notifications in DB had `url: /admin/orders/123` (integer ID)
- **Fix Applied**: `tmp/repair_notifications_uuids.php` was run to migrate these to UUID URLs
- **Status**: Resolved for existing data. New notifications always use UUID.

### ⚠️ `stripe_enabled` Must Be Set in DB
- **Issue**: Stripe doesn't activate from `.env` — keys are stored in the `settings` DB table
- **How to Enable**: Admin > Settings > Payments → set `stripe_enabled = 1`, add keys
- **Check Query**: `SELECT * FROM settings WHERE key LIKE 'stripe%';`

### ⚠️ Google OAuth Token Properties — IDE Lint
- **Issue**: `Undefined property: User::$token` in `SocialAuthController`
- **Cause**: The `token` on the Socialite user object is NOT the same as `User::$google_token`
- **Fix**: PHPDoc annotations added to `User` model for IDE hints
- **Note**: Functionally correct — `$socialUser->token` is Socialite's property, stored as `google_token`

---

## Resolved Issues (Historical)

### [2026-08-17] APP_DEBUG=true in Production
- Any visitor triggering an unhandled exception got an Ignition page with stack traces,
  source excerpts, environment variables and DB credentials — no auth required
- Set `APP_DEBUG=false` in the live `.env` + `php artisan config:clear`. A timestamped
  `.env.backup-*` was left on the server
- Verified via a temporary **web** probe (`app.debug = false`) — CLI alone would not prove
  what visitors receive — and by confirming a 404 and a `ModelNotFound` leak nothing
- Full detail in `SECURITY.md § 8`. **Keep it false**; diagnose from `storage/logs/` instead

### [2026-08-17] Image Uploads: Extension Errors, Silent Failures, Hanging Requests
Six overlapping defects that together made banner/product image uploads look random.

1. **Laravel 12's `image` rule no longer implies SVG.** It resolves to
   `jpg,jpeg,png,gif,bmp,webp`; SVG now needs an explicit `image:allow_svg`
   (`vendor/laravel/framework/.../ValidatesAttributes.php::validateImage`). `BannerController`
   used `image|mimes:jpeg,png,jpg,gif,svg`, and since both rules must pass the real allow-list
   was only `jpg,jpeg,png,gif` — WebP failed on `mimes` while the message advertised SVG, and
   SVG failed on `image`. Every other module used bare `image`, which *does* allow WebP, hence
   "sometimes works". **Fix**: `App\Http\Controllers\Concerns\ValidatesImageUploads` trait now
   supplies one rule (`mimes:jpg,jpeg,png,gif,webp,avif`) to all 11 upload controllers. SVG is
   excluded on purpose — it can carry embedded JavaScript.
2. **Validation errors were never displayed.** `layouts/admin.blade.php` had no `$errors` block
   (only 2 of 35 admin views rendered errors), and the product/banner forms used zero `old()`.
   A rejected upload bounced back to a blank form with no message, which read as a hang.
   **Fix**: global `$errors` alert in the admin layout + `old()`/`@error` on the image forms.
3. **PHP aborted large uploads before Laravel ran** — *local only, NOT production.*
   The `upload_max_filesize=2M` / `post_max_size=8M` ceiling was the **local dev PHP**.
   Live (checked on the web SAPI 2026-08-17) allows 1536M, so the discarded-POST → 419
   Page Expired path was never reachable in production. The gallery rule's impossible
   `max:5120`-vs-2M contradiction was likewise local only.
   **Fix**: `public/.user.ini` (16M/24M) guards a stingy host; it is **not honoured on
   Hostinger** and is not needed there. The real binding limit is Laravel's `max:8192`.
   Check upload limits with a **web** request — `.user.ini` never applies to CLI, so
   `php -i` over SSH reports different numbers.
4. **Requests that hung forever in the Network tab.** `SESSION_DRIVER=file`; PHP holds an
   exclusive `flock()` on the session file for a whole request, so an impatient second submit
   blocked on the lock doing no work. Time waiting in a syscall does not count toward
   `max_execution_time`, so nothing broke the deadlock. **Fix**: submit-button locking in
   `layouts/admin.blade.php` prevents the second request being issued at all.
5. **`'throw' => false` on the `public` disk hid write failures.** A failed `store()` returned
   `false`, so the record saved with an empty image and still reported success.
   **Fix**: `ValidatesImageUploads::storeImage()` checks the return value and aborts.
6. **`ProductController@update` could never save with a store present.** It validated
   `stocks.*' => 'numeric'`, but the edit form posts nested `stocks[<id>][quantity]`, so
   `stocks.<id>` is an array. **Fix**: nested `stocks.*.quantity` etc. rules.

Also fixed along the way: banners named files `time().ext` (two uploads in the same second
overwrote each other — now uuid); replaced images were not pruned on update; `pdf/invoice.blade.php`
looked for the logo under `public/storage/`, which is not where uploads land.

### [2026-08-17] Admin Edit/Update/Delete 404 for Products, Categories & Brands
`Product`, `Category`, `Brand` and `Store` all override `getRouteKeyName()` to return `'slug'`,
but the admin views passed `$model->id` to `route()`. That generated `/admin/products/1/edit`,
which implicit binding resolved as `Product::where('slug', '1')` → **404**.

Confirmed broken before the fix: the Edit button 404'd on products, categories and brands
(stores' index already passed the model). The product edit form's `update` and `destroy` actions
were also pointed at 404 URLs, so **the product edit form could not save at all** — which is the
same class of bug already recorded for orders on 2026-04-01, just never fixed for these four.

**Fix**: pass the model, not `->id` — `route('admin.products.edit', $product)`. 12 call sites
across `admin/{products,categories,brands,stores}/{index,edit}.blade.php`.

**Rule**: for any model overriding `getRouteKeyName()`, always pass the model instance to
`route()`. Passing `->id` compiles fine and fails only at runtime, as a 404.

### [2026-04-01] Admin Order URLs Using Integer IDs After UUID Migration
- `route('admin.orders.show', $order->id)` was used instead of `route('admin.orders.show', $order)`
- Fixed in: `admin/orders/show.blade.php`, `branch/orders/show.blade.php`
- Also fixed: `confirm-payment`, `update`, `invoice` form actions

### [2026-04-01] 403 Error After Google Login
- New Google users were not assigned `role = 'user'`
- `role:user` middleware gate failed → 403 on `/user/dashboard`
- Fix: `SocialAuthController` now explicitly sets `$user->role = 'user'` on creation

### [2026-04-01] `design-system.css` 404 on Admin Dashboard
- File was not present on live server
- Fix: Upload `public/css/design-system.css` to Hostinger

### [2026-03-31] Stripe PHP Library Missing
- `composer.json` declared `stripe/stripe-php ^19.4` but `vendor/` was empty
- Fix: Run `composer install --no-dev` on both local and Hostinger

---

## Gotchas for Future Development

1. **Adding a new field to `users`**: MUST add to `$fillable` if mass-assigned. `role` stays OUT.
2. **New migration**: Must run `php artisan migrate --force` on Hostinger after git pull.
3. **New Blade asset** (CSS/JS in `public/`): Must be manually uploaded OR be in git (confirm it's not gitignored).
4. **Storage files**: `public/storage` is a symlink — new file types must be in `storage/app/public/`.
5. **Order status values**: The valid set is `awaiting_payment,pending,processing,shipped,delivered,cancelled`. Adding a new status requires updating: `OrderController@update` validation, checkout blade status badges, and ideally the `TESTING_CHECKLIST.md`.
6. **Cart stored as JSON**: `users.cart_data` is a JSON column. Product IDs are the keys. Always integer product IDs (not UUIDs) for cart — UUIDs are for URLs only.
7. **FULLTEXT search**: Requires MySQL (not SQLite). Local `database.sqlite` cannot run FULLTEXT queries — always test search on MySQL.
8. **Rendering a stored image path**: always use the `image_url()` helper (`app/helpers.php`), never
   `asset($model->image)` or a hand-rolled `file_exists`/`Str::contains` check. Three storage
   schemes are live at once — `images/*` (legacy seed), `uploads/banners/*`, and `<folder>/*` from
   the `public` disk — and `image_url()` is the only thing that resolves all three plus the
   placeholder fallback and `+`-in-filename encoding. For DomPDF use `image_path()` instead; it
   returns a filesystem path, which is what DomPDF needs.
9. **The `public` disk root is overridden** to `public_path('')` in `config/filesystems.php`, NOT
   Laravel's default `storage_path('app/public')`. So `->store('products','public')` writes to
   `public/products/`, and `asset('storage/'.$path)` is wrong. The `public/storage` symlink is
   vestigial. The disk also sets `'throw' => false`, so always check whether a write returned `false`.
10. **New image validation**: use the `ValidatesImageUploads` trait rather than writing rules by
   hand, and never combine `image` with `mimes` — as of Laravel 12 their allow-lists differ over
   SVG and you get a rule whose error message contradicts itself.
11. **Laravel `max:` on a file must stay below PHP's `upload_max_filesize`** (`public/.user.ini`),
   or PHP kills the request before Laravel can produce a readable error.
