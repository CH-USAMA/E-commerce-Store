# Changelog — Jabulani Store

> Format: `## [YYYY-MM-DD] — Summary`
> Add newest entries at the top.

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

**Type**: Bug Fix
**Files Changed**: `app/Http/Controllers/CartController.php`

- Fixed `Class "Stripe\Stripe" not found` — ran `composer install --no-dev`
- Updated Stripe payment routing to handle both `payfast` and `stripe` as payment_method values
- Added proper error handling: if Stripe fails, order is still recorded with warning message
- Added `!empty($stripeSecret)` check to prevent empty key execution
- Added `order_number` to Stripe session metadata

**Deploy**: Run `composer install --no-dev` on Hostinger after `git pull`.

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
