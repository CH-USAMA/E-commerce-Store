# Project Memory — Jabulani Store

> **Last Updated**: 2026-08-24 (Product sizes/variants — see `PRODUCT_FLOW.md`; Specials — `ADMIN_PANEL.md § 1c`)
> **Commerce Status**: ⚠️ **Inquiry mode** — `hide_pricing = '1'`, no payment gateway
> configured, enquiries route to WhatsApp. See Rule 10 before re-enabling pricing.
> **Live URL**: https://store.jabulanigroupofcompanies.co.za
> **Local URL**: http://jabulani-system.test
> **Live path**: `~/domains/jabulanigroupofcompanies.co.za/public_html/store` (a *subdirectory* of the marketing site's `public_html`, alongside `agency/` and `POS/`)
> **Deployment**: ⚠️ **Two different repositories** — see Rule 2. Uses the server's own `.env`.
> **Branch Status**: The "Compact User Portal Redesign & Checkout Hardening" experiment is isolated on branch `feature/ui-redesign-and-hardening`. Dev work happens on `master`; **live runs `main` in a different repo**.
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
| [DEPLOYMENT.md](docs/memory/DEPLOYMENT.md) | Git push → Hostinger pull protocol; **check live for drift first — Rule 11** |
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

⚠️ **Corrected 2026-08-17 after measuring the live server.** Dev and live are **two
different GitHub repositories**. Pushing to the dev repo deploys nothing.

| | Branch | Remote |
|:---|:---|:---|
| Dev (local) | `master` | `https://github.com/CH-USAMA/E-commerce-Store.git` |
| **Live** | `main` | `git@github.com:JabulaniGroup/Jabulani-E-commerce-Store.git` |

Their histories share a common ancestor, so they merge cleanly — but live may hold commits
dev does not (production hotfixes edited on the server). **Never tell the user to just
"pull on live"** without first checking `git log` on both sides: a blind pull can drop them
into conflict resolution on production.

**Correct procedure** — merge locally, then let live fast-forward:

```bash
# locally: bring production's branch in, resolve, TEST
git remote add live ssh://u175002435@82.25.96.26:65002/home/u175002435/domains/jabulanigroupofcompanies.co.za/public_html/store
git fetch live main && git merge live/main
./scripts/deploy-preflight.sh          # read-only: both sides' state + the real payload
git add <named files>                  # never `git add .` — see Rule 12
php artisan test && git push origin master

# on live
git branch -f backup-pre-<change> HEAD                                  # rollback point
git fetch https://github.com/CH-USAMA/E-commerce-Store.git master
git merge --ff-only FETCH_HEAD                                          # can only ff or fail
php artisan migrate --force                                             # only if a migration landed
php artisan route:clear                                                 # ← MANDATORY, see below
php artisan view:clear
php artisan config:clear
git push origin main                                                    # keep canonical in sync
```

> ⚠️ **`route:clear` is not optional.** Live carries a cached
> `bootstrap/cache/routes-v7.php`. While it exists, `routes/web.php` is **not read at all**,
> so any newly added route is invisible and every `route('new.name')` call throws
> `Route [x] not defined` — a **500 on every page that references it**.
>
> This happened on 2026-08-17: the `admin.banners.move` route was deployed, config/view/cache
> were cleared but `route:clear` was not, and `/admin/banners` returned 500 for ~12 minutes.
> Nothing in the deploy output hinted at it — the failure only surfaced when the page was
> opened.
>
> Run `route:clear` on **every** deploy that touches `routes/*.php`. Cheap and idempotent, so
> simply always run it. The same trap applies to `config:clear` when `.env` or `config/*`
> changed, and `view:clear` for Blade edits.

`--ff-only` is the safety property: it either fast-forwards cleanly or refuses, never
half-applies a merge on production.

- SSH **does** work: `ssh -p 65002 u175002435@82.25.96.26` (publickey or password; add keys
  under hPanel → Websites → Advanced → SSH Access). The older "never give instructions that
  require SSH" guidance was based on a false assumption. hPanel → Advanced → GIT can also
  deploy with no shell.
- `composer` is at `/usr/local/bin/composer` (2.8.11); PHP 8.2.30.
- Post-deploy commands: `php artisan migrate`, `config:clear`, `cache:clear`, `route:clear`,
  `view:clear`. `composer dump-autoload` is **no longer mandatory** — see `DEPLOYMENT.md`.
- `git pull` never touches `.env`, `storage/` or `vendor/` (all gitignored).
- ⚠️ **`public/.gitignore` contains a blanket `*`**, so any *new* file under `public/` is
  invisible to git and will never deploy. Add an explicit `!filename` exception. This is why
  `public/css/design-system.css` once 404'd in production.

### Rule 3 — Security Invariants (NEVER BREAK)
- Orders are ALWAYS routed by `uuid` — never integer `id`
- Users are ALWAYS routed by `uuid` — never integer `id`
- Products are ALWAYS routed by `slug` — never integer `id`
- Stores, **Categories and Brands** are ALWAYS routed by `slug`
- **Pass the model to `route()`, never `->id`** — `route('admin.products.edit', $product)`.
  Passing `->id` does not merely leak an integer: because these models bind by slug/uuid it
  resolves as `where('slug','1')` and returns a **hard 404**. This was violated across
  products/categories/brands until 2026-08-17 and made the product edit form unsavable.
- `role` is NOT in `User::$fillable` — assign via `$user->role = 'admin'; $user->save()`
- `payment_method` value `payfast` in DB = "Stripe Online" in UI (intentional alias)
- **Never render a stored image path by hand.** Use `image_url()` (or `image_path()` for
  DomPDF) from `app/helpers.php`. Three storage schemes are live at once and only the helper
  resolves all of them — see `ARCHITECTURE.md § 7`.
- **Never combine the `image` and `mimes` rules.** In Laravel 12 their allow-lists differ
  over SVG, producing a rule whose own error message contradicts it. Use the
  `ValidatesImageUploads` trait.
- **SVG is not an accepted upload type** — it can carry embedded JS and is served
  same-origin from `public/`. See `SECURITY.md § 9`.
- The `public` disk sets `'throw' => false`; **always check whether a write returned
  `false`** or the record saves with an empty path while reporting success.

### Rule 4 — Task Completeness
- Do NOT leave a migration without updating `DATABASE_SCHEMA.md`
- Do NOT add a route without checking `FEATURE_MAP.md` and `ADMIN_PANEL.md`
- Do NOT modify `Order` or `User` routing without verifying UUID consistency across ALL views
- Always run: `php artisan route:list` mentally against the route file before declaring done
### Rule 5 — Environment Awareness
| Setting | Local | Production |
|:---|:---|:---|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | **`false`** ✅ (set 2026-08-17 — keep it false; read `storage/logs/` to diagnose) |
| `MAIL_MAILER` | `log` | `smtp` (Hostinger) |
| `DB_DATABASE` | `jabulanistore` | `u175002435_store` |
| `APP_URL` | `http://jabulani-system.test` | `https://store.jabulanigroupofcompanies.co.za` |
| Stripe keys | Not set in `.env` | Set in `settings` DB table |
| Google OAuth | In `.env` | In `.env.production` |
| `upload_max_filesize` / `post_max_size` | **2M / 8M** | **1536M / 1536M** |
| `max_execution_time` | `0` (unlimited) | `360` |
| PHP / Composer | — | 8.2.30 / 2.8.11 |

⚠️ **Local PHP limits are far stricter than production.** An upload that fails locally with
419 Page Expired or "failed to upload" may work fine on live. Never conclude a size problem
is a production problem without measuring the live **web** SAPI — `.user.ini` does not apply
to CLI, so `php -i` over SSH reports different numbers than a browser request.

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

### Rule 9 — Measure the Live Server, Don't Assume It
Several long-standing entries in these docs turned out to be wrong when finally checked
against production on 2026-08-17 (storage paths, upload limits, the git remote, whether SSH
works). Before diagnosing an environment-shaped bug:
- Check PHP limits via a **web** request, not `php -i` over SSH — `.user.ini` does not apply to CLI
- Check `git remote -v` **and** `git branch` on the live checkout before telling anyone to pull
- Check `git status` on live before pulling — production may hold commits dev does not
- Confirm the buggy code is actually deployed before claiming a fix is a production fix
- When a doc contradicts a measurement, **fix the doc in the same change**

### Rule 10 — Inquiry Mode Is Load-Bearing Security (as of 2026-08-17)
The store runs with `hide_pricing = '1'`; there is **no payment gateway configured** and
enquiries go to WhatsApp. `CheckPricingEnabled` redirects all `/cart/*` and `/checkout/*`
traffic to `/contact`, and that middleware is currently the only thing keeping two serious
payment defects unexploitable.

**Before setting `hide_pricing = 0`, the 🔴 items in `KNOWN_ISSUES.md` must be fixed** —
the Stripe bypass in `CartController::orderSuccess()` and the client-controlled upload
filename in `processCheckout()`. Re-enabling pricing re-arms both immediately.

Note `/order-success` and `/track-order` are deliberately **unguarded** so existing orders
keep working, so the Stripe bypass is already partly live against pre-existing
`pending` + `payfast` orders.


### Rule 11 — Check Live For Drift BEFORE You Start (as of 2026-08-22)

**A collaborator edits files directly on the production server.** Confirmed by the owner on
2026-08-22. Treat live as a checkout that may hold work existing in no repository, and check
it at the *start* of any task that will touch git — not when a merge finally refuses.

```bash
ssh -p 65002 u175002435@82.25.96.26   'cd ~/domains/jabulanigroupofcompanies.co.za/public_html/store && git status --porcelain && git log -3 --oneline'
```

Untracked `.env.backup-*` files are normal and should stay untracked. Anything else —
a ` M` or ` D` line — is undeclared production drift. Do not run `git checkout`, `git reset`,
`git stash` or `git clean` on live until it is dealt with.

**Dealing with it, losslessly:**

1. **Back up first, always**: `git diff > ~/deploy-backups/<name>.patch` plus a real file copy.
   Set a rollback branch: `git branch -f backup-pre-<change> HEAD`.
2. **If your incoming commit does not touch those paths**, `git merge --ff-only` straight
   through — git leaves unrelated dirty files alone. Verify the paths do not overlap first.
3. **If it does touch them, or you simply want the drift captured**, adopt it into git:
   `scp` the file down, commit it on `master`, push, then on live prove the working copy
   matches what you committed **before** cleaning:

   ```bash
   # local
   git rev-parse HEAD:resources/views/home.blade.php
   # live — these two hashes MUST be identical
   git hash-object resources/views/home.blade.php
   ```

   Matching hashes are what make `git checkout -- .` provably lossless. Without that check it
   is a guess, and a wrong guess is unrecoverable. Then clean the tree and fast-forward.

Deleting files the drift removed? Prove they are unreferenced first — grep `app/`,
`resources/`, `routes/` **and** query the production database (`categories.image`,
`banners.image`/`image_mobile`, `products.image`, `stores.image`). Use exact paths, not
`LIKE '%name%'`: a loose scan for the 2026-08-22 image deletions returned 11 false hits that
were all unrelated `images/products/*.png` uploads.

History: on 2026-08-22 live was carrying a `home.blade.php` mobile hero rework (+62/−40) and
8 `public/images/*.webp` deletions that had survived **two** deploys uncommitted. Adopted as
`4553dd1`. See `CHANGELOG.md` for the full procedure.

### Rule 12 — Assume Another Session Is Working Too (as of 2026-08-24)

The owner runs **several Claude sessions at once** and commits and deploys from any of them.
Local `master` and live both move *while you work*. This is normal, not an anomaly — design
every git step to survive it.

- **Stage by name. Never `git add .`.** On 2026-08-24 the working tree held an unfinished
  product-variants feature plus three unrun migrations while a one-line `.htaccess` fix was
  being deployed. `git add .` would have put all of it on production.
- **Re-read both sides immediately before merging**, not only at the start of the task. That
  same day a second session's commit landed *between* two commits of the first, silently
  changing what the pending fast-forward would ship.
- **Inspect the payload, not your intention.** `git log --oneline live/main..HEAD` *is* the
  deploy. If it holds commits you did not write, stop and ask before shipping them — and if
  it holds migrations, they need `php artisan migrate --force` after the merge.
- `./scripts/deploy-preflight.sh` does all of the above read-only, in one command. Run it
  before every deploy.

Never cherry-pick your commit onto live to get past someone else's work: that diverges live's
history and every later `--ff-only` fails. Either wait, or deploy their work deliberately —
having read its diff.

---

> [!IMPORTANT]
> A project without up-to-date memory leads to architectural drift. Before starting any refactor,
> cross-reference `DATABASE_SCHEMA.md`, `SECURITY.md`, and `FEATURE_MAP.md`.
