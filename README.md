# Jabulani Hardware eCommerce System

A professional, multi-branch Laravel eCommerce platform designed for high-performance hardware retail. Features proximity-based branch detection, multi-manager store management, and a sleek, industrial admin interface.

## 🚀 Key Features

- **Multi-Store Architecture**: Manage multiple physical branches from a single dashboard.
- **Proximity Detection**: Automatically detects the nearest branch for customers using geolocation (Haversine Formula).
- **Advanced Admin Panel**: Sleek, scrollable sidebar with logical grouping (Sales, Catalog, Network, Content).
- **Multi-Manager Support**: Assign multiple managers to a single store with role-based access control.
- **Product Management**: Bulk import/export via CSV, dynamic inventory tracking, and SEO-optimized categories.
- **Premium UI**: Modern dark-themed checkout with high-contrast error handling and industrial aesthetics.
- **CMS Integration**: Integrated blog, banner management, services, and team showcases.

## 🛠️ Tech Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Database**: MySQL (Optimized with indexing)
- **Frontend**: Blade, Bootstrap 5, FontAwesome 6, Select2
- **Infrastructure**: Apache (optimized `.htaccess`), Hostinger ready

## 📦 Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/your-repo/jabulani-system.git
   cd jabulani-system
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configure Environment**:
   - Copy `.env.example` to `.env`
   - Set `DB_CONNECTION=mysql` and provide your credentials.
   - Run `php artisan key:generate`

4. **Database Setup**:
   ```bash
   php artisan migrate --seed
   ```

5. **Storage Link**:
   ```bash
   php artisan storage:link
   ```

## 📈 Import/Export Products

- Go to **Catalog Management > Products**.
- Use the **Export CSV** button to download the catalog.
- Use **Import CSV** to bulk create/update products. The CSV should follow the header structure: `ID, Name, Slug, SKU, Description, Price, VAT Rate, Category, Brand, Featured`.

## 🔒 Production Deployment

- Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
- The root `.htaccess` handles redirection to `/public`.
- SSL/HTTPS is automatically enforced via `.htaccess` and `AppServiceProvider`.

## 🤝 Project Structure

- `app/Http/Controllers/Admin`: Super Admin functionalities.
- `app/Http/Controllers/Branch`: Store Manager portal.
- `app/Models`: Core entities (Store, Product, User, etc.).
- `resources/views/frontend`: Customer-facing Blade templates.
- `resources/views/layouts`: Shared admin and frontend layouts.

---
© 2026 Jabulani Hardware. All Rights Reserved.
