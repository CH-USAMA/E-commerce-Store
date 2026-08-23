# Changelog — Jabulani Store

> Format: `## [YYYY-MM-DD] — Summary`
> Add newest entries at the top.

---

## [2026-08-24] — Product Sizes (variants) End to End

**Type**: Feature (Admin + Storefront + Cart/Orders)
**Reported as**: "I have few products that has different sizes ... should i add each variation
of size for each product manually or we can have something like user can select product and
than size ... for example we have lintel and some dummy sizes like 1,2 1,5 4,6 4,8"
**Files Changed**: migrations `create_product_variants_table`,
`add_has_variants_to_products_table`, `add_variant_to_order_items_table` (new),
`app/Support/Cart.php` (new), `ProductVariant.php` (new),
`admin/products/partials/variants.blade.php` (new), `Product.php`, `OrderItem.php`,
`CartController.php`, `HomeController.php`, `Admin/ProductController.php`,
`User/OrderController.php`, `RecentlyViewed.php`, `admin/products/{create,edit}.blade.php`,
`frontend/{product-single,products,cart,checkout}.blade.php`,
`frontend/partials/{product_card,price_or_contact}.blade.php`,
`frontend/orders/track.blade.php`, `admin/orders/show.blade.php`,
`user/orders/show.blade.php`, `branch/orders/show.blade.php`, `pdf/invoice.blade.php`,
`emails/order-confirmed.blade.php`, plus `ARCHITECTURE`, `DATABASE_SCHEMA`, `PRODUCT_FLOW`,
`ORDER_FLOW`, `FEATURE_MAP`, `TESTING_CHECKLIST`

Both existing workarounds were in the live catalog and both failed: **"Lintels from 1M to 6M"**
was one product with the range in its name (no price could be shown, nothing could be picked),
and **"Paint Brush 50MM / 100MM / 150MM"** were three products that sort 100, 150, 50
alphabetically. With 292 products, one lintel in six lengths meant six cards in the grid and
six descriptions to keep in sync.

- **`product_variants`** — label, price, optional code, active flag, explicit order. Unique
  `(product_id, label)`; `(product_id, sort_order)` index.
- **Deliberately not stock-bearing.** `product_store_stocks` held 60 rows across 292 products
  and **every one was zero**, so per-store stock is not maintained. Variant-level stock would
  have meant threading `variant_id` through the stock table, CSV importer and WMS screens to
  serve nothing.
- **`Product::offersVariants()`** requires the flag AND a live size, so a fully-deactivated
  range degrades to a simple product instead of rendering an empty picker. `display_price` is
  the cheapest size; `hasPriceRange()` decides whether to print "From".
- **Cart keys became composite** — `"12"` or `"12:5"`. `Cart::parse()` reads both, so carts
  that already existed (live sessions, and `users.cart_data`, which survives logout) keep
  working with no data migration and no lost baskets.
- **`Cart::lines()` is the single resolver** for the cart page, checkout summary, order writer
  and nearest-store rule. Four copies would have been four chances to price a sized line off
  `products.price`.
- **Price tampering is blocked**: a variant must belong to the product and be active, and a
  product that offers sizes cannot be added without one.
- **Orders snapshot the size** (`variant_id` nullOnDelete + `variant_label`), same reasoning as
  `order_items.price` being a copy. Every order view renders `$item->display_name`.
- **Unticking "has sizes" parks the sizes**, it does not delete them — a seasonal range comes
  back intact.

### Bug fixed in passing

The product search `orWhere('sku', ...)` was **not grouped**, so it OR-ed past the `active()`
and category filters — a SKU match could return an inactive or out-of-category product. Now
wrapped in a `where(fn ...)` group along with the new size-label clause.

### Verified

Temporary feature test, **15 passed / 56 assertions**: legacy integer cart keys still parse;
a sized line prices off the variant (R480, not R100); `display_price` is the cheapest size;
deactivating every size degrades to a simple product; adding without a size is refused;
**attaching another product's variant is refused** (the price-tampering path); two sizes are
two separate lines; a withdrawn size is dropped from the cart rather than repriced; the picker
renders; search finds by size **and still respects the active filter**; an order keeps its
label and price after the variant is deleted, with the FK nulled rather than the item removed;
the admin sync keeps row identity, reorders, rejects duplicate labels gracefully and parks
sizes on untick.

Cart/checkout paths were exercised with `hide_pricing` temporarily 0; **inquiry mode was
restored to 1 afterwards and verified**.

Not exercised: the browser UI (Alpine picker, admin repeater) and a real payment. The CSV
importer still has no size columns — bulk-loading sizes is a follow-up, not silently handled.

**Deploy**: requires `php artisan migrate`. No new routes, but Blade changed → `view:clear`.

---

## [2026-08-24] — Admin-Managed Seasonal Specials, Auto-Compression & Agency Credit

**Type**: Feature (Admin + Storefront) & Bug Fix
**Reported as**: "in special section we have some images of montfre or like this can we add an
option in admin panel to change or modify those images as well same as banner or categories"
**Files Changed**: migration `create_specials_table` (new), `app/Support/ImageThumbnailer.php`
(new), `Special.php` (new), `Admin/SpecialController.php` (new),
`admin/specials/{index,create,edit}.blade.php` (new), `partials/agency-credit.blade.php` (new),
`HomeController.php`, `routes/web.php`, `layouts/admin.blade.php`, `layouts/frontend.blade.php`,
`layouts/user.blade.php`, `frontend/specials.blade.php`, `frontend/orders/track.blade.php`,
`ARCHITECTURE.md`, `DATABASE_SCHEMA.md`, `ADMIN_PANEL.md`, `FEATURE_MAP.md`,
`TESTING_CHECKLIST.md`

The three branch flyers on `/specials` were a hardcoded PHP array inside the Blade template,
so swapping one meant a code edit and a deploy. They are now database rows with full CRUD,
ordering, and an active toggle — the banners pattern, plus automatic image compression.

- **`specials` table**, seeded with the three existing cards pointing at their current
  `public/images/*` paths, so the page rendered identically the moment the migration ran.
- **One upload, two images.** The admin uploads only the full flyer; `ImageThumbnailer`
  derives the compressed WebP grid copy. The flyers are ~2245×1587 PNGs of 4–5MB rendered
  into a 512px-tall card, so viewing the page pulled **~15MB**. Measured on the real files:
  4.99MB → 290KB, 4.39MB → 259KB — about a **94% reduction**, ~1.3s per image.
- **GD, not `intervention/image`.** Both machines have GD with WebP (live also has Imagick),
  so a package would have added a composer dependency — and a `composer install` on every
  deploy — for four native calls. `image` is nullable and every failure path returns null, so
  a missing thumbnail costs a fallback to the full flyer and a note in the success message,
  never the admin's upload.
- **`deleteIfOwned()`** only removes files under `uploads/`. The seeded rows point at legacy
  `public/images/*` assets that are tracked in git and shared across pages — the specials hero
  and `/track-order` used the same file. An unguarded delete would have broken other pages.
- **Page header image** is a `Setting` (`specials_hero_image`) with its own cache key
  `specials_hero`, forgotten explicitly by `updateHero()` — no `Special` row changes, so
  `FlushesContentCache` would never fire for it.
- **Agency credit** — "Developed by Jabulani Tech Solutions" linking to
  `agency.jabulanigroupofcompanies.co.za`, in the storefront and customer-portal footers as
  one shared partial. `layouts/legacy.blade.php` also has a footer but is extended by **zero**
  views, so it was left alone.

### Bug fixed in passing

`images/qumbu_special_compressed.webp` **did not exist**. It was the header background on both
`/specials` and `/track-order`, so every load of either page made a failed request — invisible
at 10% opacity, which is how it survived. Both now go through `image_url()`, which falls back
to the placeholder rather than 404ing.

### Verified

`ImageThumbnailer` tested standalone against the real flyers: correct WebP output, capped at
1400px, refuses to upscale a small image, returns null for a missing source. Then a temporary
feature test, **6 passed / 49 assertions**: admin index lists the seeded rows and the header
card; an upload stores the flyer byte-for-byte unchanged and generates a WebP thumbnail more
than 5× smaller; `is_active` toggling adds and removes a special from `/specials`; arrows
reorder and restore, and the first row refuses "up"; a hero upload replaces the setting, busts
`specials_hero`, and **leaves the legacy asset it replaced on disk**; deleting a seeded special
removes the row but not `public/images/tsolo_special.*`. Local database and files restored
afterwards; the test was temporary (it depends on local data) and was removed.

Not exercised: the browser UI itself, and behaviour under the **local** 2MB
`upload_max_filesize` — a 5MB flyer cannot be uploaded through a local browser at all
(MEMORY.md Rule 5); production allows 1536M. The feature test bypasses PHP's upload limit, so
it proves the code path, not the local PHP config.

**Deploy**: requires `php artisan migrate`. New routes → **`php artisan route:clear` is
mandatory**. Blade changed → `view:clear`. No composer changes.

---

## [2026-08-22] — Adopted Live's Mobile Hero Rework Into Git

**Type**: Housekeeping (production drift)
**Files Changed**: `resources/views/home.blade.php`, 8 deleted `public/images/*.webp`

The live checkout was carrying **uncommitted** changes that existed in no repository and had
survived at least two deploys. Discovered while deploying the category-ordering feature.

- `home.blade.php` — the hero content block becomes `justify-between` on small screens and
  `sm:justify-center` above, splitting bullets / heading / CTAs into explicit top and bottom
  blocks so the hero stops overflowing on portrait phones. Copied down from live with `scp`;
  the resulting diff was byte identical (same md5) to the server's own `git diff`.
- The 8 `public/images/*.webp` deletions were confirmed safe before being recorded: no hits
  in `app/`, `resources/`, `routes/` or public assets, and no rows in `categories.image`,
  `banners.image`/`image_mobile`, `products.image` or `stores.image` referenced them on the
  production database. (An initial `LIKE '%PVA%'`-style scan looked like 11 hits; all were
  `images/products/*.png` uploads, unrelated files.)

### Procedure worth repeating

Live's working copy was verified with `git hash-object resources/views/home.blade.php`
against the committed blob (`068fd93…`) **before** running `git checkout -- .` there. Matching
hashes are what make discarding the working tree provably lossless; without that check the
clean-up is a guess. Backups were taken first regardless
(`~/deploy-backups/home.blade.php.pre-category-order` + `uncommitted.pre-category-order.patch`)
and rollback branches `backup-pre-category-order` / `backup-pre-hero-adopt` were set.

Live's tracked tree is now clean for the first time in at least two deploys. The habit that
produced the drift has not changed, so check `git status` on live before every git operation.

**Deploy**: no migration, no new routes. Blade changed → `view:clear`.

---

## [2026-08-22] — Category Display Order (per-parent sort_order + reorder arrows)

**Type**: Feature (Admin)
**Files Changed**: migration `add_sort_order_to_categories_table` (new),
`admin/categories/partials/move.blade.php` (new), `Category.php`,
`Admin/CategoryController.php`, `HomeController.php`, `Admin/ProductController.php`,
`routes/web.php`, `admin/categories/{index,create,edit}.blade.php`, `DATABASE_SCHEMA.md`,
`ADMIN_PANEL.md`, `FEATURE_MAP.md`, `TESTING_CHECKLIST.md`

Categories rendered in primary-key order in all four places they appear, so the sequence
could only be changed by deleting and re-creating rows. This is the banner-ordering pattern
from 2026-08-17 applied to categories, with one structural difference: categories are a tree,
so the order is per parent.

- **`categories.sort_order`** — unsigned int, default 0, composite index
  `(parent_id, sort_order)` because every ordering query filters by parent first.
  **Backfilled to `id`** on migration so the live order was preserved exactly, both at the
  top level and inside each parent group; without it every row would default to 0 and the
  category grid could silently reshuffle on deploy.
- **`Category::ordered()`** — `sort_order` then `id`. Applied at every call site that faces a
  shopper or an admin: the homepage grid (`categories_top`), the `/products` sidebar, and the
  product form dropdowns. **`Category::children()` carries `ordered()` on the relation**, so
  every `with('children')` is ordered without the call site having to remember.
- **Per-parent scope.** `move()` confines its neighbour search to rows sharing the row's
  `parent_id`, so a child can never trade places with an unrelated branch and the bounds are
  those of its own sibling group.
- **The admin list is now a tree and is no longer paginated.** An arrow is meaningless when
  its neighbour sits on another page — the move would look like it did nothing until you
  flipped pages. Parents show with their children nested, numbered `1`, `1.1`, `1.2`, `2`, …
  The arrow control is one shared partial so parent and child rows cannot drift apart.
- **Display Order** field on both forms. Blank on create means *last in its sibling group*
  (`Category::nextSortOrder($parentId)`), not position 0. Blank on update keeps the current
  position — unless the parent changed, in which case the row lands at the end of its new
  group, since a position inherited from the group it just left means nothing there.
- The `categories.move` route is registered **before** `Route::resource('categories', …)`,
  matching the banners precedent.

### Verified

Ran against a throwaway SQLite database (local MySQL was down), 17 checks, all passing:
backfill left `sort_order == id` for every row and preserved both the top-level and
per-parent order; moving a category up stepped it one position at a time; the first row
refused "up" and the last refused "down" without changing anything; the first child of a
parent refused "up" even though another parent's children sort before it globally; child
moves left the top level untouched and vice versa; a tied pair still swapped rather than
no-oping; `nextSortOrder()` returned the end of the correct sibling group and `1` for a
parent with no children yet; and a save cleared `categories_top`. `php artisan view:cache`
compiles all four Blade files, and `route:list` shows `admin.categories.move` registered.

Not exercised: the browser UI itself, and the migration against MySQL — the composite index
and the `orderBy`-on-`update` backfill both need a real `php artisan migrate` on a MySQL
database. The pre-existing `ExampleTest` still fails locally because it hits `/`, which needs
MySQL; unrelated to this change.

**Deploy**: requires `php artisan migrate`. New route → **`php artisan route:clear` is
mandatory** (see MEMORY.md Rule 2). Blade changed → `view:clear`.

---

## [2026-08-17] — Recently Viewed, "Order Again", and a Working Product Status

**Type**: Feature (Storefront + User Portal) & Bug Fix
**Files Changed**: `app/Support/RecentlyViewed.php` (new),
`frontend/partials/recently_viewed.blade.php` (new), `Product.php`, `HomeController.php`,
`User/OrderController.php`, `Admin/ProductController.php`, `routes/web.php`,
`frontend/product-single.blade.php`, `user/orders/{show,index}.blade.php`,
`admin/products/{create,edit}.blade.php`

### Recently viewed products

Session-based, so it works for guests as well as logged-in customers, needs no migration and
adds no database write to product pages. Only IDs are stored — serialising models would
freeze prices and re-display deleted products. Capped at 8, most-recent-first, revisits move
to the front instead of duplicating, and the current product is excluded from its own strip.

Rendering reuses `product_card.blade.php`, so it inherits price hiding, `image_url()` and the
WhatsApp CTA automatically. **Never cached** — the `Cache::remember` keys are for shared
content; this is per-session.

Cross-device continuity would need a `users.recently_viewed` column; deliberately deferred.

### "Order again" — dual-mode by design

One `POST user/orders/{order}/reorder` route that decides server-side:

| `hide_pricing` | Behaviour |
|:---|:---|
| `1` (now) | Redirects to WhatsApp pre-filled with the order's items, using the same `invoice_company_phone` as the storefront CTA |
| `0` (later) | Merges the items into the cart and redirects to `/cart` |

Deliberately **not** inside the `pricing.enabled` group — that group would bounce it to
`/contact`. Because the branch is server-side, **re-enabling pricing switches this to a real
cart reorder with no view changes**. Both paths are covered by tests.

Items whose product was deleted or deactivated are skipped rather than failing the whole
action, and the customer is told how many were dropped. Quantities merge into an existing
cart rather than replacing it. Cart persistence mirrors `CartController::syncCartToDb()`
locally on purpose — CartController is the payment path and was not refactored.

### Product `status` made functional (it never worked)

`status` was documented as controlling storefront visibility but was **not in `$fillable`**,
never validated, had no form field, and was filtered by no query. Every product sat at the
column default `active`, permanently. Now fillable, validated `required|in:active,inactive`,
selectable on both admin forms, and enforced by `Product::scopeActive()` across the homepage,
listing, search, product detail and recently-viewed. Inactive products 404.

### Verified

Four tests, all passing: view-order tracking and de-duplication; inactive product 404s and
disappears from search, then restores; reorder → WhatsApp with the cart untouched under
`hide_pricing=1`; reorder → `/cart` with correct product IDs and quantities under
`hide_pricing=0`, with `/cart` confirmed reachable. Inquiry mode restored afterwards.

**Deploy**: no migration. New route → **`php artisan route:clear` is mandatory**.

---

## [2026-08-17] — Banner Display Order (sort_order + reorder arrows)

**Type**: Feature (Admin)
**Files Changed**: migration `add_sort_order_to_banners_table` (new), `Banner.php`,
`HomeController.php`, `Admin/BannerController.php`, `routes/web.php`,
`admin/banners/{index,create,edit}.blade.php`, `DATABASE_SCHEMA.md`, `ADMIN_PANEL.md`,
`TESTING_CHECKLIST.md`

The hero slider rendered in primary-key order, so the sequence could only be changed by
deleting and re-creating banners.

- **`banners.sort_order`** — unsigned int, default 0, indexed. **Backfilled to `id`** on
  migration so the live slider's existing order was preserved exactly; without that every row
  would default to 0 and the sequence could silently reshuffle on deploy.
- **`Banner::ordered()`** — `sort_order` then `id`. The `id` tie-break makes the order
  deterministic even when two rows share a `sort_order`, which legacy rows can.
  Used by both `HomeController::index` and the admin index, so the list always matches
  what visitors see.
- **Up/down arrows** on the banners list — `POST admin/banners/{banner}/move/{up|down}`,
  disabled at the boundaries, with the slide number shown per row. The action swaps the two
  rows' `sort_order` inside a transaction rather than renumbering the list, and nudges by ±1
  if the pair is tied. Both saves fire `saved`, so the `banners` cache invalidates itself.
- **Display Order** field on both forms. Blank on create means *last*
  (`Banner::nextSortOrder()`), not position 0 — otherwise every new banner would jump to the
  front of the slider.
- The `banners.move` route is registered **before** `Route::resource('banners', …)`; after it,
  the resource's `{banner}` wildcards would shadow it.

### Verified

Backfill preserved the original order (`sort_order == id` for all rows). Moving the last
banner up twice stepped it 5 → 4 → 3 one position at a time. Cache invalidated by the swap.
First row refuses "up" and last refuses "down" with a readable message. Original order
restored afterwards.

**Deploy**: requires `php artisan migrate`.

---

## [2026-08-17] — Hero Banner Count Fix, Content-Cache Invalidation & Mobile Art Direction

**Type**: Bug Fix (stale cache) & Feature (responsive banners)
**Reported as**: "it just adds 4 images in swiper or banner… I want the number of images I
choose to upload"

**Files Changed**: `Models/Concerns/FlushesContentCache.php` (new), migration
`add_image_mobile_to_banners_table` (new), `Banner.php`, `Store.php`, `Brand.php`,
`Category.php`, `TeamMember.php`, `GalleryItem.php`, `BlogPost.php`,
`Admin/BannerController.php`, `home.blade.php`, `admin/banners/{create,edit,index}.blade.php`,
`ARCHITECTURE.md`, `ADMIN_PANEL.md`, `DATABASE_SCHEMA.md`, `TESTING_CHECKLIST.md`

### There was never a 4-banner limit — it was a stale cache

`HomeController` uses `Banner::all()` with no `take()`. The real cause was
`Cache::remember('banners', 3600, …)` that **nothing ever invalidated**. Live had **5**
banners in the database while the cache held a 4-row snapshot, so the homepage rendered 4
slides. The 5th would have appeared on its own within the hour.

Nine keys had the same defect: `banners`, `stores_all`, `stores_page`, `brands`,
`categories_top`, `team_about`, `team_all`, `gallery_all`, `blog_post_{slug}`. Every one
produced "I saved it and nothing changed".

### Changes

- **`FlushesContentCache` trait** on all seven owning models, hooking `saved` and `deleted`.
  Invalidation lives on the model, not in controllers, so admin CRUD, tinker, seeders and the
  CSV import are all covered. Caching is kept (TTL stays 3600) — invalidation, not removal,
  is what keeps the site fast *and* correct.
  `BlogPost` also clears the **previous** slug's key via `getOriginal('slug')`, or a renamed
  post keeps serving its pre-edit copy on the old URL.
- **`banners.image_mobile`** — nullable column for a portrait crop, rendered through
  `<picture>` + `<source media="(max-width: 768px)">`. This is art direction: the hero is
  `object-cover` at near-viewport height, so a wide banner is cropped hard to its centre on a
  portrait phone. `<picture>` lets the browser choose *before* downloading, so a phone never
  fetches the desktop file. Nullable → falls back to `image`, so it can be added per banner
  and existing rows are untouched.
  A `-mobile` filename convention was **rejected**: nothing validates the partner file exists,
  a rename breaks it silently, and it is invisible in the admin UI.
- Edit form gained a `remove_image_mobile` checkbox; both images are pruned on replace and on
  delete; the index shows "mobile set" / "desktop only" per row.
- **Hero loading fixed**: previously *every* slide was `loading="eager"`, so a visitor
  downloaded all banners at full size on first paint. Now only the first is eager with
  `fetchpriority="high"` (it is the LCP element); the rest are lazy.

### Verified

Warmed the cache, then created / updated / deleted a banner — invalidated in all three cases,
and the homepage went from 4 to 5 slides. Confirmed all nine keys invalidate, including the
old-slug case. Rendered the hero with a mobile crop set: exactly one `<source>` emitted, the
desktop `<img>` retained as fallback, 1 eager + 4 lazy.

**Deploy**: needs `php artisan migrate` (adds `banners.image_mobile`). Existing banners keep
working with no data change.

---

## [2026-08-17] — Image Upload Fixes (7 bugs) + Deployment Reality Check

**Type**: Bug Fix (Admin uploads) & Documentation Correction
**Reported as**: "sometimes when I upload images for banner or stocks it accepts webp or
.png but sticks the UI and sometimes gives extension error"

**Files Changed**: `app/helpers.php` (new), `Concerns/ValidatesImageUploads.php` (new),
`public/.user.ini` (new), all 10 `Admin\*` upload controllers, `CartController.php`,
`AppServiceProvider.php`, `composer.json`, `layouts/admin.blade.php`, 36 Blade views,
`.gitignore`, `public/.gitignore`, `ARCHITECTURE.md`, `SECURITY.md`, `ADMIN_PANEL.md`,
`DEPLOYMENT.md`, `KNOWN_ISSUES.md`, `TESTING_CHECKLIST.md`, `MEMORY.md`

### Root causes — seven independent defects, which is why it looked random

1. **WebP/AVIF rejected on banners.** `BannerController` used
   `image|mimes:jpeg,png,jpg,gif,svg`. In Laravel 12 the `image` rule resolves to
   `jpg,jpeg,png,gif,bmp,webp` and no longer implies `svg`. Both rules must pass, so the
   real allow-list was just `jpg,jpeg,png,gif` — WebP failed on `mimes` while the message
   advertised `svg`, which failed on `image`. Every other module used bare `image`, which
   *does* allow WebP → the inconsistency.
2. **Validation errors were invisible.** `layouts/admin.blade.php` had no `$errors` block
   (2 of 35 views rendered errors) and the product/banner forms had no `old()`. A rejection
   bounced back to a blank form with no message — the reported "stuck UI".
3. **Requests that hung forever.** `SESSION_DRIVER=file`; PHP holds an exclusive `flock()`
   on the session file per request, so an impatient second submit blocked on the lock doing
   no work, and syscall waits do not count toward `max_execution_time`.
4. **Silent write failures.** The `public` disk sets `'throw' => false`, so a failed
   `store()` returned `false` and saved an empty image while reporting success.
5. **Product edit could never save.** `ProductController@update` validated
   `stocks.*` => `numeric`, but the edit form posts nested `stocks[<id>][quantity]`.
6. **Admin Edit buttons 404'd** for products, categories and brands — views passed
   `$model->id` to `route()` while the models bind by `slug`. The product edit form's
   `update`/`destroy` actions were also pointed at 404 URLs.
7. **Broken image paths.** Uploads land in `public/<folder>/` (the `public` disk root is
   overridden to `public_path('')`), but `admin/products/index` prefixed non-legacy paths
   with `images/` → 404 thumbnails, and `pdf/invoice.blade.php` looked under
   `public/storage/`.

### Changes

- **`ValidatesImageUploads` trait** — one rule for all 11 upload paths:
  `mimes:jpg,jpeg,png,gif,webp,avif|max:8192`, plain-language messages, and a
  `storeImage()` that uses UUID filenames and checks the `throw => false` return value.
  SVG stays excluded (embedded-JS/XSS risk).
- **`image_url()` / `image_path()` / `image_relative_path()`** in `app/helpers.php` replace
  ad-hoc `file_exists`/`Str::contains`/`'images/'`-prefixing across 28 views. Resolves all
  three live storage schemes, so **no data migration was needed**. Registered via both
  `composer.json` `autoload.files` **and** `AppServiceProvider::register()` so a deploy
  without `composer dump-autoload` cannot fatal.
- Global `$errors` alert + submit-button locking in the admin layout.
- `old()` repopulation, inline `@error`, explicit `accept` lists and size hints on upload forms.
- Replaced images are now pruned on update; records delete their files.
- `public/.gitignore` gained `!.user.ini` — its blanket `*` silently swallows new files
  (the same trap that once left `design-system.css` 404ing).

### Corrections to prior documentation

- **`ARCHITECTURE.md § 7` was wrong.** It claimed uploads live under `public/storage/…`.
  They do not — the `public` disk root is `public_path('')`.
- **The 2M/8M upload ceiling was local, not production.** Live's web SAPI allows 1536M, so
  the 419 Page Expired path was never reachable on live. `public/.user.ini` is committed but
  is **not** honoured on Hostinger; the binding limit is the app's own `max:8192`.
- **Dev and live are two different GitHub repos** (`CH-USAMA/E-commerce-Store` `master` vs
  `JabulaniGroup/Jabulani-E-commerce-Store` `main`). Pushing to dev deploys nothing. See
  `MEMORY.md` Rule 2 for the merge-locally-then-fast-forward procedure.
- SSH does work (`-p 65002`); the old "never give SSH instructions" rule was unfounded.

### Verified

All 9 public + 20 admin pages render 200 with zero empty image srcs; old-vs-new rules
checked against real generated jpg/png/gif/webp/avif/svg files; helper verified against a
deliberately stale autoloader; all Blade templates compile; live site confirmed 200 across
10 routes post-deploy.

**Still open**: `APP_DEBUG=true` on production (see `SECURITY.md § 8`), and EFT screenshots
world-readable in `public/payments/`.

**Deploy**: merge live's `main` locally → push → on live `git merge --ff-only` +
`php artisan view:clear`. No migration. `composer dump-autoload` optional.

---

## [2026-07-25] — Hide Pricing / Inquiry Mode

**Type**: Feature (Storefront)  
**Files Changed**: `SystemController.php`, `Kernel.php`, `CheckPricingEnabled.php` (new), `web.php`, `payments.blade.php`, `price_or_contact.blade.php` (new), `product_card.blade.php`, `products.blade.php`, `product-single.blade.php`, `layouts/frontend.blade.php`, `ADMIN_PANEL.md`

- **New setting `hide_pricing`**: toggle on `Admin > Settings > Payments` ("Storefront Pricing" card). When enabled, hides prices site-wide (homepage carousels, shop/search listing, product detail page) and replaces Add to Cart with a WhatsApp "Contact Us" CTA (using `invoice_company_phone`, no price in the prefilled message).
- **Route guard**: new `pricing.enabled` middleware (`CheckPricingEnabled`) wraps all `/cart/*` and `/checkout/*` routes (except `/cart/count`) and redirects to `/contact` while `hide_pricing` is on. `/order-success` and `/track-order` remain unguarded so existing orders still work.
- **Header cart icon** hidden while `hide_pricing` is on.
- SEO JSON-LD price schema and `llms.txt`/`llms-full.txt` catalog pages intentionally left unchanged (out of scope).

---

## [2026-04-06] — Paystack Integration & Gateway Selection

**Type**: Feature (Payments)  
**Files Changed**: `SystemController.php`, `PaystackController.php`, `CartController.php`, `web.php`, `payments.blade.php`, `CHANGELOG.md`, `DATABASE_SCHEMA.md`, `ADMIN_PANEL.md`

- **Paystack Integration**: Implemented a full redirect-based payment flow for Paystack (Public/Secret Key support).
- **Gateway Selection**: Added an "Active Gateway Strategy" in the Admin Panel to switch the "Online Payment" method between Stripe and Paystack dynamically.
- **Paystack Callback**: Implemented a secure verification handler that confirms transactions via the Paystack API before updating order status and sending confirmation emails.
- **Unified Frontend**: Customers only see a single "Online Payment" option, while the backend determines the provider.

---

## [2026-04-06] — Global Theme Engine & Admin Permission System

**Type**: Feature & Security Hardening  
**Files Changed**: `User.php`, `web.php`, `Kernel.php`, `CheckPermission.php`, `UserController.php`, `SystemController.php`, `frontend.blade.php`, `admin.blade.php`, `user.blade.php`, `home.blade.php`, `theme.blade.php`, `SocialAuthController.php`, `AuthController.php`

### 🎨 Global Theme Customization
- **Custom Theme Engine**: Implemented a dynamic theme system in the Admin Panel (System > Theme Settings).
- **Dynamic CSS Variables**: Injected `--brand-primary`, `--brand-primary-rgb`, `--bg-main`, `--bg-surface`, and `--text-primary` into all layouts. All hardcoded yellow/gold accents replaced with these variables.
- **Visual Polish**: Removed hardcoded yellow `sepia` and `hue-rotate` filters from category images. Implemented dynamic RGBA shadow calculation based on primary color.
- **Contrast Logic**: Theme automatically adjusts primary text color (black/white) based on the background to ensure readability.

### 🔐 Admin Permission System (RBAC Lite)
- **Granular Permissions**: Added a `permissions` JSON column to the `users` table.
- **Module Access**: Admins can now be restricted to specific modules: `manage_products`, `manage_orders`, `manage_content`, `manage_users`, `manage_settings`, `view_analytics`.
- **Dynamic Sidebar**: Sidebar links and dashboard stats now hide/show based on the logged-in user's assigned permissions.
- **Permission Middleware**: Created `CheckPermission` middleware to enforce route-level security.

### 🛠️ Auth & System Fixes
- **Social Auth Fix**: Added `email_verified_at` to `User::$fillable`. Google logins now correctly mark users as verified without sending redundant verification emails.
- **Manual Registration**: Explicitly called `sendEmailVerificationNotification()` in `AuthController` to ensure delivery.
- **Regex Repair**: Fixed regex delimiter issues in `SystemController`.

**Deploy**: `git pull` + `php artisan migrate` + `php artisan route:clear` + `php artisan view:clear`.

---

## [2026-04-02] — AIO (AI Optimization) & llms.txt Implementation

**Type**: SEO & AI Web Scraper Optimization
**Files Changed**: `routes/web.php`, `robots.blade.php`, `llms.blade.php`, `llms-full.blade.php`

### Fixes
*   **Blade Syntax Errors**: Fixed "unexpected end of file" errors by expanding inline `@if` statements and re-organizing `@push` blocks in `home`, `product-single`, and `store-detail` views. This ensures compatibility with older or strictly configured Blade compilers on production servers (e.g., Hostinger).

- Upgraded `robots.txt` to explicitly invite major AI engines (`GPTBot`, `ClaudeBot`, `PerplexityBot`, `Google-Extended`)
- Implemented `/llms.txt` — a machine-readable markdown overview of the business tailored for ChatGPT/Claude
- Implemented `/llms-full.txt` — a complete raw markdown catalog dumping all products and prices for instant AI comparison against competitors (Cashbuild)

**Deploy**: `git pull` + `php artisan route:clear`.

---

## [2026-04-02] — Full SEO Overhaul (Sitemap & JSON-LD)

**Type**: SEO & Performance
**Files Changed**: `routes/web.php`, `sitemap.blade.php`, `robots.blade.php`, `home.blade.php`, `product-single.blade.php`, `store-detail.blade.php`

- Replaced old static sitemap with dynamic `/sitemap.xml` covering stores, products, categories, brands, and blogs
- Added dynamic `/robots.txt` blocking `/admin` and `/cart` to preserve Google crawl budget
- Injected `Organization` JSON-LD schema into the homepage with all 5 social links
- Injected `Product` JSON-LD schema into product pages to enable Google Shopping price badges
- Injected `HardwareStore` (LocalBusiness) JSON-LD schema into store pages to trigger "near me" Maps rankings

**Deploy**: `git pull` + `php artisan route:clear` + Submit `/sitemap.xml` to Google Search Console.

---

## [2026-04-02] — Documentation Overhaul & AI Intelligence System

**Type**: Documentation
**Files Changed**: `MEMORY.md`, `docs/memory/*` (14 files total)

- Rebuilt entire `docs/memory/` system from scratch into a dense, machine-readable format
- Added: `USER_PORTAL.md`, `SECURITY.md`, `DEPLOYMENT.md`, `TESTING_CHECKLIST.md`, `KNOWN_ISSUES.md`, `FEATURE_MAP.md`
- Upgraded: `SYSTEM_OVERVIEW.md` (role matrix, integrations), `DATABASE_SCHEMA.md` (full column-level schema), `ARCHITECTURE.md` (middleware stack, controller→model matrix), `ORDER_FLOW.md` (all branch conditions), `PRODUCT_FLOW.md` (WMS states, CSV spec), `ADMIN_PANEL.md` (full route table, settings registry), `IMPROVEMENT_PLAN.md` (Phase 5 tasks + AI protocol)
- `MEMORY.md` root: Now an AI operating contract with 6 enforceable rules, environment comparison table, and full doc index

**Deploy**: No code changes. Documentation only. No migration or cache clear required.

---

## [2026-04-02] — Stripe Integration Fix
## [2026-04-02] — Stripe & Paystack Integration

**Type**: Feature & Bug Fix
**Files Changed**: `app/Http/Controllers/CartController.php`, `database/migrations/add_paystack_settings.php`

- Fixed `Class "Stripe\Stripe" not found` — ran `composer install --no-dev`
- Updated payment routing to handle `payfast`, `stripe`, and `paystack`
- Added Paystack integration with webhook verification
- Added new settings keys:
| Key | Type | Location | Description |
| :--- | :--- | :--- | :--- |
| `invoice_company_name` | string | Invoice page | Company name on PDF |
| `stripe_enabled` | `0` or `1` | Payments page | Enable/disable Stripe checkout |
| `paystack_enabled` | `0` or `1` | Payments page | Enable/disable Paystack checkout |
| `preferred_online_gateway` | `stripe` or `paystack` | Payments page | Currently active online provider |
| `paystack_public_key` | string | Payments page | Paystack public key |
| `paystack_secret_key` | string | Payments page | Paystack secret key |

**Deploy**: Run `composer install --no-dev` and `php artisan migrate` on Hostinger after `git pull`.

---

## [2026-04-01] — UUID Security Hardening (Full System)

**Type**: Security
**Files Changed**: Multiple views, `NewOrderNotification.php`, `OrderStatusChangedNotification.php`

- Fixed admin order views: all form actions now use `$order` object (UUID), not `$order->id`
- Fixed branch manager order view: status update form uses UUID routing
- Fixed notification payloads: `order_id` now stores UUID string instead of integer
- Repaired legacy notification records in DB (used `tmp/repair_notifications_uuids.php`)

**Deploy**: Run repair script on live if old notifications still show integer URLs.

---

## [2026-04-01] — Production Logging (Daily Rotation)

**Type**: Infrastructure
**Files Changed**: `config/logging.php`

- Changed default log channel to `daily` driver
- Log files now stored as `storage/logs/laravel-YYYY-MM-DD.log`
- Retention: 14 days

**Deploy**: `git pull` + `php artisan config:clear`.

---

## [2026-04-01] — Fix 403 After Google OAuth Login

**Type**: Bug Fix
**Files Changed**: `app/Http/Controllers/Auth/SocialAuthController.php`

- New Google users were not being assigned `role = 'user'`
- `role:user` middleware blocked access to `/user/dashboard`
- Fix: Explicitly set `$user->role = 'user'` on user creation in `handleGoogleCallback()`

---

## [2026-03-31] — WMS Inventory States

**Type**: Feature
**Files Changed**: Migration, `ProductStoreStock` model, branch views

- Added `incoming_quantity`, `reserved_quantity`, `damaged_quantity` to `product_store_stocks`
- Updated branch dashboard to display and edit all WMS states
- Updated CSV import/export to include WMS columns

---

## [2026-03-31] — Audit Logging System

**Type**: Feature
**Files Changed**: New `activity_logs` migration, `ActivityLog` model

- Created `ActivityLog` model with static `record()` helper
- Logs: order status changes, payment confirmations, stock adjustments
- Columns: `action`, `model_type`, `model_id`, `old_values`, `new_values`, `user_id`

---

## [2026-03-31] — FULLTEXT Search

**Type**: Performance
**Files Changed**: Migration (FULLTEXT index), `HomeController`

- Added FULLTEXT indexes on `products.name` and `products.description`
- Replaced `LIKE '%query%'` with `MATCH...AGAINST` for faster search
- Fallback to `LIKE` if FULLTEXT returns no results

---

## [2026-03-19] — Stripe Checkout Integration (Initial)

**Type**: Feature
**Files Changed**: `CartController.php`, `resources/views/frontend/checkout.blade.php`

- Added Stripe Checkout payment path (using `payfast` as DB value — legacy alias)
- Stripe keys stored in `settings` DB table, managed via Admin > Payments
- On success: order status updated to `processing`, confirmation email dispatched

---

## [2026-03-19] — Database Notifications

**Type**: Feature
**Files Changed**: `notifications` migration, `NewOrderNotification`, `OrderStatusChangedNotification`

- Admin notified via DB notification when new order is placed
- User notified via DB notification when order status changes
- Notification bell in admin and user dashboards

---

## [2026-03-06] — Google OAuth (Socialite)

**Type**: Feature
**Files Changed**: `SocialAuthController`, `users` migration (social fields), routes

- Google OAuth via Laravel Socialite
- Stores `google_id`, `google_token`, `google_refresh_token` on user
- Auto-verifies email on first Google login

---

## [2026-03-04] — Security Middleware

**Type**: Security
**Files Changed**: `app/Http/Middleware/SecurityHeaders.php`, kernel

- Added CSP, HSTS, X-Frame, XSS, X-Content-Type, Referrer-Policy headers
- Applied globally to all HTTP responses
- Rate limiting (throttle:6,1) on auth and contact routes

---

## [2026-03-03] — Initial System Build

**Type**: Initial Release
- Laravel 12 project initialized
- Core tables: users, stores, categories, brands, products, orders, order_items, settings
- Admin portal (Carbon Pro) with full CRUD
- Frontend (Tailwind + Alpine.js) with cart, checkout, product listing
- EFT payment flow with proof-of-payment upload
- Branch manager portal
- Geolocation nearest-store detection
