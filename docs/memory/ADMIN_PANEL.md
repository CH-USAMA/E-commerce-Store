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
