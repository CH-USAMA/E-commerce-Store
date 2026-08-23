# Product Flow & Catalog Life Cycle

---

## 1. Creation Phase (Admin)

**Controller**: `App\Http\Controllers\Admin\ProductController`

| Field | Rule | Notes |
|:---|:---|:---|
| `name` | required | Used to auto-generate slug |
| `slug` | auto-generated | `Str::slug($name)`, must be unique |
| `category_id` | required | Parent category |
| `subcategory_id` | nullable | Child of parent category |
| `brand_id` | nullable | |
| `sku` | nullable | |
| `price` | required, numeric | VAT-inclusive |
| `vat_rate` | default 15 | South African standard rate |
| `status` | `required\|in:active,inactive` | Selectable on both admin forms. **Enforced** by `Product::scopeActive()` on every public query — inactive products 404 on their own URL and vanish from listings, search, the homepage and "recently viewed". *Was a dead field until 2026-08-17: not fillable, not validated, no form control, filtered nowhere — see `KNOWN_ISSUES.md`.* |
| `image` | `nullable\|mimes:jpg,jpeg,png,gif,webp,avif\|max:8192` | Stored as `products/{uuid}.{ext}` in **`public/products/`** — the `public` disk root is overridden to `public_path('')`, so there is no `/storage/` segment. Render with `image_url()`, never `asset()`. See `ARCHITECTURE.md § 7` |
| Homepage flags | boolean | `is_featured`, `is_top_selling`, `is_new_arrival` |

---

## 2. Inventory Allocation — WMS States

**Table**: `product_store_stocks`
**Managing**: `Branch\StockController` (per branch) + `Admin\ProductController` (master view)

| WMS Column | Meaning | Who Manages |
|:---|:---|:---|
| `quantity` | Physical on-hand (sellable) | Admin / Branch Manager |
| `incoming_quantity` | In transit from supplier | Admin |
| `reserved_quantity` | Held for pending orders | Admin |
| `damaged_quantity` | Written off / damaged | Admin / Branch Manager |

**Available stock formula**: `quantity - reserved_quantity`

A product shows as "in stock" on frontend only if the assigned branch has `quantity > 0`.

---

## 3. Bulk CSV Import/Export

**Import**: `Admin\ProductController@import` (`POST /admin/products/import`)
**Export**: `Admin\ProductController@export` (`GET /admin/products/export`)

### CSV Import Column Format
```
Name | SKU | Price | Category | Brand | Status | {Store Name} Physical | {Store Name} Incoming | {Store Name} Reserved | {Store Name} Damaged
```
- One row per store stock relationship
- Store columns are dynamically generated from `stores` table
- Existing products matched by `name` → updated; new → created

### CSV Export Format
- One row per `product_store_stocks` record
- Headers: `Product Name, SKU, Price, Category, Brand, Status, Store, Physical, Incoming, Reserved, Damaged`

---

## 4. Customer Discovery (Frontend)

**Controller**: `App\Http\Controllers\HomeController`

| Feature | Mechanism |
|:---|:---|
| Category browsing | `GET /products?category={slug}` |
| Brand filtering | `GET /products?brand={slug}` |
| Search | `GET /search?q={term}` → MySQL FULLTEXT on `products.name` + `products.description` |
| Featured/New/Top | Homepage carousels, filtered by boolean flags |
| Geolocation | JS `navigator.geolocation` → `POST /cart/nearest-store` → `StoreService@findNearestStore` |
| Product detail | `GET /product/{slug}` → `HomeController@productDetail` |

---

## 5. Image Management

- Upload: Admin form → `public` disk → `storage/products/{uuid}_{filename}`
- Display: `asset($product->image)` in views
- Fallback: `public/images/placeholder.webp` when image null or file missing (checked via `file_exists(public_path($product->image))`)
- Deletion: Old image file deleted when product updated with new image (`Storage::disk('public')->delete($oldImage)`)

---

## 6. Slug Generation

```php
// Auto on creation in ProductController@store:
$data['slug'] = \Str::slug($request->name);
// Uniqueness enforced via DB unique index on products.slug
// If conflict: append -2, -3 etc. (manual or validated)
```

---

## 7. FULLTEXT Search

```php
// In HomeController@search:
Product::whereRaw("MATCH(name, description) AGAINST(? IN BOOLEAN MODE)", [$query])
    ->where('status', 'active')
    ->get();
```
Fallback to `LIKE` if no results returned.

---

## Product Sizes (variants)

*Added 2026-08-24.* A product is either a **single product** or a **product with sizes**,
switched by `products.has_variants` on the admin product form. Sizes live in
`product_variants` (label + price + optional code + active flag + order).

**Why not separate products.** Both workarounds were visible in the live catalog and both
failed: "Lintels from 1M to 6M" was one product with the range in its name, so no price could
be shown and nothing could be picked; "Paint Brush 50MM / 100MM / 150MM" were three products
that sort 100, 150, 50 alphabetically. With 292 products, one lintel in six lengths would have
meant six cards in the category grid and six descriptions to keep in sync.

**Read `Product::offersVariants()`**, not `has_variants` — it also requires a live size, so a
fully-deactivated range degrades to a simple product rather than rendering an empty picker.

Storefront behaviour:
- **Listing cards** show `display_price` (the cheapest size), prefixed "From" when
  `hasPriceRange()`. The card CTA becomes **Choose Size** and links to the product page:
  adding to the cart from a card would have to guess a size and therefore a price, and a
  WhatsApp enquiry with no size puts the customer back to typing "the 4.8m one".
- **Product page** renders a size picker; the price and the WhatsApp message both react to it.
  While `hide_pricing = 1` that WhatsApp message is the whole point of the feature — it carries
  `- size 4.8m` so the enquiry arrives complete.
- **Cart / checkout / orders** carry the size end to end; see `ARCHITECTURE.md` for the cart
  key scheme and `ORDER_FLOW.md` for the order snapshot.

**Search** matches size labels and per-size codes as well as name/description/SKU, so
"lintel 4.8" finds the product. Note the search clause is **grouped**: before this change the
bare `orWhere('sku', ...)` OR-ed past the `active()` and category filters and could return
inactive or out-of-category products on a SKU match.

**The CSV importer does not handle sizes.** Its column map is fixed (0:ID … 9:Featured) and
was left alone. Bulk-loading sizes is a follow-up, not something the current importer does
silently.

