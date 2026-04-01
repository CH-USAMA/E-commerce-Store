# Admin Panel — Carbon Pro Dashboard

The administrative panel is built with the **Carbon Pro Design System**, focusing on a high-contrast dark theme.

## 1. Key Controllers
- **`App\Http\Controllers\Admin\OrderController`**: Master order fulfillment and PDF invoice streaming.
- **`App\Http\Controllers\Admin\ProductController`**: Catalog and brand management.
- **`App\Http\Controllers\Admin\SystemController`**: Global settings: Stripe, Payment, Invoice branding, and EFT management.
- **`App\Http\Controllers\Admin\MarketingController`**: Multi-channel campaign logic.
- **`App\Http\Controllers\Admin\StoreController`**: Branch network management.

## 2. Admin Capabilities
- **Order Audit**: Manual verification of EFT proof-of-payment.
- **Invoice & Site Branding**: Dynamic rebranding of PDF invoices and site-wide contact info (Footer/Contact Page) including logo, contact details, and bank accounts.
- **Branch Management**: Assigning specific staff members (via `store_user` pivot) to manage physical store operations.
- **Website Content**: Full CRUD for Banners, Services, Blog Posts, and Gallery items.
- **Inventory Control**: Adjusting branch-specific stock counts for all active products.

## 3. Navigation Structure (Sidebar)
- **Operations**: Dashboard, Orders.
- **Catalog**: Products, Categories, Brands.
- **Network**: Stores, Staff.
- **Website**: Banners, Services, Blog, Gallery, Team.
- **System**: Payment Settings, Invoice Settings, Marketing Push.
