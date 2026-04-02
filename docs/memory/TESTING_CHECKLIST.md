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
- [ ] Image stored in `public/storage/products/`
- [ ] Status `active` — product appears on frontend
- [ ] Status `inactive` — product hidden from frontend

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
