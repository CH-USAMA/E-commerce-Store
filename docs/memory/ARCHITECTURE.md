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
| `/admin` | `admin.` | `auth,role:admin` | Admin portal |
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

| Asset | Storage Location | Access URL |
|:---|:---|:---|
| Product images | `public/storage/products/` | `/storage/products/{file}` |
| Payment screenshots (EFT proof) | `public/payments/` | `/payments/{file}` |
| Invoice logo | `storage/app/public/settings/` | via `Storage::url()` |
| Banners | `public/storage/banners/` | `/storage/banners/{file}` |
| Gallery items | `public/storage/gallery/` | `/storage/gallery/{file}` |
| Daily logs | `storage/logs/laravel-YYYY-MM-DD.log` | Server only |
