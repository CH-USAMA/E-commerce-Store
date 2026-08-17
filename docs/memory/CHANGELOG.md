# Changelog — Jabulani Store

> Format: `## [YYYY-MM-DD] — Summary`
> Add newest entries at the top.

---

## [2026-08-17] — Image Upload Fixes (7 bugs) + Deployment Reality Check

**Type**: Bug Fix (Admin uploads) & Documentation Correction
**Reported as**: "sometimes when I upload images for banner or stocks it accepts webp or
.png but sticks the UI and sometimes gives extension error"

**Files Changed**: `app/helpers.php` (new), `Concerns/ValidatesImageUploads.php` (new),
`public/.user.ini` (new), all 10 `Admin\*` upload controllers, `CartController.php`,
`AppServiceProvider.php`, `composer.json`, `layouts/admin.blade.php`, 36 Blade views,
`.gitignore`, `public/.gitignore`, `ARCHITECTURE.md`, `SECURITY.md`, `ADMIN_PANEL.md`,
`DEPLOYMENT.md`, `KNOWN_ISSUES.md`, `TESTING_CHECKLIST.md`, `MEMORY.md`

### Root causes — seven independent defects, which is why it looked random

1. **WebP/AVIF rejected on banners.** `BannerController` used
   `image|mimes:jpeg,png,jpg,gif,svg`. In Laravel 12 the `image` rule resolves to
   `jpg,jpeg,png,gif,bmp,webp` and no longer implies `svg`. Both rules must pass, so the
   real allow-list was just `jpg,jpeg,png,gif` — WebP failed on `mimes` while the message
   advertised `svg`, which failed on `image`. Every other module used bare `image`, which
   *does* allow WebP → the inconsistency.
2. **Validation errors were invisible.** `layouts/admin.blade.php` had no `$errors` block
   (2 of 35 views rendered errors) and the product/banner forms had no `old()`. A rejection
   bounced back to a blank form with no message — the reported "stuck UI".
3. **Requests that hung forever.** `SESSION_DRIVER=file`; PHP holds an exclusive `flock()`
   on the session file per request, so an impatient second submit blocked on the lock doing
   no work, and syscall waits do not count toward `max_execution_time`.
4. **Silent write failures.** The `public` disk sets `'throw' => false`, so a failed
   `store()` returned `false` and saved an empty image while reporting success.
5. **Product edit could never save.** `ProductController@update` validated
   `stocks.*` => `numeric`, but the edit form posts nested `stocks[<id>][quantity]`.
6. **Admin Edit buttons 404'd** for products, categories and brands — views passed
   `$model->id` to `route()` while the models bind by `slug`. The product edit form's
   `update`/`destroy` actions were also pointed at 404 URLs.
7. **Broken image paths.** Uploads land in `public/<folder>/` (the `public` disk root is
   overridden to `public_path('')`), but `admin/products/index` prefixed non-legacy paths
   with `images/` → 404 thumbnails, and `pdf/invoice.blade.php` looked under
   `public/storage/`.

### Changes

- **`ValidatesImageUploads` trait** — one rule for all 11 upload paths:
  `mimes:jpg,jpeg,png,gif,webp,avif|max:8192`, plain-language messages, and a
  `storeImage()` that uses UUID filenames and checks the `throw => false` return value.
  SVG stays excluded (embedded-JS/XSS risk).
- **`image_url()` / `image_path()` / `image_relative_path()`** in `app/helpers.php` replace
  ad-hoc `file_exists`/`Str::contains`/`'images/'`-prefixing across 28 views. Resolves all
  three live storage schemes, so **no data migration was needed**. Registered via both
  `composer.json` `autoload.files` **and** `AppServiceProvider::register()` so a deploy
  without `composer dump-autoload` cannot fatal.
- Global `$errors` alert + submit-button locking in the admin layout.
- `old()` repopulation, inline `@error`, explicit `accept` lists and size hints on upload forms.
- Replaced images are now pruned on update; records delete their files.
- `public/.gitignore` gained `!.user.ini` — its blanket `*` silently swallows new files
  (the same trap that once left `design-system.css` 404ing).

### Corrections to prior documentation

- **`ARCHITECTURE.md § 7` was wrong.** It claimed uploads live under `public/storage/…`.
  They do not — the `public` disk root is `public_path('')`.
- **The 2M/8M upload ceiling was local, not production.** Live's web SAPI allows 1536M, so
  the 419 Page Expired path was never reachable on live. `public/.user.ini` is committed but
  is **not** honoured on Hostinger; the binding limit is the app's own `max:8192`.
- **Dev and live are two different GitHub repos** (`CH-USAMA/E-commerce-Store` `master` vs
  `JabulaniGroup/Jabulani-E-commerce-Store` `main`). Pushing to dev deploys nothing. See
  `MEMORY.md` Rule 2 for the merge-locally-then-fast-forward procedure.
- SSH does work (`-p 65002`); the old "never give SSH instructions" rule was unfounded.

### Verified

All 9 public + 20 admin pages render 200 with zero empty image srcs; old-vs-new rules
checked against real generated jpg/png/gif/webp/avif/svg files; helper verified against a
deliberately stale autoloader; all Blade templates compile; live site confirmed 200 across
10 routes post-deploy.

**Still open**: `APP_DEBUG=true` on production (see `SECURITY.md § 8`), and EFT screenshots
world-readable in `public/payments/`.

**Deploy**: merge live's `main` locally → push → on live `git merge --ff-only` +
`php artisan view:clear`. No migration. `composer dump-autoload` optional.

---

## [2026-07-25] — Hide Pricing / Inquiry Mode

**Type**: Feature (Storefront)  
**Files Changed**: `SystemController.php`, `Kernel.php`, `CheckPricingEnabled.php` (new), `web.php`, `payments.blade.php`, `price_or_contact.blade.php` (new), `product_card.blade.php`, `products.blade.php`, `product-single.blade.php`, `layouts/frontend.blade.php`, `ADMIN_PANEL.md`

- **New setting `hide_pricing`**: toggle on `Admin > Settings > Payments` ("Storefront Pricing" card). When enabled, hides prices site-wide (homepage carousels, shop/search listing, product detail page) and replaces Add to Cart with a WhatsApp "Contact Us" CTA (using `invoice_company_phone`, no price in the prefilled message).
- **Route guard**: new `pricing.enabled` middleware (`CheckPricingEnabled`) wraps all `/cart/*` and `/checkout/*` routes (except `/cart/count`) and redirects to `/contact` while `hide_pricing` is on. `/order-success` and `/track-order` remain unguarded so existing orders still work.
- **Header cart icon** hidden while `hide_pricing` is on.
- SEO JSON-LD price schema and `llms.txt`/`llms-full.txt` catalog pages intentionally left unchanged (out of scope).

---

## [2026-04-06] — Paystack Integration & Gateway Selection

**Type**: Feature (Payments)  
**Files Changed**: `SystemController.php`, `PaystackController.php`, `CartController.php`, `web.php`, `payments.blade.php`, `CHANGELOG.md`, `DATABASE_SCHEMA.md`, `ADMIN_PANEL.md`

- **Paystack Integration**: Implemented a full redirect-based payment flow for Paystack (Public/Secret Key support).
- **Gateway Selection**: Added an "Active Gateway Strategy" in the Admin Panel to switch the "Online Payment" method between Stripe and Paystack dynamically.
- **Paystack Callback**: Implemented a secure verification handler that confirms transactions via the Paystack API before updating order status and sending confirmation emails.
- **Unified Frontend**: Customers only see a single "Online Payment" option, while the backend determines the provider.

---

## [2026-04-06] — Global Theme Engine & Admin Permission System

**Type**: Feature & Security Hardening  
**Files Changed**: `User.php`, `web.php`, `Kernel.php`, `CheckPermission.php`, `UserController.php`, `SystemController.php`, `frontend.blade.php`, `admin.blade.php`, `user.blade.php`, `home.blade.php`, `theme.blade.php`, `SocialAuthController.php`, `AuthController.php`

### 🎨 Global Theme Customization
- **Custom Theme Engine**: Implemented a dynamic theme system in the Admin Panel (System > Theme Settings).
- **Dynamic CSS Variables**: Injected `--brand-primary`, `--brand-primary-rgb`, `--bg-main`, `--bg-surface`, and `--text-primary` into all layouts. All hardcoded yellow/gold accents replaced with these variables.
- **Visual Polish**: Removed hardcoded yellow `sepia` and `hue-rotate` filters from category images. Implemented dynamic RGBA shadow calculation based on primary color.
- **Contrast Logic**: Theme automatically adjusts primary text color (black/white) based on the background to ensure readability.

### 🔐 Admin Permission System (RBAC Lite)
- **Granular Permissions**: Added a `permissions` JSON column to the `users` table.
- **Module Access**: Admins can now be restricted to specific modules: `manage_products`, `manage_orders`, `manage_content`, `manage_users`, `manage_settings`, `view_analytics`.
- **Dynamic Sidebar**: Sidebar links and dashboard stats now hide/show based on the logged-in user's assigned permissions.
- **Permission Middleware**: Created `CheckPermission` middleware to enforce route-level security.

### 🛠️ Auth & System Fixes
- **Social Auth Fix**: Added `email_verified_at` to `User::$fillable`. Google logins now correctly mark users as verified without sending redundant verification emails.
- **Manual Registration**: Explicitly called `sendEmailVerificationNotification()` in `AuthController` to ensure delivery.
- **Regex Repair**: Fixed regex delimiter issues in `SystemController`.

**Deploy**: `git pull` + `php artisan migrate` + `php artisan route:clear` + `php artisan view:clear`.

---

## [2026-04-02] — AIO (AI Optimization) & llms.txt Implementation

**Type**: SEO & AI Web Scraper Optimization
**Files Changed**: `routes/web.php`, `robots.blade.php`, `llms.blade.php`, `llms-full.blade.php`

### Fixes
*   **Blade Syntax Errors**: Fixed "unexpected end of file" errors by expanding inline `@if` statements and re-organizing `@push` blocks in `home`, `product-single`, and `store-detail` views. This ensures compatibility with older or strictly configured Blade compilers on production servers (e.g., Hostinger).

- Upgraded `robots.txt` to explicitly invite major AI engines (`GPTBot`, `ClaudeBot`, `PerplexityBot`, `Google-Extended`)
- Implemented `/llms.txt` — a machine-readable markdown overview of the business tailored for ChatGPT/Claude
- Implemented `/llms-full.txt` — a complete raw markdown catalog dumping all products and prices for instant AI comparison against competitors (Cashbuild)

**Deploy**: `git pull` + `php artisan route:clear`.

---

## [2026-04-02] — Full SEO Overhaul (Sitemap & JSON-LD)

**Type**: SEO & Performance
**Files Changed**: `routes/web.php`, `sitemap.blade.php`, `robots.blade.php`, `home.blade.php`, `product-single.blade.php`, `store-detail.blade.php`

- Replaced old static sitemap with dynamic `/sitemap.xml` covering stores, products, categories, brands, and blogs
- Added dynamic `/robots.txt` blocking `/admin` and `/cart` to preserve Google crawl budget
- Injected `Organization` JSON-LD schema into the homepage with all 5 social links
- Injected `Product` JSON-LD schema into product pages to enable Google Shopping price badges
- Injected `HardwareStore` (LocalBusiness) JSON-LD schema into store pages to trigger "near me" Maps rankings

**Deploy**: `git pull` + `php artisan route:clear` + Submit `/sitemap.xml` to Google Search Console.

---

## [2026-04-02] — Documentation Overhaul & AI Intelligence System

**Type**: Documentation
**Files Changed**: `MEMORY.md`, `docs/memory/*` (14 files total)

- Rebuilt entire `docs/memory/` system from scratch into a dense, machine-readable format
- Added: `USER_PORTAL.md`, `SECURITY.md`, `DEPLOYMENT.md`, `TESTING_CHECKLIST.md`, `KNOWN_ISSUES.md`, `FEATURE_MAP.md`
- Upgraded: `SYSTEM_OVERVIEW.md` (role matrix, integrations), `DATABASE_SCHEMA.md` (full column-level schema), `ARCHITECTURE.md` (middleware stack, controller→model matrix), `ORDER_FLOW.md` (all branch conditions), `PRODUCT_FLOW.md` (WMS states, CSV spec), `ADMIN_PANEL.md` (full route table, settings registry), `IMPROVEMENT_PLAN.md` (Phase 5 tasks + AI protocol)
- `MEMORY.md` root: Now an AI operating contract with 6 enforceable rules, environment comparison table, and full doc index

**Deploy**: No code changes. Documentation only. No migration or cache clear required.

---

## [2026-04-02] — Stripe Integration Fix
## [2026-04-02] — Stripe & Paystack Integration

**Type**: Feature & Bug Fix
**Files Changed**: `app/Http/Controllers/CartController.php`, `database/migrations/add_paystack_settings.php`

- Fixed `Class "Stripe\Stripe" not found` — ran `composer install --no-dev`
- Updated payment routing to handle `payfast`, `stripe`, and `paystack`
- Added Paystack integration with webhook verification
- Added new settings keys:
| Key | Type | Location | Description |
| :--- | :--- | :--- | :--- |
| `invoice_company_name` | string | Invoice page | Company name on PDF |
| `stripe_enabled` | `0` or `1` | Payments page | Enable/disable Stripe checkout |
| `paystack_enabled` | `0` or `1` | Payments page | Enable/disable Paystack checkout |
| `preferred_online_gateway` | `stripe` or `paystack` | Payments page | Currently active online provider |
| `paystack_public_key` | string | Payments page | Paystack public key |
| `paystack_secret_key` | string | Payments page | Paystack secret key |

**Deploy**: Run `composer install --no-dev` and `php artisan migrate` on Hostinger after `git pull`.

---

## [2026-04-01] — UUID Security Hardening (Full System)

**Type**: Security
**Files Changed**: Multiple views, `NewOrderNotification.php`, `OrderStatusChangedNotification.php`

- Fixed admin order views: all form actions now use `$order` object (UUID), not `$order->id`
- Fixed branch manager order view: status update form uses UUID routing
- Fixed notification payloads: `order_id` now stores UUID string instead of integer
- Repaired legacy notification records in DB (used `tmp/repair_notifications_uuids.php`)

**Deploy**: Run repair script on live if old notifications still show integer URLs.

---

## [2026-04-01] — Production Logging (Daily Rotation)

**Type**: Infrastructure
**Files Changed**: `config/logging.php`

- Changed default log channel to `daily` driver
- Log files now stored as `storage/logs/laravel-YYYY-MM-DD.log`
- Retention: 14 days

**Deploy**: `git pull` + `php artisan config:clear`.

---

## [2026-04-01] — Fix 403 After Google OAuth Login

**Type**: Bug Fix
**Files Changed**: `app/Http/Controllers/Auth/SocialAuthController.php`

- New Google users were not being assigned `role = 'user'`
- `role:user` middleware blocked access to `/user/dashboard`
- Fix: Explicitly set `$user->role = 'user'` on user creation in `handleGoogleCallback()`

---

## [2026-03-31] — WMS Inventory States

**Type**: Feature
**Files Changed**: Migration, `ProductStoreStock` model, branch views

- Added `incoming_quantity`, `reserved_quantity`, `damaged_quantity` to `product_store_stocks`
- Updated branch dashboard to display and edit all WMS states
- Updated CSV import/export to include WMS columns

---

## [2026-03-31] — Audit Logging System

**Type**: Feature
**Files Changed**: New `activity_logs` migration, `ActivityLog` model

- Created `ActivityLog` model with static `record()` helper
- Logs: order status changes, payment confirmations, stock adjustments
- Columns: `action`, `model_type`, `model_id`, `old_values`, `new_values`, `user_id`

---

## [2026-03-31] — FULLTEXT Search

**Type**: Performance
**Files Changed**: Migration (FULLTEXT index), `HomeController`

- Added FULLTEXT indexes on `products.name` and `products.description`
- Replaced `LIKE '%query%'` with `MATCH...AGAINST` for faster search
- Fallback to `LIKE` if FULLTEXT returns no results

---

## [2026-03-19] — Stripe Checkout Integration (Initial)

**Type**: Feature
**Files Changed**: `CartController.php`, `resources/views/frontend/checkout.blade.php`

- Added Stripe Checkout payment path (using `payfast` as DB value — legacy alias)
- Stripe keys stored in `settings` DB table, managed via Admin > Payments
- On success: order status updated to `processing`, confirmation email dispatched

---

## [2026-03-19] — Database Notifications

**Type**: Feature
**Files Changed**: `notifications` migration, `NewOrderNotification`, `OrderStatusChangedNotification`

- Admin notified via DB notification when new order is placed
- User notified via DB notification when order status changes
- Notification bell in admin and user dashboards

---

## [2026-03-06] — Google OAuth (Socialite)

**Type**: Feature
**Files Changed**: `SocialAuthController`, `users` migration (social fields), routes

- Google OAuth via Laravel Socialite
- Stores `google_id`, `google_token`, `google_refresh_token` on user
- Auto-verifies email on first Google login

---

## [2026-03-04] — Security Middleware

**Type**: Security
**Files Changed**: `app/Http/Middleware/SecurityHeaders.php`, kernel

- Added CSP, HSTS, X-Frame, XSS, X-Content-Type, Referrer-Policy headers
- Applied globally to all HTTP responses
- Rate limiting (throttle:6,1) on auth and contact routes

---

## [2026-03-03] — Initial System Build

**Type**: Initial Release
- Laravel 12 project initialized
- Core tables: users, stores, categories, brands, products, orders, order_items, settings
- Admin portal (Carbon Pro) with full CRUD
- Frontend (Tailwind + Alpine.js) with cart, checkout, product listing
- EFT payment flow with proof-of-payment upload
- Branch manager portal
- Geolocation nearest-store detection
