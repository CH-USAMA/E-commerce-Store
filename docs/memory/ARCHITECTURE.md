# Technical Architecture — Jabulani Store

The application follows the **Laravel 10/11 MVC pattern** with a custom design system for the admin portal.

## 1. MVC Breakdown

### **Models (`app/Models`)**
- Uses **Eloquent ORM** for database interactions.
- Key Models: `Product`, `Order`, `Store`, `Category`, `Setting`, `OrderItem`.
- Most include `$fillable` for secure bulk assignment and `relationships` (HasMany, BelongsTo, BelongsToMany).

### **Controllers (`app/Http/Controllers`)**
- **Admin**: CRUD logic with extensive validation and redirect responses.
- **Frontend**: Simplified controllers for cart logic (`CartController`), search, and guest checkout.
- **API**: Some JSON response methods for dynamic cart counts and store locations.

### **Views (`resources/views`)**
- **Admin**: Blade components combined with the Carbon Pro CSS framework.
- **Frontend**: Standard Blade with Alpine.js specifically for interactive cart UI.
- **PDF**: Uses **DomPDF** to stream VAT invoices.

## 2. Technical Stack
- **PHP 8.2+**: Latest Laravel framework.
- **JavaScript**: Alpine.js (reactive frontend elements), Select2 (admin dropdowns).
- **CSS**: Tailwind CSS for frontend, Vanilla CSS for Admin (Carbon Pro).
- **Service Layer**: 
    - `StoreService`: Geolocation logic and branch detection.
    - `ActivityLog` (Model+Helper): Audit logging for order status changes and stock movements.
    - `Mail`: Support for transactional order emails.

## 3. Directory Layout
- `app/Http/Controllers/Admin`: Back-office logic.
- `app/Http/Controllers/User`: Front-end user journey.
- `public/storage`: Product images, banners, and payment proof documents.
- `database/migrations`: Versioned schema evolution.
- `docs/memory`: Project memory documentation assets.
