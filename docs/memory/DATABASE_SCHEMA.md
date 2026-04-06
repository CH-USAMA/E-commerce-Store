# Database Schema & Relationships

> **Engine**: MySQL/MariaDB | **ORM**: Laravel Eloquent | **Migrations**: 49 files

---

## 1. Routing Identifier Map

| Model | Route Key | Column Used | Example URL |
|:---|:---|:---|:---|
| `Order` | UUID | `orders.uuid` | `/admin/orders/{uuid}` |
| `User` | UUID | `users.uuid` | (internal only) |
| `Product` | Slug | `products.slug` | `/product/{slug}` |
| `Store` | Slug | `stores.slug` | `/store/{slug}` |
| `Category` | Slug | `categories.slug` | (filter param) |
| `Brand` | Slug | `brands.slug` | (filter param) |
| `BlogPost` | Slug | `blog_posts.slug` | `/blog/{slug}` |

---

## 2. Core Tables — Full Column Reference

### `users`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | Internal only |
| `uuid` | varchar(36) | Route key for URLs |
| `name` | varchar(255) | |
| `email` | varchar(255) unique | |
| `email_verified_at` | timestamp null | Must be set for user checkout |
| `password` | varchar(255) hashed | |
| `role` | varchar(20) | `admin`, `manager`, `user`. NOT in `$fillable` |
| `phone` | varchar(50) null | |
| `cart_data` | json null | Serialized cart for persistence across sessions |
| `google_id` | varchar null | Social auth |
| `google_token` | text null | |
| `google_refresh_token` | text null | |
| `permissions` | json null | Granular module access (e.g., `manage_products`) |
| `remember_token` | varchar(100) null | |
| `created_at` / `updated_at` | timestamps | |

### `orders`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | Internal only |
| `uuid` | varchar(36) | Route key — all URLs use this |
| `order_number` | varchar | Format: `JB-YYYYMMDD-XXXXXX` |
| `user_id` | bigint FK null | Null for guest orders |
| `store_id` | bigint FK | Fulfillment branch |
| `total` | decimal(10,2) | Final order total (VAT-free) |
| `vat` | decimal(10,2) | Always 0.00 (VAT-free system) |
| `status` | varchar | `pending`, `awaiting_payment`, `processing`, `shipped`, `delivered`, `cancelled` |
| `payment_method` | varchar | `eft` or `payfast` (payfast = Stripe) |
| `payment_screenshot` | varchar null | Path to uploaded EFT proof |
| `payment_confirmed_at` | timestamp null | Set when admin confirms EFT |
| `order_type` | varchar | `pickup` or `delivery` |
| `notes` | text null | Customer logistics notes |
| `lat` / `lng` | decimal null | Customer delivery GPS coords |
| `customer_name` | varchar | Snapshot at time of order |
| `customer_email` | varchar | Snapshot |
| `customer_phone` | varchar | Snapshot |
| `customer_address` | text | Snapshot |
| `customer_city` | varchar | Snapshot |
| `customer_postal_code` | varchar null | Snapshot |

### `order_items`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | |
| `order_id` | bigint FK | → `orders.id` |
| `product_id` | bigint FK | → `products.id` |
| `quantity` | int | |
| `price` | decimal(10,2) | Historical price snapshot |
| `vat` | decimal(10,2) | Historical VAT snapshot (Always 0) |

### `products`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | |
| `uuid` | varchar(36) | |
| `name` | varchar(255) | FULLTEXT indexed |
| `slug` | varchar unique | Route key |
| `description` | text null | FULLTEXT indexed |
| `category_id` | bigint FK | → `categories.id` |
| `subcategory_id` | bigint FK null | → `categories.id` (self-join) |
| `brand_id` | bigint FK null | → `brands.id` |
| `sku` | varchar null | |
| `price` | decimal(10,2) | Base price |
| `vat_rate` | decimal(5,2) | Default 0.00 |
| `status` | varchar | `active`, `inactive` |
| `image` | varchar null | Path relative to `public/` |
| `is_featured` | boolean | Homepage flag |
| `is_top_selling` | boolean | Homepage flag |
| `is_new_arrival` | boolean | Homepage flag |

### `product_store_stocks`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | |
| `product_id` | bigint FK | → `products.id` |
| `store_id` | bigint FK | → `stores.id` |
| `quantity` | int | Physical on-hand |
| `incoming_quantity` | int default 0 | WMS: in transit |
| `reserved_quantity` | int default 0 | WMS: held for orders |
| `damaged_quantity` | int default 0 | WMS: damaged stock |

### `stores`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | |
| `name` | varchar | |
| `slug` | varchar unique | Route key |
| `address` | text | |
| `phone` | varchar null | |
| `lat` / `lng` | decimal null | For proximity calculations |
| `manager_id` | bigint FK null | → `users.id` |
| `image` | varchar null | |
| `is_active` | boolean | |

### `categories`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | |
| `name` | varchar | |
| `slug` | varchar unique | |
| `parent_id` | bigint FK null | Self-join for subcategories |
| `image` | varchar null | |

### `brands`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | |
| `name` | varchar | |
| `slug` | varchar unique | |
| `image` | varchar null | |

### VAT Handling
- The system currently calculates **NO VAT** (`vat = 0`) across both order totals and line items. Checkout is strictly base cost.

### Address Consolidation
- Users generally have a **Primary** address that serves as both Shipping and Billing.
- The system supports multiple addresses per user, with one marked as `is_default`.
- Automated flows (Social Login) ensure at least one primary address is created before checkout.

### `settings` (Key-Value Store)
| Key | Value Type | Purpose |
|:---|:---|:---|
| `stripe_enabled` | `0` or `1` | Enable Stripe checkout |
| `stripe_secret_key` | string | Stripe secret key (stored in DB not .env) |
| `stripe_public_key` | string | Stripe publishable key |
| `max_delivery_km` | numeric | Max delivery radius (default 300) |
| `invoice_company_name` | string | PDF invoice branding |
| `invoice_company_address` | string | |
| `invoice_company_phone` | string | |
| `invoice_company_email` | string | |
| `invoice_registration_number` | string | Company reg number |
| `invoice_footer_text` | string | Footer on PDF |
| `invoice_logo` | path string | Stored in `storage/app/public/settings/` |
| `invoice_eft_accounts` | JSON array | Array of bank account objects |
| `theme_primary_color` | string | Hex code (e.g., `#FF8C00`) |
| `theme_text_color` | string | Secondary text color |
| `theme_primary_text_color` | string | Text color ON primary background (auto-contrast) |
| `theme_background_color` | string | Main site background |
| `theme_surface_color` | string | Card/Surface background |
| `theme_muted_text_color` | string | Lower contrast text |

### `notifications`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | char(36) UUID PK | |
| `type` | varchar | Class name |
| `notifiable_type` | varchar | `App\Models\User` |
| `notifiable_id` | bigint | User's integer ID |
| `data` | json | Contains `order_id` (UUID string), `url` (UUID-based link) |
| `read_at` | timestamp null | |

### `activity_logs`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | |
| `action` | varchar | e.g., `status_update`, `payment_confirmed` |
| `model_type` | varchar | |
| `model_id` | bigint | |
| `old_values` | json null | |
| `new_values` | json null | |
| `user_id` | bigint FK null | Who made the change |

### `addresses`
| Column | Type | Notes |
|:---|:---|:---|
| `id` | bigint PK | |
| `user_id` | bigint FK | |
| `address_name` | varchar | e.g., "Default Site", "Head Office" |
| `address_line_1` | varchar | |
| `address_line_2` | varchar null | |
| `city` | varchar | |
| `postal_code` | varchar null | |
| `is_default` | boolean | |

---

## 3. Key Relationships

```
users           → orders          (hasMany via user_id)
users           → addresses       (hasMany via user_id)
users           ↔ stores          (belongsToMany via store_user pivot)
orders          → order_items     (hasMany via order_id)
order_items     → products        (belongsTo via product_id)
orders          → stores          (belongsTo via store_id)
products        ↔ stores          (hasMany stocks via product_store_stocks)
categories      → categories      (self-join via parent_id)
categories      → products        (hasMany via category_id)
brands          → products        (hasMany via brand_id)
```

---

## 4. Database Indexes

| Table | Column(s) | Type |
|:---|:---|:---|
| `products` | `name`, `description` | FULLTEXT |
| `products` | `slug` | UNIQUE |
| `products` | `category_id`, `brand_id`, `status` | INDEX |
| `orders` | `uuid` | UNIQUE |
| `orders` | `user_id`, `store_id`, `status` | INDEX |
| `users` | `uuid` | UNIQUE |
| `users` | `email` | UNIQUE |
| `addresses` | `user_id`, `postal_code` | INDEX |
| `stores` | `slug` | UNIQUE |
