# System Overview — Jabulani Store

> **Stack**: Laravel 12, PHP 8.3, MySQL/MariaDB, Alpine.js, Tailwind CSS (frontend), Vanilla CSS (admin Carbon Pro)
> **Live**: https://store.jabulanigroupofcompanies.co.za | **Host**: Hostinger

---

## 1. Purpose
Multi-branch B2B/B2C eCommerce platform for the Jabulani Group of Companies (South Africa).
Bridges online ordering with physical branch inventory. Key differentiator: branch-specific stock,
geolocation-based store assignment, and hybrid EFT + Stripe payment.

---

## 2. Core Modules

### 🛒 Product Catalog
- Hierarchical categories (`categories.parent_id` self-join)
- Brand management (`brands` table)
- VAT-inclusive pricing (15% South African VAT stored on `order_items.vat`)
- Products routed by `slug`, identified internally by UUID
- WMS states: `physical`, `incoming`, `reserved`, `damaged` — on `product_store_stocks`

### 📦 Inventory & Logistics (WMS)
- Stock is **branch-specific** — no global stock concept
- `product_store_stocks` is the Many-to-Many bridge: `product_id ↔ store_id`
- WMS columns: `quantity` (physical), `incoming_quantity`, `reserved_quantity`, `damaged_quantity`
- Bulk management via CSV import/export (`ProductController@import` / `@export`)

### 💳 Order Lifecycle
- Guest and Auth checkout supported
- Payment methods: EFT (manual, requires admin verification) and Stripe (card, auto)
- **Important**: `payment_method` DB value `payfast` = "Stripe Online" in UI (legacy alias)
- Fulfillment: Pickup or Delivery (radius-limited via Haversine formula, default 300km)
- UUID-based routing for all order URLs (`orders.uuid`)

### 👤 Customer Portal
- Auth users: dashboard, order history, notifications, address book
- Email verification required before checkout
- Google OAuth (Socialite) supported — auto-assigns `role = 'user'`
- Profile completion gate via `profile.complete` middleware

### 🛠️ Administrative System
- Carbon Pro dark-mode dashboard
- 3 roles: `admin` (full), `manager`/`branch` (branch ops only), `user` (customer)
- Settings managed in DB `settings` table (key-value store)

---

## 3. Role-Based Access Matrix

| Feature | Admin | Manager | User | Guest |
|:---|:---:|:---:|:---:|:---:|
| Admin Dashboard | ✅ | ❌ | ❌ | ❌ |
| All Orders | ✅ | ❌ | ❌ | ❌ |
| Branch Orders | ✅ | ✅ | ❌ | ❌ |
| Confirm EFT Payment | ✅ | ❌ | ❌ | ❌ |
| Product CRUD | ✅ | ❌ | ❌ | ❌ |
| User Dashboard | ❌ | ❌ | ✅ | ❌ |
| View Own Orders | ❌ | ❌ | ✅ | ❌ |
| Checkout | ✅ | ✅ | ✅ | ✅ |
| Browse Products | ✅ | ✅ | ✅ | ✅ |
| Marketing Push | ✅ | ❌ | ❌ | ❌ |
| System Settings | ✅ | ❌ | ❌ | ❌ |

Middleware enforcement:
- `role:admin` → Admin group
- `role:manager` → Branch group (prefix: `/branch`)
- `role:user` + `verified` + `profile.complete` → User portal (prefix: `/user`)

---

## 4. Third-Party Integrations

| Integration | Package | Purpose | Config Location |
|:---|:---|:---|:---|
| Stripe | `stripe/stripe-php ^19.4` | Card payments | `settings` DB table (keys: `stripe_enabled`, `stripe_secret_key`, `stripe_public_key`) |
| Google OAuth | `laravel/socialite ^5.24` | Social login | `.env.production` (`GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`) |
| PDF Invoice | `barryvdh/laravel-dompdf ^3.1` | Invoice generation | `resources/views/pdf/invoice.blade.php` |
| HTTP Client | `guzzlehttp/guzzle ^7.2` | External requests | - |
| Tinker | `laravel/tinker ^2.8` | Dev console | - |

---

## 5. Business Invariants (AI Must Never Violate)

1. Orders MUST be routed by `uuid` — never `id`
2. Stock is ALWAYS branch-specific — never global
3. EFT orders require manual admin confirmation (`OrderController@confirmPayment`)
4. Stripe orders auto-update to `processing` on success callback
5. `role` column is NOT mass-assignable — always set directly
6. Delivery radius is enforced both client-side (JS) and server-side (Haversine in `CartController`)
7. Email verification is required for auth user checkout (not for guests)
8. Notifications to admins use DB channel with UUID-based order URLs
