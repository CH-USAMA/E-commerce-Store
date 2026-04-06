# Project Memory — Jabulani Store

> **Last Updated**: 2026-04-06 (Paystack Integration Update)
> **Live URL**: https://store.jabulanigroupofcompanies.co.za
> **Local URL**: http://jabulani-system.test
> **Deployment**: Git push (local) → `git pull` on Hostinger (live). Uses `.env.production` on server.
> **Branch Status**: The "Compact User Portal Redesign & Checkout Hardening" experiment is isolated on branch `feature/ui-redesign-and-hardening`. The `master` branch remains representing the stable live production code.
---

## 📌 Context Index — Read These In Order

| File | Purpose |
|:---|:---|
| [SYSTEM_OVERVIEW.md](docs/memory/SYSTEM_OVERVIEW.md) | Goals, modules, role matrix, integrations |
| [DATABASE_SCHEMA.md](docs/memory/DATABASE_SCHEMA.md) | Every table, column, index, routing identifier |
| [ARCHITECTURE.md](docs/memory/ARCHITECTURE.md) | Middleware stack, controller→model map, config |
| [ORDER_FLOW.md](docs/memory/ORDER_FLOW.md) | Full order lifecycle with all branch conditions |
| [PRODUCT_FLOW.md](docs/memory/PRODUCT_FLOW.md) | Catalog, WMS states, CSV spec |
| [ADMIN_PANEL.md](docs/memory/ADMIN_PANEL.md) | Full route table, settings registry |
| [USER_PORTAL.md](docs/memory/USER_PORTAL.md) | Auth user journey, notifications, addresses |
| [SECURITY.md](docs/memory/SECURITY.md) | RBAC, middleware, UUID rules, known risks |
| [DEPLOYMENT.md](docs/memory/DEPLOYMENT.md) | Git push → Hostinger pull protocol |
| [TESTING_CHECKLIST.md](docs/memory/TESTING_CHECKLIST.md) | Per-feature smoke tests |
| [KNOWN_ISSUES.md](docs/memory/KNOWN_ISSUES.md) | Bugs, workarounds, gotchas |
| [FEATURE_MAP.md](docs/memory/FEATURE_MAP.md) | Feature → route → controller → model lookup |
| [CHANGELOG.md](docs/memory/CHANGELOG.md) | History of all major changes |
| [IMPROVEMENT_PLAN.md](docs/memory/IMPROVEMENT_PLAN.md) | Roadmap + AI agent task protocol |

---

## 🤖 AI Operating Contract (MUST READ FIRST)

### Rule 1 — Self-Documentation
After ANY architectural change (routes, models, migrations, controllers):
- Update the relevant `docs/memory/*.md` file immediately
- Add an entry to `docs/memory/CHANGELOG.md` with date + summary

### Rule 2 — Deployment Awareness
All changes are made locally first. The user **pushes to Git** and **pulls on Hostinger**.
- Never give instructions that require direct SSH file editing
- Always state: "Push to Git, then pull on Hostinger, then run: `php artisan ...`"
- Post-deploy commands: `php artisan migrate`, `php artisan config:clear`, `php artisan cache:clear`, `php artisan route:clear`, `php artisan view:clear`

### Rule 3 — Security Invariants (NEVER BREAK)
- Orders are ALWAYS routed by `uuid` — never integer `id`
- Users are ALWAYS routed by `uuid` — never integer `id`
- Products are ALWAYS routed by `slug` — never integer `id`
- Stores are ALWAYS routed by `slug`
- `role` is NOT in `User::$fillable` — assign via `$user->role = 'admin'; $user->save()`
- `payment_method` value `payfast` in DB = "Stripe Online" in UI (intentional alias)

### Rule 4 — Task Completeness
- Do NOT leave a migration without updating `DATABASE_SCHEMA.md`
- Do NOT add a route without checking `FEATURE_MAP.md` and `ADMIN_PANEL.md`
- Do NOT modify `Order` or `User` routing without verifying UUID consistency across ALL views
- Always run: `php artisan route:list` mentally against the route file before declaring done
### Rule 5 — Environment Awareness
| Setting | Local | Production |
|:---|:---|:---|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `true` ⚠️ (should be false) |
| `MAIL_MAILER` | `log` | `smtp` (Hostinger) |
| `DB_DATABASE` | `jabulanistore` | `u175002435_store` |
| `APP_URL` | `http://jabulani-system.test` | `https://store.jabulanigroupofcompanies.co.za` |
| Stripe keys | Not set in `.env` | Set in `settings` DB table |
| Google OAuth | In `.env` | In `.env.production` |

### Rule 6 — Test Before Declaring Done
Reference `docs/memory/TESTING_CHECKLIST.md` for every feature area.
Never declare a feature complete without running its checklist mentally.

### Rule 7 — Tone & Vocabulary (Premium Friendly)
The store MUST use simple, conventional e-commerce terminology. Avoid technical/military/logistics-heavy jargon.
- Use: **Order** (Not Requisition/Procurement/Ledger)
- Use: **Shipping/Delivery** (Not Logistics/Fulfillment/Dispatch)
- Use: **Payment** (Not Financial Node/Verification/Settlement)
- Use: **Status** (Not Intelligence/Analytics/Audit)
- Use: **Checkout** (Not Terminal/Authorization)
- Use: **Items** (Not Artifacts/Units/Inventory units)
- Keep labels friendly and shopper-focused (e.g., "Your Items" vs "Line Item Manifest").
### Rule 8 — Permission Integrity (PBAC)
All NEW admin routes or modules MUST be registered with the `permission:{name}` middleware.
- Sidebar links must be wrapped in `@if(auth()->user()->hasPermission('...'))`
- Check `docs/memory/SECURITY.md` for the list of existing module permissions.

---

> [!IMPORTANT]
> A project without up-to-date memory leads to architectural drift. Before starting any refactor,
> cross-reference `DATABASE_SCHEMA.md`, `SECURITY.md`, and `FEATURE_MAP.md`.
