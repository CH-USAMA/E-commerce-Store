# Known Issues, Bugs & Gotchas

> This file is a living document. Add new findings immediately when discovered.
> Format: `## [YYYY-MM-DD] — Issue Title`

---

## Active Issues

### ⚠️ APP_DEBUG=true in Production
- **Risk**: Exposes stack traces and environment info to end users on errors
- **File**: `.env.production` line 4
- **Fix**: Set `APP_DEBUG=false` in production `.env`

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
