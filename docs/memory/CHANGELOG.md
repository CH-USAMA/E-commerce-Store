## [2026-04-01] — Phase 4: Total System Hardening & Dynamic Branding
### Added
- **ID Obfuscation (UUID/Slug Migration)**: Implemented UUID-based route binding for `Users` and `Orders`, and Slug-based binding for `Products`, `Stores`, `Categories`, and `Brands`. This completely hides incremental database IDs from URLs.
- **Security Middleware**: Implemented `SecurityHeaders` middleware providing a strict Content Security Policy (CSP), HSTS, X-Frame-Options (SAMEORIGIN), and X-Content-Type-Options (nosniff).
- **Rate Limiting**: Applied `throttle` middleware to `Login`, `Register`, and `Contact` forms to prevent brute-force and spam attacks.
- **HTTPS Enforcement**: Globally enforced HTTPS for all URLs in production via `AppServiceProvider`.
- **Dynamic Site Branding**: Wired the frontend footer, contact page, and WhatsApp buttons to pull from the central "Invoice Settings" in the admin panel.
- **Data Repair & Integrity**: Executed batch repair scripts to ensure 100% UUID coverage for existing database records and seeded official Jabulani Group contact/EFT details.

### Hardened
- **Mass Assignment**: Removed `role` from `User` model fillable attributes to prevent privilege escalation.
- **Route Model Binding**: Audited and fixed all Blade templates to ensure they use the new secure identifiers.
- **Asset Security**: Configured CSP to whitelist external CDNs (Tailwind, FontAwesome, Alpine.js) while blocking unauthorized third-party scripts.

### Fixed [Post-Hardening Repairs]
- **Admin Order Processing**: Resolved a critical regression where "Confirm Payment" and "Update Status" were failing due to route model binding mismatches after the UUID migration.
- **Notification Data Integrity**: Executed a backend repair script (`tmp/repair_notifications_uuids.php`) to migrate legacy integer-based URLs in the `notifications` table to their new secure UUID equivalents.
- **Social Login Models**: Patched the `User` model with PHPDoc property declarations to resolve IDE linting errors and improve static analysis for Google Socialite tokens.

## [2026-04-01] — Phase 3: Premium Operations & User Portal Redesign
### Added
- **Dedicated User Layout**: Created `layouts/user.blade.php` with a sleek glassmorphic sidebar and premium typography for an app-like authenticated experience.
- **WMS-Aware CSV Import**: Enhanced `ProductController@import` to handle `Physical`, `Incoming`, `Reserved`, and `Damaged` stock columns mapped to specific stores.
- **Enhanced CSV Export**: `ProductController@export` now outputs one row per store-stock relationship for easier bulk updates of national inventory.
- **Master Regional Stock View**: Redesigned Product Edit view with a "Regional Inventory Tracking" table managing stocks across all branches from a single screen.

### Changed
- **Premium User Dashboard**: Replaced legacy dashboard with a high-contrast "Dark Mode Premium" interface featuring a Welcome Hero and detailed Procurement Analytics (`Total Spent`, `Active Orders`).
- **Modernized Order History**: Overhauled `user/orders/index.blade.php` with a "Transactional Ledger" UI and glassmorphic pagination.
- **Luxury Order Details**: Finalized `user/orders/show.blade.php` with a high-end logistics pipeline visualizer and line-item manifest.
- **Core Performance**: `DashboardController` now pre-calculates cumulative transactional value for real-time account equity reporting.

## [2026-03-31] — Phase 2: SEO, GDPR & WMS Inventory
### Added
- **GDPR Guest Management**: Dedicated Admin panel for reviewing and manually purging guest PII (anonymization).
- **Artisan Purge Tool**: Created `app:purge-guest-order-data` for batch anonymization of PII after 7 years.
- **Dynamic SEO Framework**: Master layout now supports dynamic OpenGraph meta tags with specific overrides for products, blogs, and stores.
- **WMS Database Schema**: Added `incoming`, `reserved`, and `damaged` columns to `product_store_stocks`.
- **Branch Stock Manager**: Updated branch inventory dashboard with unified 4-point stock management.

## [2026-03-31] — Improvement Plan Execution (Phase 1)
### Added
- **Invoice Settings**: Comprehensive admin panel for dynamic configuration of company info, logos, and EFT bank accounts.
- **Project Memory Documentation**: Centralized `docs/memory/` directory for AI context and onboarding.
- **AI Entry Point**: Root-level `MEMORY.md` for instructing future automation agents.

### Fixed
- **Order Invoice Route**: Resolved `admin.orders.invoice` missing route error.
- **Database Performance**: Implemented missing indexes on `addresses` for postal code and address type filtering.

### Changed
- **Customer UI**: Redesigned Order Details view (`show.blade.php`) with high-contrast labels and information for improved readability on dark backgrounds.
- **PDF Template**: Refactored `invoice.blade.php` to use dynamic branding and bank accounts instead of hardcoded strings.
