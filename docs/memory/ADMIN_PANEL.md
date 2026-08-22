# Admin Panel — Carbon Pro Dashboard

---

## 1. Design System

- **CSS Framework**: Vanilla CSS (Carbon Pro), dark-mode, custom variables in `public/css/design-system.css`
- **Layout**: `resources/views/layouts/admin.blade.php`
- **Notification Bell**: Pulls unread DB notifications from `auth()->user()->unreadNotifications`
- **Sidebar Sections**: Operations | Catalog | Network | Website | System

### Layout-provided behaviour (added 2026-08-17)

`layouts/admin.blade.php` now supplies two things to **all** admin pages. Do not duplicate
them per-view, and do not remove them.

**1. Global validation-error alert.** Renders `$errors->any()` above `@yield('content')`.
Before this, only 2 of 35 admin views displayed errors at all — a rejected form silently
redirected back to a blank page with no message, which users reported as the page "hanging".
Individual forms may still add inline `@error(...)` for field-level highlighting, but the
layout guarantees the message is never lost.

**2. Submit-button locking.** A delegated `submit` listener disables the submit button and
swaps its label to "Uploading…" / "Saving…" for the duration of the request.

This is not cosmetic. `SESSION_DRIVER=file`, and PHP holds an **exclusive `flock()`** on the
session file for a whole request. A second submit therefore blocks on the lock while doing
no work of its own, and time spent waiting in a syscall does **not** count toward
`max_execution_time` — so the duplicate request hangs indefinitely (visible as a
never-completing request in DevTools). Preventing the second submit is the actual fix.

Two implementation details that must be preserved if this is ever edited:

| Detail | Why |
|:---|:---|
| Listener is on the **bubble** phase and checks `event.defaultPrevented` | Delete forms use inline `onsubmit="return confirm(...)"`. A capture-phase listener would lock the button even when the user cancels the confirm. |
| `button.disabled = true` is deferred via `setTimeout(…, 0)` | Disabling a button *before* submit drops its `name`/`value` from the payload. |

A `pageshow` handler unlocks forms restored from the back/forward cache.

### Image upload form convention

Every admin file input should follow this shape:

```blade
<input type="file" name="image"
       class="form-control @error('image') is-invalid @enderror"
       accept=".jpg,.jpeg,.png,.gif,.webp,.avif">
@error('image')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
<div class="form-text">JPG, PNG, GIF, WebP or AVIF &middot; max 8MB</div>
```

- Use the **explicit extension list**, not `accept="image/*"` — the latter lets an iPhone
  offer HEIC, which the server then rejects. (`stores/` used `image/*` and was changed.)
- `accept` is a UX filter only; the server rule is authoritative (`SECURITY.md § 9`).
- Edit forms must show the current image via `image_url($model->image)` — never
  `asset($model->image)` or a hand-rolled `Str::contains` check.
- Every text/select/checkbox on a form containing an upload needs
  `old('field', $model->field)`, because a rejected image bounces the whole form back. A file
  input itself cannot be repopulated — the user must reselect the file.

### Homepage hero banners

There is **no cap** on the number of banners — `HomeController` uses `Banner::all()` and the
slider renders one slide per row. If a newly saved banner does not appear, the cause is the
`banners` cache key, not a limit (see `ARCHITECTURE.md § 4`); that is now invalidated on save.

**Two images per banner** (`banners.image`, `banners.image_mobile`):

| Field | Required | Recommended | Purpose |
|:---|:---|:---|:---|
| `image` | yes | landscape ≈ 1920×1080 | desktop / tablet |
| `image_mobile` | no | portrait ≈ 1080×1350 | phones ≤ 768px |

The hero renders them with `<picture>`:

```blade
<picture>
    @if($banner->image_mobile)
        <source media="(max-width: 768px)" srcset="{{ image_url($banner->image_mobile) }}">
    @endif
    <img src="{{ image_url($banner->image) }}" ...>
</picture>
```

This is **art direction**, not resolution switching. The hero is
`h-[calc(100vh-60px)]` with `object-cover`, so on a portrait phone a wide banner is cropped
hard to its centre — cutting the subject and any text baked into the image. `<picture>` +
`media` lets the browser choose *before* downloading, so a phone never fetches the desktop file.

`image_mobile` is nullable and falls back to `image`, so it can be added per banner. The edit
form offers a "remove mobile image" checkbox (`remove_image_mobile`) so a crop can be dropped
without replacing it, and the index shows a "mobile set" / "desktop only" marker per row.

A `-mobile` filename convention was **rejected**: nothing validates the partner file exists,
a rename breaks it silently, and it is invisible in the admin UI. Use the column.

Only the **first** slide is `loading="eager"` + `fetchpriority="high"` (it is the LCP
element); the rest are `loading="lazy"`. Previously every slide was eager, so a visitor
downloaded all banners at full size on first paint.

**Ordering.** `banners.sort_order` controls the sequence (lowest first) via
`Banner::ordered()`, which the admin index and the homepage both use — so the list always
matches what visitors see. The index shows the slide number with **up/down arrows**
(`POST admin/banners/{banner}/move/{up|down}`), disabled at the boundaries. The forms also
expose a numeric **Display Order** field; leaving it blank on create puts the banner last
(`Banner::nextSortOrder()`) rather than at position 0.

The move action swaps the two rows' `sort_order` values inside a transaction rather than
renumbering the whole list, and nudges by ±1 if the pair happens to be tied. Both saves fire
`saved`, so the `banners` cache is invalidated automatically.

> The `banners.move` route is declared **before** `Route::resource('banners', …)` in
> `routes/web.php`. Registered after, the resource's `{banner}` wildcards would shadow it.

---

## 1b. Category Display Order

*Added 2026-08-22.* Categories previously rendered in primary-key order everywhere, so the
sequence could only be changed by deleting and re-creating rows. `categories.sort_order` now
drives it via `Category::ordered()`, applied at every call site that faces a shopper or an
admin: `HomeController::index` (the homepage "Shop By Category" grid, cached as
`categories_top`), `HomeController::products` (the /products sidebar), and
`Admin\ProductController@create/edit` (the product form dropdowns). `Category::children()`
carries `ordered()` on the relation itself, so `with('children')` is ordered for free — do
not re-sort children at the call site.

**The order is per parent.** Top-level categories are ordered among themselves; each
parent's sub-categories are ordered within that parent. A child can never sort against an
unrelated branch, so `move()` confines its neighbour search to rows sharing the row's
`parent_id`. This matters for the /products sidebar, which renders both levels.

**The admin list is a tree and is deliberately NOT paginated.** An up/down arrow is
meaningless when its neighbour sits on another page — the move would look like it did
nothing until you flipped pages. `admin/categories` shows each parent with its children
nested underneath, numbered `1`, `1.1`, `1.2`, `2`, … The arrows
(`POST admin/categories/{category}/move/{up|down}`) are disabled at the bounds of the row's
**own** sibling group, so the first child of a parent refuses "up" even though other
categories sort before it globally. The arrow control is one partial,
`admin/categories/partials/move.blade.php`, shared by both row types so they cannot drift.

Both forms expose a numeric **Display Order** field. Blank on create means *last in its
sibling group* (`Category::nextSortOrder($parentId)`), not position 0. Blank on update keeps
the current position — unless the parent changed, in which case the row lands at the end of
its new group, because a position inherited from the group it just left means nothing.

The move action swaps the two rows' `sort_order` values inside a transaction rather than
renumbering the whole list, and nudges by ±1 if the pair happens to be tied. Both saves fire
`saved`, so the `categories_top` cache is invalidated automatically.

> The `categories.move` route is declared **before** `Route::resource('categories', …)` in
> `routes/web.php`, matching the banners precedent.

---

## 2. Complete Admin Route Table

| Method | URI | Controller@Method | Route Name |
|:---|:---|:---|:---|
| GET | `/admin/dashboard` | `Admin\DashboardController@index` | `admin.dashboard` |
| GET | `/admin/orders` | `Admin\OrderController@index` | `admin.orders.index` |
| GET | `/admin/orders/export` | `Admin\OrderController@export` | `admin.orders.export` |
| GET | `/admin/orders/fake` | `Admin\OrderController@createFakeOrder` | `admin.orders.fake` |
| GET | `/admin/orders/{order}` | `Admin\OrderController@show` | `admin.orders.show` |
| PUT | `/admin/orders/{order}` | `Admin\OrderController@update` | `admin.orders.update` |
| DELETE | `/admin/orders/{order}` | `Admin\OrderController@destroy` | `admin.orders.destroy` |
| POST | `/admin/orders/{order}/confirm-payment` | `Admin\OrderController@confirmPayment` | `admin.orders.confirm-payment` |
| GET | `/admin/orders/{order}/invoice` | `Admin\OrderController@invoice` | `admin.orders.invoice` |
| GET/POST/PUT/DELETE | `/admin/products/**` | `Admin\ProductController` (resource) | `admin.products.*` |
| GET | `/admin/products/export` | `Admin\ProductController@export` | `admin.products.export` |
| POST | `/admin/products/import` | `Admin\ProductController@import` | `admin.products.import` |
| GET/POST/PUT/DELETE | `/admin/stores/**` | `Admin\StoreController` (resource) | `admin.stores.*` |
| GET/POST/PUT/DELETE | `/admin/categories/**` | `Admin\CategoryController` | `admin.categories.*` |
| POST | `/admin/categories/{category}/move/{up\|down}` | `Admin\CategoryController@move` | `admin.categories.move` |
| GET/POST/PUT/DELETE | `/admin/brands/**` | `Admin\BrandController` | `admin.brands.*` |
| GET/POST/PUT/DELETE | `/admin/users/**` | `Admin\UserController` | `admin.users.*` |
| GET/POST/PUT/DELETE | `/admin/banners/**` | `Admin\BannerController` | `admin.banners.*` |
| GET/POST/PUT/DELETE | `/admin/services/**` | `Admin\ServiceController` | `admin.services.*` |
| GET/POST/PUT/DELETE | `/admin/blog/**` | `Admin\BlogPostController` | `admin.blog.*` |
| GET/POST/PUT/DELETE | `/admin/blog-categories/**` | `Admin\BlogCategoryController` | `admin.blog-categories.*` |
| GET/POST/PUT/DELETE | `/admin/gallery/**` | `Admin\GalleryItemController` | `admin.gallery.*` |
| GET/POST/PUT/DELETE | `/admin/team/**` | `Admin\TeamMemberController` | `admin.team.*` |
| GET | `/admin/guests` | `Admin\GuestController@index` | `admin.guests.index` |
| POST | `/admin/guests/purge` | `Admin\GuestController@purge` | `admin.guests.purge` |
| POST | `/admin/guests/purge-old` | `Admin\GuestController@purgeOld` | `admin.guests.purge-old` |
| GET | `/admin/marketing` | `Admin\MarketingController@index` | `admin.marketing.index` |
| POST | `/admin/marketing` | `Admin\MarketingController@push` | `admin.marketing.push` |
| DELETE | `/admin/marketing/{campaign}` | `Admin\MarketingController@destroy` | `admin.marketing.destroy` |
| GET | `/admin/settings/payments` | `Admin\SystemController@payments` | `admin.settings.payments` |
| POST | `/admin/settings/payments` | `Admin\SystemController@updatePayments` | `admin.settings.payments.update` |
| GET | `/admin/settings/invoice` | `Admin\SystemController@invoiceSettings` | `admin.settings.invoice` |
| POST | `/admin/settings/invoice` | `Admin\SystemController@updateInvoiceSettings` | `admin.settings.invoice.update` |
| POST | `/admin/settings/test-email` | `Admin\SystemController@sendTestEmail` | `admin.settings.test-email` |
| GET | `/admin/settings/theme` | `Admin\SystemController@themeSettings` | `admin.settings.theme` |
| POST | `/admin/settings/theme` | `Admin\SystemController@updateThemeSettings` | `admin.settings.theme.update` |

**All admin routes**: `auth, role:admin` middleware.  
**PBAC Enforcement**: Specific modules are further protected by the `permission:{module}` middleware (e.g., `manage_products`, `manage_orders`).

---

## 3. Settings Key-Value Registry

All stored in `settings` DB table. Managed via `Admin > Settings`.

| Key | Input Type | Set By | Purpose |
|:---|:---|:---|:---|
| `stripe_enabled` | `0` or `1` | Payments page | Enable/disable Stripe checkout |
| `stripe_secret_key` | string | Payments page | Stripe secret API key |
| `stripe_public_key` | string | Payments page | Stripe publishable key (for frontend JS) |
| `max_delivery_km` | numeric | Payments page | Max delivery radius in KM (default 300) |
| `invoice_company_name` | string | Invoice page | Company name on PDF |
| `invoice_company_address` | string | Invoice page | Address on PDF |
| `invoice_company_phone` | string | Invoice page | Phone on PDF and site footer |
| `invoice_company_email` | string | Invoice page | Email on PDF and contact page |
| `invoice_registration_number` | string | Invoice page | Reg number on PDF |
| `invoice_logo` | file path | Invoice page | Logo on PDF (`storage/settings/{file}`) |
| `invoice_eft_accounts` | JSON array | Invoice page | Bank account objects shown on checkout and PDF |
| `stripe_enabled` | `0` or `1` | Payments page | Enable/disable Stripe checkout |
| `stripe_secret_key` | string | Payments page | Stripe secret API key |
| `stripe_public_key` | string | Payments page | Stripe publishable key |
| `paystack_enabled` | `0` or `1` | Payments page | Enable/disable Paystack checkout |
| `paystack_public_key` | string | Payments page | Paystack public key |
| `paystack_secret_key` | string | Payments page | Paystack secret key |
| `preferred_online_gateway` | `stripe` or `paystack` | Payments page | Active online provider |
| `theme_primary_color` | string (hex) | Theme page | Main brand color (e.g. #FF8C00) |
| `theme_background_color`| string (hex) | Theme page | Site-wide background color |
| `theme_surface_color` | string (hex) | Theme page | Card and surface background color |
| `theme_text_color` | string (hex) | Theme page | Primary text color |
| `theme_primary_text_color`| string (hex) | Theme page | Text color on primary background (auto-calc) |
| `theme_muted_text_color` | string (hex) | Theme page | Muted/Secondary text color |
| `hide_pricing` | `0` or `1` | Payments page | When `1`, hides prices site-wide and replaces Add to Cart with a WhatsApp "Contact Us" CTA on every product; cart/checkout routes redirect to `/contact` (guarded by `pricing.enabled` middleware) |

### EFT Accounts JSON Structure
```json
[
  {
    "bank": "FNB",
    "account_name": "Moin Hardware",
    "account_number": "62866895166",
    "branch_code": "628"
  }
]
```

---

## 4. Order Filters (Admin Orders Index)

The orders index supports filtering by:
- `status` (GET param)
- `store_id` (GET param)
- `date_from` (GET param, `whereDate`)
- `date_to` (GET param, `whereDate`)
- `per_page` (default 20, options: 10, 20, 50, 100)

---

## 5. Dashboard KPIs

**Controller**: `Admin\DashboardController@index`
Calculates and passes to view:
- Total orders count and revenue
- Orders per status
- Recent orders list
- New users count
- Low stock products (if implemented)
