# Security — Jabulani Store

---

## 1. Middleware Security Stack

Applied globally to all requests via `SecurityHeaders` middleware:

| Header | Value | Purpose |
|:---|:---|:---|
| `X-Frame-Options` | `SAMEORIGIN` | Prevent clickjacking |
| `X-XSS-Protection` | `1; mode=block` | Browser XSS filter |
| `X-Content-Type-Options` | `nosniff` | Prevent MIME sniffing |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Limit referrer data |
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains` | Force HTTPS for 1 year |
| `Content-Security-Policy` | See below | Script/style source whitelist |

### CSP Whitelist
```
default-src 'self'
script-src: 'self' 'unsafe-inline' 'unsafe-eval' + cdn.jsdelivr.net, code.jquery.com,
            cdn.tailwindcss.com, unpkg.com, cdnjs.cloudflare.com
style-src: 'self' 'unsafe-inline' + fonts.googleapis.com, cdn.jsdelivr.net,
           cdnjs.cloudflare.com, cdn.tailwindcss.com
font-src: 'self' + fonts.gstatic.com, cdn.jsdelivr.net, cdnjs.cloudflare.com
img-src: 'self' data: https: ui-avatars.com
connect-src: 'self' https:
frame-src: 'self' https:
```

**Note**: `unsafe-inline` and `unsafe-eval` are currently allowed. Tightening CSP is a Phase 5 task.

---

## 2. RBAC (Role-Based Access Control)

### Role Assignment Rules
- `role` is NOT in `User::$fillable` — prevents mass-assignment privilege escalation
- To set role: `$user->role = 'admin'; $user->save();`
- New users (register or Google OAuth) always get `role = 'user'` explicitly
- Admin assignment must be done by existing admin via Admin > Users, or via Tinker

### Role Middleware Logic
Custom middleware checks `auth()->user()->role === $role`.
- 403 returned if role doesn't match
- Unauthenticated → redirected to login

### PBAC (Permission-Based Access Control)
As of [2026-04-06], the system supports granular module-level permissions for Admin/Staff users.
- **Middleware**: `CheckPermission` (alias: `permission:{name}`)
- **Data Source**: `users.permissions` (JSON column)
- **Logic**: `auth()->user()->hasPermission($name)` check.
- **Super Admin Override**: Users with `role === 'admin'` and no permissions set (or `'all'` in permissions) automatically have full access.
- **Available Modules**: `manage_products`, `manage_orders`, `manage_content`, `manage_users`, `manage_settings`, `view_analytics`.

---

## 3. URL Security — ID Obfuscation

**Invariant**: Integer database IDs must NEVER appear in user-facing URLs.

| Model | Route Identifier | Enforced By |
|:---|:---|:---|
| `Order` | `uuid` | `getRouteKeyName()` → `'uuid'` in `Order` model |
| `User` | `uuid` | `getRouteKeyName()` → `'uuid'` in `User` model |
| `Product` | `slug` | `getRouteKeyName()` → `'slug'` in `Product` model |
| `Store` | `slug` | `Store` model |
| `Category` | `slug` | `Category` model |
| `Brand` | `slug` | `Brand` model |

**Blade Rule**: Always pass model objects to `route()`, never `$model->id`:
```blade
{{-- CORRECT --}}
{{ route('admin.orders.show', $order) }}
{{ route('admin.orders.update', $order) }}
{{ route('admin.orders.confirm-payment', $order) }}

{{-- WRONG — will expose integer ID --}}
{{ route('admin.orders.show', $order->id) }}
```

### This is not only a disclosure rule — passing `->id` produces a hard 404

Because these models bind by `slug`/`uuid`, `route('admin.products.edit', $product->id)`
generates `/admin/products/1/edit`, which implicit binding resolves as
`Product::where('slug', '1')` → **404**. The integer is never exposed because the page
never loads.

Found and fixed 2026-08-17: the Edit button 404'd for **products, categories and brands**,
and the product edit form's `update`/`destroy` actions pointed at 404 URLs, so that form
could not save at all. `stores` was already correct. 12 call sites across
`admin/{products,categories,brands,stores}/{index,edit}.blade.php`.

Blade compiles `->id` happily and the failure only appears at runtime, so this cannot be
caught by a syntax check. Verify by rendering the index page and following its own links —
see `TESTING_CHECKLIST.md § Route Key Binding`.

---

## 4. Mass Assignment Protection

| Model | Protected Fields (NOT in fillable) |
|:---|:---|
| `User` | `role`, `uuid`, `id` |
| `Order` | `uuid`, `id` |
| `Product` | `uuid`, `id` |

*Note: `email_verified_at` was added to User::$fillable on [2026-04-06] to support seamless Social Auth account creation.*

---

## 5. Rate Limiting (Throttle)

| Route Group | Limit | Applied To |
|:---|:---|:---|
| Login | `throttle:6,1` | Brute-force protection |
| Register | `throttle:6,1` | Spam prevention |
| Contact form submit | `throttle:6,1` | Spam prevention |
| Email verification resend | `throttle:6,1` | Abuse prevention |

---

## 6. CSRF Protection

- Applied to all `web` routes via `VerifyCsrfToken` middleware
- All POST/PUT/DELETE forms must include `@csrf`
- JSON API routes excluded (if any added in future, use Sanctum tokens)

---

## 7. Input Validation

All controller methods use `$request->validate([...])` before any DB operations.
Key validation rules enforced:
- `payment_method`: must be `in:eft,payfast` (Online payments are dynamically routed to Stripe or Paystack)
- `status`: must be `in:awaiting_payment,pending,processing,shipped,delivered,cancelled`
- `order_type`: must be `in:pickup,delivery`
- Image uploads: `mimes:jpg,jpeg,png,gif,webp,avif|max:8192` (8MB) via the
  `ValidatesImageUploads` trait — see § 9
- `payment_screenshot`: `mimes:jpg,jpeg,png,gif,webp,avif,pdf|max:8192`

---

## 8. Known Security Risks & Mitigations

| Risk | Current State | Recommended Action |
|:---|:---|:---|
| `APP_DEBUG=true` in production | ⚠️ **Active — re-confirmed on live 2026-08-17** | Set `APP_DEBUG=false` in the live `.env`, then `php artisan config:clear` |
| EFT screenshots world-readable in `public/payments/` | ⚠️ Active | Move behind an auth'd controller route (Phase 5) |
| `.env` contains SSH/DB password in comments | ⚠️ Found | Delete those comment lines from `.env` |
| `unsafe-inline` in CSP | ⚠️ Active | Tighten in Phase 5 with nonce-based CSP |
| Stripe keys in DB not encrypted at rest | ⚠️ Note | Consider encrypting `settings` values in Phase 5 |
| No 2FA for admin | 🔲 Missing | Consider adding TOTP in Phase 5 |
| No login attempt log | 🔲 Missing | Extend `ActivityLog` to record failed logins |

### On `APP_DEBUG=true` with `APP_ENV=production`

Verified directly in the live `.env` on 2026-08-17 — still `true`. With
`spatie/laravel-ignition` installed, **any** visitor who triggers an unhandled exception
receives an error page exposing stack traces, source excerpts, environment variables and
DB credentials. No authentication is required to see it.

One-line fix, but it is a production config change and was left alone deliberately pending
sign-off:

```bash
# on live: ~/domains/jabulanigroupofcompanies.co.za/public_html/store
sed -i 's/^APP_DEBUG=true/APP_DEBUG=false/' .env && php artisan config:clear
```

Note that `config:cache` is **not** in use here, so `config:clear` is sufficient.

---

## 9. File Upload Security

All image uploads go through `App\Http\Controllers\Concerns\ValidatesImageUploads`
(see `ARCHITECTURE.md § 4`). Accepted: **`jpg,jpeg,png,gif,webp,avif`**, max **8MB**.

### SVG is deliberately excluded

An SVG is XML and can carry `<script>` or event handlers. Because uploads are served from
`public/` as same-origin content, a stored SVG would execute in the site's origin — stored
XSS with session access. Do **not** re-add it. Laravel 12 removed `svg` from the `image`
rule for this reason; re-enabling it requires an explicit `image:allow_svg`, which this
project does not use anywhere.

Before 2026-08-17 the banner rule *claimed* to accept SVG (`mimes:jpeg,png,jpg,gif,svg`)
but the `image` rule alongside it rejected them, so no SVG was ever stored — the exposure
was latent, not realised.

### Validation is by content, not filename

`mimes` checks the file's guessed type via `Symfony\Component\Mime\MimeTypes`, not the
client-supplied extension, so a `.txt` renamed to `.png` is rejected. Verified.
The `accept=".jpg,.jpeg,.png,.gif,.webp,.avif"` attribute on the inputs is a UX filter
only — never a control.

### Stored filenames are UUIDs

`storeImage()` names files `Str::uuid().'.'.$ext`, discarding the client filename entirely.
This removes path-traversal and overwrite vectors, and fixes an earlier bug where banners
used `time().$ext` so two uploads in the same second silently overwrote each other.

### Locations

- Image uploads: `public/{folder}/` — publicly accessible by URL (see `ARCHITECTURE.md § 7`)
- Invoice logo: `public/settings/` — **not** `storage/app/public/settings/`
- EFT screenshots: `public/payments/` — publicly accessible by URL

> ⚠️ **Unresolved**: EFT proof-of-payment screenshots are world-readable at a guessable
> path and may contain bank details. They should move behind an authenticated controller
> route. Tracked as a Phase 5 item.

### PHP-level upload ceiling

Measured on the live **web** SAPI 2026-08-17: `upload_max_filesize` / `post_max_size` /
`memory_limit` are all **1536M**, well above the 8MB application rule.
`public/.user.ini` is committed but is not honoured on Hostinger.

Check limits with a **web** request — `.user.ini` never applies to CLI, so `php -i` over
SSH reports different values. If a host's `post_max_size` ever drops below an upload, PHP
discards the entire `$_POST` including the CSRF token and the user gets a **419 Page
Expired** rather than a validation error.

---

## 10. HTTPS & Transport

- **Local**: HTTP (`http://jabulani-system.test`)
- **Production**: HTTPS enforced (`Strict-Transport-Security` header + Hostinger SSL)
- `AppServiceProvider` forces HTTPS URLs on production via `URL::forceScheme('https')`
