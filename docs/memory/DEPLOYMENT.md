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

## 5. Storage Link

The `public/storage` symlink must exist on the server:
```bash
php artisan storage:link
```
This links `public/storage` → `storage/app/public/`.
Required for: product images, banners, gallery, invoice logos.

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
- **Composer**: Available on Hostinger via SSH
