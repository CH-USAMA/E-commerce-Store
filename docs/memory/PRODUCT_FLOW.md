# Product Flow & Catalog Life Cycle

The following outlines the logical flow for product management and discovery within the Jabulani system.

## 1. Creation Phase (Admin)
- **Controller**: `App\Http\Controllers\Admin\ProductController`
- **Logic**: 
    - Admin creates a product record in `products`.
    - Uploads a primary image (stored in `public/storage/products`).
    - Selects **Category** and **Brand**.
    - Sets **VAT-inclusive Price** (default VAT 15% South African rate).
    - Status is set to **Active/Inactive**.

## 2. Inventory Allocation (Branch Stock)
- **Logic**: 
    - Products must be mapped to physical branches to be available for sale.
    - Stocks are managed in `product_store_stocks`.
    - **Quantity** is branch-specific. This allows "Store A" to show a different stock count than "Store B".

## 3. Customer Discovery (Frontend)
- **Controller**: `App\Http\Controllers\HomeController`
- **Logic**: 
    - **Geolocation**: System detects customer's location via browser GPS.
    - **Store Selection**: `StoreService` finds the nearest physical branch.
    - **Filtering**: Customers view categories, brands, or search for products.
    - **Availability**: Products are shown based on their **Active** status and branch-level availability.
