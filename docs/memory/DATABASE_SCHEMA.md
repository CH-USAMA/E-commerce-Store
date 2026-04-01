# Database Schema & Relationships

The database is built on **Laravel 10/11 Migrations**, using **MariaDB/MySQL**.

## 1. Core Tables

### Catalog System
- **`categories`**: Hierarchical with `parent_id`. Uses **Slug** for routing.
- **`brands`**: Simple entity for product filtering. Uses **Slug** for routing.
- **`products`**: Core item data with **SKU** and **VAT** details. Uses **Slug** for routing.
- **`product_store_stocks`**: Critical Many-to-Many linking **Branch ↔ Product** with `quantity`.

### Sales & Customers
- **`users`**: Multi-role support (Manager, Staff, Customer). Uses **UUID** for routing.
- **`addresses`**: User-associated locations (Shipping/Billing).
- **`orders`**: Master transaction records with snapshot customer data. Uses **UUID** for routing.
- **`order_items`**: Line-item details with historical price/vat data.
- **`coupons`**: Discount code logic.

### Infrastructure & Engagement
- **`stores`**: Physical branch locations with geolocation and assigned manager. Uses **Slug** for routing.
- **`settings`**: Key-Value pairs for Stripe, Payfast, Max Delivery radius, and Invoice details.
- **`notifications`**: Persistent database-driven notifications for admins.
- **`campaigns`**: Logic for marketing outreach.

## 2. Key Relationships

- **Product ↔ Store**: Many-to-Many (`product_store_stocks`).
- **User ↔ Store Manager**: One-to-One (`stores.manager_id` → `users.id`).
- **User ↔ Store Staff**: Many-to-Many (`store_user` pivot).
- **Order ↔ Store**: One-to-Many (`orders.store_id` → `stores.id`).
- **Order ↔ Items**: One-to-Many (`orders.id` → `order_items.order_id`).
- **Category ↔ Subcategory**: Recursive (`categories.parent_id` → `categories.id`).
