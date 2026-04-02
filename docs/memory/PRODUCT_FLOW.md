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
| `status` | required | `active` or `inactive` |
| `image` | nullable, image file | Stored in `public/storage/products/` via `store('products', 'public')` |
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
