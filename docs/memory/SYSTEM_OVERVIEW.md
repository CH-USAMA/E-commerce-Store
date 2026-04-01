# System Overview — Jabulani Store

## 1. Purpose
Jabulani Store is a modular, multi-branch Laravel eCommerce platform designed for retail businesses with physical branch locations. It bridges the gap between online sales and branch-specific inventory management (e.g., Click & Collect, Branch Delivery).

## 2. Core Modules

### 🛒 Product Catalog
- **Multi-Category Management**: Supports hierarchical categories (Parent/Sub).
- **Brand Management**: Distinct manufacturer/brand entities.
- **Dynamic Pricing**: VAT-inclusive pricing with standard 15% South African rate support.

### 📦 Inventory & Logistics
- **Branch-Specific Stock**: Stocks are not global; they are tracked per physical branch (`stores`).
- **Nearest Store Detection**: Integrated geolocation to find the closest fulfillment center to the customer.

### 💳 Order Lifecycle
- **Guest & Auth Checkout**: Supports both registered users and quick guest sales.
- **Hybrid Payments**: Integrated with **Payfast/Stripe** for cards and **EFT Settlement** for manual verification.
- **Fulfillment**: Support for **Warehouse Pickup** and **Radius-Limited Delivery** (calculated via Haversine distance).

### 🛠️ Administrative System
- **Carbon Pro Dashboard**: A premium, dark-mode administrative portal.
- **System Settings**: Global control over billing, payment gateways, and delivery rules.
- **Engagement Tools**: Marketing push (campaigns), Blogs, and Team management.
