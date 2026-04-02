# Improvement Plan & Future Roadmap

---

## Completed Phases

### ✅ Phase 1 — Performance & Identity
- Advanced query caching (1-hour TTL on homepage queries)
- Audit logging (`ActivityLog` model)
- FULLTEXT search on `products.name` + `products.description`

### ✅ Phase 2 — GDPR, SEO & Inventory
- SEO + OpenGraph meta tags on products, blogs, stores
- GDPR/POPIA guest management panel
- WMS inventory states (`incoming`, `reserved`, `damaged`)

### ✅ Phase 3 — Premium Ops & User Portal
- Admin master stock overview
- Premium dark-mode user portal redesign
- Bulk CSV inventory import/export (WMS-aware)

### ✅ Phase 4 — Security Hardening & Branding
- UUID + Slug routing (no integer IDs in URLs)
- Security middleware (CSP, HSTS, XSS, Frame, Referrer)
- Mass assignment protection
- Dynamic site branding via admin settings
- Daily log rotation (14-day retention)
- Google OAuth + email verification gate
- Stripe checkout integration

---

## Phase 5 — Advanced Scalability (Current Roadmap)

### 🔲 A. Fix `APP_DEBUG=true` in Production
- **File**: `.env.production`
- **Action**: Set `APP_DEBUG=false`
- **Impact**: Prevents stack trace exposure to end users
- **Effort**: 1 line change, immediate

### 🔲 B. Tighten CSP (Remove `unsafe-inline`)
- **File**: `app/Http/Middleware/SecurityHeaders.php`
- **Action**: Implement nonce-based CSP for all inline scripts/styles
- **Impact**: Eliminates XSS vector via injected scripts
- **Effort**: Medium — requires updating all Blade views to use `@nonce`

### 🔲 C. Multi-Regional Pricing
- **Scope**: New DB column `product_store_stocks.price_override`
- **Action**: Allow branch-specific pricing on top of global product price
- **Impact**: Better margin control for inland vs coastal branches
- **Effort**: Medium — new migration + UI in product edit

### 🔲 D. RESTful API Service Layer
- **Scope**: New `/api/v1/` route group + API controllers
- **Action**: JSON endpoints for products, orders, cart, and auth
- **Impact**: Foundation for mobile app (Flutter/React Native)
- **Effort**: High — separate controllers, Sanctum token auth, versioning

### 🔲 E. Encrypt Settings Values at Rest
- **Scope**: `settings` DB table
- **Action**: Use Laravel `encrypt()`/`decrypt()` for Stripe keys and sensitive values
- **Impact**: Keys not readable if DB is compromised
- **Effort**: Low — accessor/mutator on `Setting` model

### 🔲 F. Stripe Webhook Handler
- **Scope**: New route `POST /webhook/stripe`
- **Action**: Handle `payment_intent.succeeded` to update order status server-side
- **Impact**: More reliable than redirect-based payment confirmation
- **Effort**: Medium — new controller, webhook secret validation

### 🔲 G. Admin 2FA (TOTP)
- **Scope**: Admin login only
- **Action**: Add Google Authenticator-style TOTP for admin accounts
- **Impact**: Prevents unauthorized admin access if credentials are stolen
- **Effort**: Medium — use `pragmarx/google2fa-laravel` or similar

---

## Long-Term Roadmap

| Item | Notes |
|:---|:---|
| **Mobile App** (Flutter/React Native) | Using Phase 5 API. Native B2B ordering experience. |
| **Next.js Headless Frontend** | Decouple from Blade templates for ultra-fast SSR. |
| **Multi-Currency Support** | Beyond ZAR — for future regional expansion. |
| **Automated Stock Reorder Alerts** | Email admin when stock < threshold. |
| **Customer Loyalty Points** | Points earned per purchase, redeemable at checkout. |

---

## AI Agent Task Protocol

When adding any new feature, follow this sequence:

### Step 1 — Research
Before writing any code:
1. Read `FEATURE_MAP.md` — does a similar route/controller already exist?
2. Read `DATABASE_SCHEMA.md` — does any existing table support this, or is a migration needed?
3. Read `SECURITY.md` — does this feature need auth, role, or UUID protection?

### Step 2 — Plan
- If schema changes needed → write migration first
- If new controller needed → plan route first, check for naming consistency
- If new model field → check `$fillable` to keep secure

### Step 3 — Execute
- Write migration → model → controller → routes → views (in that order)
- Run `php artisan route:list` to verify route registration
- Test against `TESTING_CHECKLIST.md`

### Step 4 — Document
After every change:
1. Update `docs/memory/FEATURE_MAP.md` with new route entry
2. Update `docs/memory/DATABASE_SCHEMA.md` if schema changed
3. Update `docs/memory/CHANGELOG.md` with dated entry
4. If a bug was found and fixed: add to `docs/memory/KNOWN_ISSUES.md`

### Step 5 — Deploy
1. Commit + push to Git
2. Instruct user: `git pull` on Hostinger
3. If migration: `php artisan migrate --force`
4. Always: `php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear`
