# Improvement Plan & Future Roadmap

Current recommendations for enhancing the Jabulani Store scalability and user experience.

---

## 1. Short-Term Performance & Identity — ✅ COMPLETED

- ✅ **Advanced Query Caching**: All home page queries (categories, stores, brands, banners) cached with 1-hour TTL.
- ✅ **Audit Logging**: Custom `ActivityLog` model tracking all order status changes and payment confirmations.
- ✅ **Search Optimization**: Replaced `LIKE` queries with native MySQL `FULLTEXT` indexes on `products.name` and `products.description`.

---

## 2. GDPR, SEO & Inventory — ✅ COMPLETED

- ✅ **SEO & OpenGraph Meta Tags**: Dynamic metadata injected into products, blogs, and stores for premium social sharing.
- ✅ **GDPR/POPIA Guest Management**: Dedicated Admin panel for reviewing and manually purging guest PII (anonymization).
- ✅ **WMS Inventory States**: Enhanced database and branch dashboards for `Incoming`, `Reserved`, and `Damaged` stock tracking.

---

## 3. Premium Ops & User Portal — ✅ COMPLETED

- ✅ **Admin Master Stock Overview**: Comprehensive regional stock tracking view in Product edit.
- ✅ **Premium User Portal Redesign**: Dark mode, glassmorphic redesign of all user-facing dashboard and order views.
- ✅ **Bulk Inventory Import (WMS Aware)**: CSV tools updated for national inventory management at scale.

---

## 4. Total System Hardening & Branding — ✅ COMPLETED

- ✅ **UUID & Slug Transition**: Removed all incremental IDs from URLs. Primary and secondary routing now strictly use secure identifiers.
- ✅ **Security Middleware**: Implemented CSP, HSTS, and XSS/Frame protection.
- ✅ **Mass Assignment Protection**: Hardened User model and throttled auth/contact routes.
- ✅ **Dynamic Brand Wiring**: Site-wide footer and contact pages now pull from central Admin > Invoice settings.

---

## 5. Phase 5: Advanced Scalability (Future Roadmap)

### 🔲 A. Multi-Regional Pricing
- **Scope**: Database & Localization.
- **What**: Implement branch-specific pricing for products based on transport/logistics costs.
- **Impact**: Better margin control for inland vs coastal branches.

### 🔲 B. RESTful API Service Layer
- **Scope**: Backend API.
- **What**: Decouple the frontend logic from the controllers to provide a unified API for the future mobile app.
- **Impact**: Foundations for native Android/iOS procurement apps.

---

## Roadmap Overview (Long-Term)

| Item | Notes |
|:---|:---|
| **Mobile App (Flutter/React Native)** | Using the Phase 5 API to provide a native experience for heavy-duty procurement users. |
| **Next.js Headless Frontend** | Future migration to a modern JS framework for ultra-fast performance. |
