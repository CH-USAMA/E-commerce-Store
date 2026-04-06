# Admin Panel — Carbon Pro Dashboard

---

## 1. Design System

- **CSS Framework**: Vanilla CSS (Carbon Pro), dark-mode, custom variables in `public/css/design-system.css`
- **Layout**: `resources/views/layouts/admin.blade.php`
- **Notification Bell**: Pulls unread DB notifications from `auth()->user()->unreadNotifications`
- **Sidebar Sections**: Operations | Catalog | Network | Website | System

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

**All admin routes**: `auth, role:admin` middleware.

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
| `invoice_footer_text` | string | Invoice page | Footer text on PDF |
| `invoice_logo` | file path | Invoice page | Logo on PDF (`storage/settings/{file}`) |
| `invoice_eft_accounts` | JSON array | Invoice page | Bank account objects shown on checkout and PDF |

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
