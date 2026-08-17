# Known Issues, Bugs & Gotchas

> This file is a living document. Add new findings immediately when discovered.
> Format: `## [YYYY-MM-DD] — Issue Title`

---

## Active Issues

### 🛑 DO NOT set `hide_pricing = 0` until the payment findings below are fixed

Reviewed 2026-08-17. The store currently runs in **inquiry mode** (`hide_pricing = '1'`),
so `CheckPricingEnabled` redirects all `/cart/*` and `/checkout/*` traffic to `/contact`
and enquiries go to WhatsApp. That middleware is the **only** thing preventing two serious
defects from being exploitable. Re-enabling pricing re-arms both instantly.

Verified live: `/cart`, `/checkout`, `/checkout/auth` all 302 → `/contact`.

---

### 🔴 Stripe payment can be bypassed (partly live even in inquiry mode)
- **Where**: `CartController::orderSuccess()` (~line 487)
- **Defect**: an order is marked `processing` purely because someone loaded
  `/order-success?order_number=…`. There is **no** webhook, no `Session::retrieve()`, no
  `payment_status` check — nothing contacts Stripe at all.
- **Exploit**: check out with online payment, abandon the Stripe page, then visit
  `/order-success?order_number=<your own number>`. Order is marked paid and a confirmation
  email is sent. No guessing required.
- **Why still partly live**: `/order-success` is deliberately unguarded so existing orders
  keep working. As of 2026-08-17 there were **7 orders** in `pending` + `payfast` that could
  each be flipped to `processing` by anyone holding the order number.
- **Contrast**: `PaystackController::callback()` does this correctly — it calls
  `transaction/verify/{reference}` and checks `data.status === 'success'`. Stripe never got
  the same treatment.
- **Cheap interim fix**: with no payment gateway configured, `orderSuccess()` has no reason
  to mutate status at all. Deleting that block closes the finding with no design decision.
- **Proper fix**: Stripe webhook (CSRF-exempt route + signature verification) as the source
  of truth, optionally plus a `Session::retrieve()` check on return for instant feedback.

### 🔴 Payment screenshot keeps the uploader's file extension
- **Where**: `CartController::processCheckout()` (~line 374)
- **Defect**: `$filename = time().'_'.$file->getClientOriginalName();` then moved into
  `public/payments/`. The `mimes` rule validates file **content**, not the name — so a
  genuine JPEG named `evil.php` passes and is written as a `.php` file into a web-served
  directory. PHP hidden in an EXIF comment is a standard polyglot. Nothing in
  `public/.htaccess` disables PHP execution in subdirectories, and there is no
  `public/payments/.htaccess`.
- **Not** a traversal risk — Symfony strips directory separators from `getClientOriginalName()`.
- **Unconfirmed**: live execution was never demonstrated (writing a test `.php` into the
  production web root was correctly refused). Treat as high-confidence, unverified.
- **Fix**: use `Str::uuid().'.'.$ext` like `ValidatesImageUploads::storeImage()` already does,
  and add a `public/payments/.htaccess` denying PHP execution.
- **Dormant** while `hide_pricing = 1` (unreachable via the blocked checkout).

### 🟠 Stock is never decremented by an order
- Nothing in the checkout touches `ProductStoreStock` — it is only ever written by the admin
  product screens and the branch stock page. Unlimited overselling; stock figures are
  display-only; the `reserved` WMS state is never used by a real order.
- Moot while no orders can be placed, but must be solved before pricing is re-enabled.

### 🟡 VAT is always recorded as 0
- `'vat' => 0` is hardcoded on both `Order` and `OrderItem` in `processCheckout()`, though
  products carry `vat_rate` (default 15) and prices are documented as VAT-inclusive.
- Invoices therefore cannot show a VAT breakdown — likely a problem for SARS tax invoices.

### 🟡 `/track-order` is public and unthrottled
- No auth and no rate limit; exposes `customer_name`, `customer_address`, `customer_city`,
  `total` and `status`. Order numbers are `JB-YYYYMMDD-` + 6 chars of `[A-Z0-9]` (~2.2×10⁹),
  so not casually enumerable, but the date prefix narrows the space and nothing slows attempts.
- **Unaffected by inquiry mode** — this one is live now.

### 🔴 Checkout crashes for a logged-in customer with no saved address
- **Found**: 2026-08-17, while building "Order again"
- **Where**: `CartController::processCheckout()` (~line 363)
- **Defect**: it inserts `'address_name' => 'Default Site'`, but the `addresses` table has
  **no `address_name` column** — the columns are `id, user_id, type, address_line_1,
  address_line_2, city, province, postal_code, is_default`. `Address::$fillable` also lists
  the non-existent `address_name`.
- **Effect**: a non-existent column raises `SQLSTATE[42S22]` (mass assignment would have
  ignored it silently, a missing column does not). The `try/catch` rolls the transaction back,
  so the customer sees *"Something went wrong. Please try again."* and **the order is lost** —
  every time, for any logged-in customer whose address book is empty.
- **Fix**: drop `address_name` from the insert and from `Address::$fillable`, or add the
  column. Also give `type` and `province` values — both are real columns.
- **Dormant** while `hide_pricing = 1`. **This will fire on day one of re-enabling pricing.**

### 🟡 `store_id` is not validated at checkout
- `Store::find($request->store_id)` runs on unvalidated input and is absent from the
  `validate()` list, so an order can be saved with a null or arbitrary `store_id`.

### 🟡 `orders/fake` debug route ships in production
- `Route::get('orders/fake', …'createFakeOrder')` — admin-only, but a debug artifact.

> **Confirmed sound during the same review** (worth not re-auditing): order totals are
> computed **server-side** from database prices — the cart session holds only
> `product_id => quantity`, so prices cannot be tampered with. `User\OrderController::show()`
> checks ownership and 403s. Admin order queries use the query builder throughout.

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

### [2026-08-17] Product `status` was a dead feature
- `PRODUCT_FLOW.md` documented `status` as `required | active or inactive` controlling
  storefront visibility. In reality **none of it worked**:
  - `status` was **not in `Product::$fillable`** → every write silently dropped it
  - `ProductController` never validated it
  - Neither the create nor the edit form had a field for it
  - No public query filtered on it
- So every product sat at the column default `active` and could not be changed. All 331
  products were `active` — not a coincidence, an inevitability.
- **Fixed**: added `status` to `$fillable`, `required|in:active,inactive` validation, a
  Status select on both admin forms, and `Product::scopeActive()` applied to every public
  query (homepage sections, listing, search, product detail, recently-viewed).
- Inactive products now **404** on their own URL and drop out of every listing. Verified by
  deactivating a product, confirming 404 + absence from search, then restoring it.

### [2026-08-17] 500 on /admin/banners — stale route cache after deploy
- **Symptom**: `/admin/banners` returned 500. Log: `Route [admin.banners.move] not defined.`
- **Cause**: live had `bootstrap/cache/routes-v7.php` (dated Jul 25). While a route cache
  exists, `routes/web.php` is **never read**, so the newly deployed `admin.banners.move`
  route did not exist and the view's `route()` call threw. `config`, `view` and `cache` were
  cleared on deploy — `route` was not.
- **Fix**: `php artisan route:clear` on live. Recovery confirmed: last error 19:30:41, clean
  from 19:42 onward.
- **Prevention**: `route:clear` added as a mandatory step in `DEPLOYMENT.md` and `MEMORY.md`
  Rule 2. The clears are idempotent — always run all of them.
- **Note**: with `APP_DEBUG=false` the browser shows only a generic 500. This was the first
  real exercise of reading `storage/logs/` instead of an Ignition page, which is the intended
  workflow now.

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
