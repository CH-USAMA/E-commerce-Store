# Deployment Protocol — Local → Hostinger

---

## 1. Environment Summary

| Setting | Local | Hostinger (Production) |
|:---|:---|:---|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `true` ⚠️ Should be `false` |
| `APP_URL` | `http://jabulani-system.test` | `https://store.jabulanigroupofcompanies.co.za` |
| `.env` file used | `.env` | `.env.production` (renamed to `.env` on server) |
| Database | `jabulanistore` (local MySQL) | `u175002435_store` (Hostinger MySQL) |
| Mail | `log` driver (saved to log file) | SMTP via `smtp.hostinger.com:587` |
| Stripe keys | Not in `.env` — must be set via Admin > Settings in DB | Same — set via Admin > Settings in DB |
| Google OAuth | In `.env` | In `.env.production` |
| Queue | `sync` | `sync` (no queue worker) |
| Cache | `file` | `file` |
| Storage | `public` disk | `public` disk (symlink required) |

---

## 2. Deployment Workflow

### Every Session — Standard Code Push
```bash
# 1. On local machine — commit and push
git add .
git commit -m "feat: [description of change]"
git push origin main

# 2. On Hostinger (via SSH or Hostinger File Manager terminal)
cd /path/to/jabulani-store
git pull origin main

# 3. Run post-deploy commands on Hostinger
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### When Migrations Are Included
```bash
# After git pull on Hostinger:
php artisan migrate --force   ← --force required in production env
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### When composer.json Changes (New Package Added)
```bash
# After git pull on Hostinger:
composer install --no-dev --optimize-autoloader
php artisan config:clear
# ... other cache clears
```

---

## 3. What NEVER to Push to Git

| File/Folder | Reason |
|:---|:---|
| `.env` | Contains local DB and app key |
| `.env.production` | Contains live credentials — managed directly on server |
| `storage/logs/` | Log files — server-side only |
| `tmp/` | Temporary tinker scripts — not production code |
| `node_modules/` | npm packages — regenerated via `npm install` |
| `vendor/` | Composer packages — regenerated via `composer install` |
| `public/payments/` | EFT screenshots — server-side data, not code |
| `database/database.sqlite` | Local SQLite test DB |
| Any `*.log`, `*.sql` files | Data dumps and logs |

These should all be in `.gitignore`. Verify before each commit.

---

## 4. First-Time Server Setup (Reference Only)

```bash
# On Hostinger after first clone:
composer install --no-dev --optimize-autoloader
cp .env.production .env        # Copy production config
php artisan key:generate       # If key not already in .env
php artisan migrate --force
php artisan storage:link       # Create public/storage symlink
php artisan config:clear
php artisan cache:clear
```

---

## 5. Storage Link — NOT required

> ⚠️ **Corrected 2026-08-17.** This section previously said the symlink was "required for
> product images, banners, gallery, invoice logos". It is not.

`config/filesystems.php` overrides the `public` disk root to `public_path('')`, so uploads
are written **directly into `public/<folder>/`** and served at `/<folder>/{file}`. Nothing
resolves through `/storage/`.

```bash
php artisan storage:link      # harmless, but not needed by any upload path
```

The existing `public/storage` → `storage/app/public/` symlink is vestigial. `image_url()`
still probes `storage/app/public/` as a fallback, purely so any row predating the root
override keeps resolving — no current code writes there.

If uploads 404 after a deploy, the symlink is **not** the cause. Check instead that
`public/<folder>/` exists and is writable, and remember `public/.gitignore` contains a
blanket `*`, so uploaded files are never in git and never deploy — they live only on the
server and must be preserved across migrations.

---

## 6. Post-Deploy Verification Checklist

After every deploy to Hostinger:
- [ ] Home page loads without errors
- [ ] Products page loads with images
- [ ] Admin login works at `/login`
- [ ] Admin dashboard opens at `/admin/dashboard`
- [ ] Test order can be placed (or view existing orders)
- [ ] Check `storage/logs/laravel-YYYY-MM-DD.log` for any new errors

---

## 7. Rollback Procedure

If a deployment breaks something:
```bash
# On Hostinger:
git log --oneline -10          # Find last working commit hash
git checkout {commit-hash}     # Or:
git revert HEAD                # Revert last commit
git push                       # Push the revert
# Then on Hostinger: git pull + cache clear
```

---

## 8. Hostinger-Specific Notes

- **PHP Version**: Must be 8.2+ (configured in Hostinger control panel)
- **Database**: Hostinger MySQL — credentials in `.env.production`
- **Domain**: `store.jabulanigroupofcompanies.co.za`
- **SSL**: Managed by Hostinger (Let's Encrypt auto-renewal)
- **Document Root**: Must point to `/public` folder of the Laravel app
- **Composer**: Available on Hostinger via SSH at `/usr/local/bin/composer` (2.8.11); PHP 8.2.30

### SSH access

The live checkout is at
`~/domains/jabulanigroupofcompanies.co.za/public_html/store` (note: `store/` is a
subdirectory of the marketing site's `public_html`, alongside `agency/` and `POS/`).

```
ssh -p 65002 u175002435@82.25.96.26
```

Password auth and publickey are both accepted. Add keys under
**hPanel → Websites → Advanced → SSH Access**. If SSH is unavailable, hPanel's
**Advanced → GIT** page can pull without a shell.

### Live git remote differs from the dev remote

Measured 2026-08-17 — these are **two different GitHub repositories**:

| | Branch | Remote |
|---|---|---|
| Live | `main` | `git@github.com:JabulaniGroup/Jabulani-E-commerce-Store.git` |
| Dev | `master` | `https://github.com/CH-USAMA/E-commerce-Store.git` |

Their histories share a common ancestor, so they merge cleanly, but **pushing to the
dev repo does not deploy anything**. To deploy from the dev repo, merge live's branch
locally first so the live pull is a pure fast-forward:

```bash
# locally — merge production's branch in, resolve, test
git remote add live ssh://u175002435@82.25.96.26:65002/home/u175002435/domains/jabulanigroupofcompanies.co.za/public_html/store
git fetch live main && git merge live/main
git push origin master

# on live — --ff-only can only fast-forward or fail safely, never half-apply
git branch -f backup-pre-<change> HEAD          # rollback point
git fetch https://github.com/CH-USAMA/E-commerce-Store.git master
git merge --ff-only FETCH_HEAD
php artisan migrate --force                     # only if a migration landed
php artisan route:clear                         # MANDATORY — see below
php artisan view:clear
php artisan config:clear
git push origin main                            # keep the canonical repo in sync
```

### `route:clear` is mandatory — this has already caused a production 500

Live carries a cached `bootstrap/cache/routes-v7.php`. **While that file exists,
`routes/web.php` is never read**, so a newly deployed route simply does not exist as far as
the app is concerned. Every `route('new.name')` call then throws `Route [x] not defined`,
which is a **500 on every page referencing it**.

Happened 2026-08-17: `admin.banners.move` was deployed and `config`/`view`/`cache` were all
cleared — but not `route`. `/admin/banners` returned 500 for about 12 minutes. The deploy
output gave no hint; the only evidence was `storage/logs/laravel-YYYY-MM-DD.log`.

The clears are cheap and idempotent, so run all of them every time rather than trying to
decide which are needed.

### Diagnosing a production 500

`APP_DEBUG=false` (correctly), so the browser shows a generic page. The detail is in
`storage/logs/laravel-YYYY-MM-DD.log` — daily rotation, 14-day retention:

```bash
f="storage/logs/laravel-$(date +%F).log"
grep -n "production.ERROR" "$f" | tail -3          # headline messages
ln=$(grep -n "production.ERROR" "$f" | tail -1 | cut -d: -f1)
sed -n "${ln},$((ln+8))p" "$f"                     # that entry's first frames
```

Read the **headline** rather than the trace tail: `tail` alone lands you in the middle of a
60-frame stack and shows middleware, not the cause.

### Upload limits — Hostinger already exceeds what the app needs

Measured on the live **web** SAPI 2026-08-17 (CLI `php -i` shows different values, and
`.user.ini` never applies to CLI — you must check via a web request):

```
upload_max_filesize = 1536M    post_max_size = 1536M
memory_limit        = 1536M    max_execution_time = 360
```

`public/.user.ini` is committed but **is not honoured on this host** — its 16M/24M values
did not take effect. That is fine: the binding limit is Laravel's own `max:8192` (8MB) in
`ValidatesImageUploads`, which produces a readable validation error. The file is kept only
so a host with stingy defaults cannot reintroduce the discarded-POST → 419 failure; its
16M ceiling still sits above the 8MB rule, so it can never downgrade this app.

### `composer dump-autoload` is no longer mandatory

`app/helpers.php` is listed in `composer.json`'s `autoload.files`, but that list is baked
into `vendor/composer/autoload_files.php` and only refreshed by a dump — and `vendor/` is
gitignored. `AppServiceProvider::register()` therefore also `require_once`s the file, so a
bare `git pull` is sufficient. Run the dump anyway when you have a shell (it is the
cleaner state), but a deploy without it will not break.

New *classes* never need a dump: `optimize-autoloader` is on but
`classmap-authoritative` is not, so PSR-4 resolves classes missing from the classmap.
