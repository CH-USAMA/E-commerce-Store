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
- File uploads: `mimes:jpg,jpeg,png,pdf|max:2048` (2MB limit)

---

## 8. Known Security Risks & Mitigations

| Risk | Current State | Recommended Action |
|:---|:---|:---|
| `APP_DEBUG=true` in production | ⚠️ Active | Set to `false` in `.env.production` immediately |
| `.env` contains SSH/DB password in comments | ⚠️ Found | Delete those comment lines from `.env` |
| `unsafe-inline` in CSP | ⚠️ Active | Tighten in Phase 5 with nonce-based CSP |
| Stripe keys in DB not encrypted at rest | ⚠️ Note | Consider encrypting `settings` values in Phase 5 |
| No 2FA for admin | 🔲 Missing | Consider adding TOTP in Phase 5 |
| No login attempt log | 🔲 Missing | Extend `ActivityLog` to record failed logins |

---

## 9. File Upload Security

- EFT screenshots: stored in `public/payments/` — publicly accessible by URL
- Invoice logo: stored in `storage/app/public/settings/` — accessed via `Storage::url()`
- No executable file types accepted (MIME type validation enforced)

---

## 10. HTTPS & Transport

- **Local**: HTTP (`http://jabulani-system.test`)
- **Production**: HTTPS enforced (`Strict-Transport-Security` header + Hostinger SSL)
- `AppServiceProvider` forces HTTPS URLs on production via `URL::forceScheme('https')`
