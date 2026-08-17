# Testing Checklist — Jabulani Store

> Run these checks after any related code change. Reference before declaring any feature "done".

---

## 1. Authentication & User Management

### Registration
- [ ] Register with name, email, password
- [ ] Verification email sent (check log locally, SMTP on prod)
- [ ] Verify email via link → redirected to user dashboard
- [ ] Unverified user cannot access `/user/*`

### Login
- [ ] Login with valid credentials → role-appropriate redirect
  - Admin → `/admin/dashboard`
  - Manager → `/branch/dashboard`
  - User → `/user/dashboard`
- [ ] Invalid credentials → back with error (no user enumeration)
- [ ] Throttle: 6 failed attempts in 1 minute → rate limit error

### Google OAuth
- [ ] Click "Login with Google" → Google consent screen
- [ ] Callback → user created with `role = 'user'` and `email_verified_at` set
- [ ] Existing Google user → tokens updated, logged in
- [ ] Redirected to user dashboard (or `url.intended`)

### Profile Completion Gate
- [ ] New user without phone → redirect to `/profile/complete`
- [ ] Complete profile → proceed to checkout / dashboard

---

## 2. Checkout & Orders

### EFT Checkout (Auth User)
- [ ] Add products to cart
- [ ] Go to `/checkout` → all 3 steps work
- [ ] Select "Bank EFT" payment method
- [ ] Fill delivery details, select branch
- [ ] Submit → order created with `status = awaiting_payment`
- [ ] Admin notification dispatched (check DB `notifications` table)
- [ ] Order appears in Admin > Orders
- [ ] Order appears in User > Orders

### EFT Checkout (Guest)
- [ ] Cart as guest → `/checkout/auth` → click "Continue as Guest"
- [ ] Fill name, email, phone + delivery details
- [ ] Submit → guest order created with `user_id = null`
- [ ] Order visible in Admin > Orders
- [ ] Guest data appears in Admin > Guests

### Stripe Checkout (Auth User)
- [ ] Select "Stripe Online" payment
- [ ] Submit → redirected to `checkout.stripe.com`
- [ ] Complete payment → redirect to `/order-success?order_number=...`
- [ ] `orderSuccess()` sets status → `processing`
- [ ] Confirmation email dispatched (with PDF invoice attached)

### Stripe Not Configured
- [ ] If `stripe_enabled = 0` OR `stripe_secret_key` empty → order created, warning shown, no redirect to Stripe

### Delivery Radius Enforcement
- [ ] Customer > 300km from store → order_type forced to `pickup`
- [ ] Flash info message shown
- [ ] Frontend distance indicator shows warning

### Admin — EFT Confirmation
- [ ] Go to Admin > Orders > open EFT order
- [ ] Click "Confirm Payment" button → appears only when `status = awaiting_payment`
- [ ] Status changes to `processing`, `payment_confirmed_at` set
- [ ] Customer notification dispatched
- [ ] Confirmation email sent to `customer_email`

### Admin — Status Update
- [ ] Change status via dropdown → customer notified
- [ ] `ActivityLog` record created

### Admin — Invoice PDF
- [ ] Click Invoice button on order detail
- [ ] PDF streams with correct order data and branding

### Order URL Security
- [ ] All order URLs use UUID — never integer ID
- [ ] Test: `/admin/orders/{integer}` returns 404 (model not found)

---

## 3. Product & Inventory

### Product Creation
- [ ] Create product with all fields including image
- [ ] Slug auto-generated from name
- [ ] Image stored in `public/products/` (**not** `public/storage/products/` — the `public`
      disk root is overridden to `public_path('')`; see `ARCHITECTURE.md § 7`)
- [ ] Thumbnail renders on `admin/products/index` **and** on the storefront card
- [ ] Status `active` — product appears on frontend
- [ ] Status `inactive` — product hidden from frontend

### Product Editing (regression — was fully broken before 2026-08-17)
- [ ] The **Edit** button on `admin/products` opens the form (was a 404 — see below)
- [ ] Saving with at least one store present succeeds (the `stocks.*` rule rejected every
      save while stores existed)
- [ ] Per-store quantity / incoming / reserved / damaged all persist
- [ ] Replacing the image deletes the previous file, leaving no orphan in `public/products/`

### Branch Stock Management
- [ ] Update stock for a branch → `product_store_stocks` updated
- [ ] WMS states: physical, incoming, reserved, damaged all save correctly

### CSV Import
- [ ] Upload valid CSV → products created/updated
- [ ] Invalid CSV → error message, no partial import

### CSV Export
- [ ] Download CSV → correct columns, one row per store stock

### Search
- [ ] Search for product name → FULLTEXT results
- [ ] Search for partial keyword → results returned
- [ ] Empty search → no crash

---

## 4. Admin Panel

### Navigation
- [ ] All sidebar links work (Operations, Catalog, Network, Website, System)
- [ ] Design system CSS loads (`/css/design-system.css` returns 200)

### Settings — Payments
- [ ] Save Stripe keys → stored in `settings` table
- [ ] `stripe_enabled` toggle works

### Settings — Invoice Branding
- [ ] Update company name/address → reflected on PDF invoice
- [ ] Upload logo → appears on PDF
- [ ] Add/remove EFT accounts → reflected on checkout page

### Notifications Bell
- [ ] Badge count shows unread count
- [ ] Click notification → goes to correct UUID-based order URL
- [ ] "Mark all read" clears badge

---

## 5. Branch Manager Portal

### Orders
- [ ] Manager sees only their branch's orders
- [ ] Status update form works → uses UUID in form action
- [ ] Cannot see other branches' orders

### Stock Management
- [ ] Update stock counts for assigned branch products

---

## 6. Frontend Public Pages

- [ ] Home page loads with all sections (hero, featured, categories, banners)
- [ ] Product listing and filters (category, brand) work
- [ ] Product detail page with correct price and images
- [ ] Contact page shows dynamic company info from settings
- [ ] Footer shows dynamic phone from settings
- [ ] Blog listing and detail pages
- [ ] Order tracking by order number (public)
- [ ] Cart add/remove/update via AJAX
- [ ] Cart count updates in header

---

## 7. Image Uploads (all admin modules)

Applies to banners, products, categories, brands, gallery, services, team, stores, blog
feature images and the invoice logo — they all share `ValidatesImageUploads`.

### Formats
- [ ] Upload **`.webp`** → accepted (rejected on banners before 2026-08-17)
- [ ] Upload **`.avif`** → accepted
- [ ] Upload `.jpg`, `.png`, `.gif` → accepted
- [ ] Upload **`.svg`** → rejected. Deliberate; do not "fix" (`SECURITY.md § 9`)
- [ ] Rename a `.txt` to `.png` and upload → rejected with
      *"Please upload a JPG, PNG, GIF, WebP or AVIF image."* (validation is by content, not
      filename)
- [ ] An iPhone `.heic` is not offered by the file picker (`accept` list)

### Error feedback — the "stuck UI" regression
- [ ] A rejected upload shows a **visible red alert**, not a silent bounce
- [ ] Every other field retains its typed value, including per-store stock quantities
- [ ] The message names formats in plain language, not a list of MIME tokens

### Submit locking — the hanging-request regression
- [ ] On submit the button disables and reads "Uploading…"
- [ ] Double-clicking Save does not create a second pending request
      (DevTools → Network: exactly one request. Two would deadlock on the file-session
      `flock()` and hang forever — see `ADMIN_PANEL.md § 1`)
- [ ] A **delete** button's `confirm()` → Cancel leaves the button enabled and usable
- [ ] Navigating back to a submitted form leaves it usable, not stuck disabled

### Size
- [ ] A file over 8MB is rejected with *"Image must be 8MB or smaller."* — a readable
      message, **not** a 419 Page Expired
- [ ] A ~5MB file uploads successfully (live allows 1536M; only the 8MB app rule binds)
- [ ] Gallery accepts a 4MB image (its old `max:5120` rule was unsatisfiable locally)

### Display & storage
- [ ] The uploaded image appears in the admin index thumbnail and on the storefront
- [ ] Legacy `images/*` records still render (regression check for `image_url()`)
- [ ] A filename containing `+` or a space renders correctly — the URL should show `%2B` /
      `%20`, not a broken image
- [ ] Invoice **PDF** embeds the logo (uses `image_path()`; DomPDF cannot fetch an http src)
- [ ] Replacing an image deletes the old file; deleting the record deletes its file
- [ ] Two uploads within the same second do not overwrite each other (filenames are UUIDs)

---

## 8. Route Key Binding (slug/uuid models)

`Product`, `Category`, `Brand`, `Store` bind by `slug`; `Order`, `User` bind by `uuid`.
Passing `->id` to `route()` yields a **404**, not a leaked integer — it fails only at
runtime, so it cannot be caught by a syntax check.

- [ ] From each of `admin/products`, `admin/categories`, `admin/brands`, `admin/stores`:
      click **Edit** → form opens (200). All but stores were 404 before 2026-08-17
- [ ] On each edit form, **Save** succeeds (the form `action` must also use the model)
- [ ] Delete works from both the index and the edit page
- [ ] No admin URL contains an integer id

Quick automated sweep — render an index, follow its own links:

```php
$html = $this->actingAs($admin)->get('/admin/products')->getContent();
preg_match_all('#href="([^"]*/admin/products/[^"/]+/edit)"#', $html, $m);
$this->assertSame(200, $this->actingAs($admin)->get($m[1][0])->getStatusCode());
```
