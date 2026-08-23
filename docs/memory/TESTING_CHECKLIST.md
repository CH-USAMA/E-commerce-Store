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

## 7b. Homepage Hero Banners & Content Cache

### Count — no cap exists
- [ ] Add a banner → it appears on the homepage **immediately**, no waiting, no cache clear
- [ ] Slide count and dot count both equal the number of rows in `banners`
- [ ] Delete a banner → it disappears immediately

Before 2026-08-17 the `banners` key was cached for an hour with no invalidation, so a new
banner stayed invisible for up to 60 minutes. Live had 5 rows rendering 4 slides.

### Cache invalidation (same bug class, 9 keys)
- [ ] Editing a **store**, **brand**, **category**, **team member** or **gallery item**
      shows on the public page immediately
- [ ] Editing a blog post shows immediately on its detail page
- [ ] **Changing a blog post's slug** → the *old* URL no longer serves the pre-edit copy

### Mobile art direction
- [ ] Upload only a desktop image → phone shows it (cropped centre), no error
- [ ] Upload a mobile image too → DevTools ▸ Network at ≤768px shows the **mobile** file
      downloaded and the desktop file **not** downloaded
- [ ] Resize above 768px → desktop file is used
- [ ] Tick "remove mobile image" → falls back to desktop, file deleted from disk
- [ ] Banner index shows "mobile set" / "desktop only" per row

### Hero performance
- [ ] Only the **first** slide is `loading="eager"` with `fetchpriority="high"`
- [ ] Remaining slides are `loading="lazy"`
- [ ] First paint does not download every banner at full size

### Banner ordering
- [ ] Admin banners list is in the same order as the homepage slider
- [ ] Up arrow moves a banner one position earlier; down arrow one later
- [ ] Up is disabled on the first row, down on the last
- [ ] Reordering shows on the homepage immediately (cache invalidated by the swap)
- [ ] Creating a banner with **Display Order blank** puts it **last**, not first
- [ ] Setting Display Order by hand on the edit form takes effect
- [ ] Two banners sharing a Display Order still render in a stable order (`id` breaks the tie)

### Category ordering
- [ ] `admin/categories` lists parents with their sub-categories nested, numbered `1`, `1.1`, `2`, …
- [ ] The list is **not** paginated — every category is on one page, so both arrows always
      have a visible neighbour
- [ ] Up arrow moves a category one position earlier; down arrow one later
- [ ] Up is disabled on the first row of a sibling group, down on the last — **including for
      sub-categories**, so the first child of a parent refuses "up" even though other
      categories sort before it globally
- [ ] Moving a sub-category never reorders the top level, and vice versa
- [ ] Reordering shows on the homepage "Shop By Category" grid immediately (`categories_top`
      invalidated by the swap)
- [ ] The `/products` sidebar shows the same order — parents **and** their sub-categories
- [ ] The category dropdowns on the admin product create/edit forms use the same order
- [ ] Creating a category with **Display Order blank** puts it **last in its own group**, not first
- [ ] Setting Display Order by hand on the edit form takes effect
- [ ] Editing a category and changing its **parent** with Display Order blank lands it at the
      end of the new parent's list, not at whatever position it held under the old one
- [ ] Two categories sharing a Display Order still render in a stable order (`id` breaks the tie)

### Seasonal specials
- [ ] `admin/specials` lists the flyers in the same order `/specials` shows them
- [ ] Uploading a flyer creates BOTH images: the full file stored untouched, plus a
      generated WebP thumbnail at most 1400px on its longest edge and several times smaller
- [ ] The list marks each row "compressed" or "full size"; "full size" means the thumbnail
      could not be generated and the grid is serving the flyer (check `storage/logs/`)
- [ ] Clicking a card opens the **full** flyer in the lightbox, not the thumbnail
- [ ] Unchecking "Show on the website" removes it from `/specials` but keeps the row
- [ ] Up/down arrows reorder; disabled at the first and last row
- [ ] Creating with **Display Order blank** puts it last, not first
- [ ] Replacing a flyer regenerates the thumbnail and deletes the old **uploaded** files
- [ ] Deleting a seeded special does **not** delete `public/images/*` — those are tracked
      git assets shared with other pages (`deleteIfOwned` guards this)
- [ ] Replacing the **Page Header Image** changes `/specials` immediately (own cache key)
- [ ] `/specials` and `/track-order` make no failed image request (the old
      `qumbu_special_compressed.webp` 404)

### Product sizes (variants)
- [ ] Product form: ticking **"This product comes in different sizes"** reveals the size rows
- [ ] Adding sizes, saving, re-opening: rows come back in the order entered, not alphabetical
- [ ] Editing a size keeps its id (order history joins on it) — check the row is updated, not
      deleted and recreated
- [ ] Removing a row in the browser deletes that size on save
- [ ] Two sizes with the same name are refused with a readable message, and the product falls
      back to "single product" rather than saving a broken set
- [ ] **Unticking the switch keeps the sizes** — re-tick and they are all still there
- [ ] Deactivating EVERY size makes the product behave as a simple product (no empty picker)
- [ ] Listing card shows "From R x" (the cheapest size) and a **Choose Size** button that
      goes to the product page
- [ ] Product page: picking a size updates the price
- [ ] **WhatsApp enquiry contains the chosen size** — this is the whole feature while
      `hide_pricing = 1`
- [ ] Searching a size ("lintel 4.8") finds the product
- [ ] Searching still excludes inactive and out-of-category products

### Product sizes — cart & orders (needs `hide_pricing = 0`; restore it afterwards)
- [ ] Adding a sized product without choosing a size is refused ("Please choose a size first")
- [ ] Two different sizes of one product are **two separate cart lines**, each showing its size
- [ ] Cart line price is the **size's** price, not the product's base price
- [ ] Changing quantity / removing affects only that size's line
- [ ] Checkout summary shows the size; the order total matches the per-size prices
- [ ] The placed order, the invoice PDF, the confirmation email, the tracking page and the
      admin order view all show "Product (size)"
- [ ] **Deleting that size afterwards** leaves the order reading correctly (label snapshot)
- [ ] "Order again" on a sized order restores the right sizes, not base-priced products
- [ ] A cart holding a size that is then deleted drops that line with a message, and does
      **not** silently reprice it to the base price
- [ ] An old cart from before this change (integer keys) still loads — legacy key support

### Footer credit
- [ ] "Developed by Jabulani Tech Solutions" appears in the storefront footer and the
      customer portal footer, linking to `https://agency.jabulanigroupofcompanies.co.za/`
- [ ] The link opens in a new tab and carries `rel="noopener noreferrer"`

---

## 7c. Recently Viewed & Order Again

### Recently viewed
- [ ] Visit 3 products → the strip on a product page shows them, newest first
- [ ] The product being viewed is **not** in its own strip
- [ ] Revisiting a product moves it to the front and does not duplicate it
- [ ] Works while logged out (session-based)
- [ ] Deactivating a product removes it from the strip without clearing the session
- [ ] Prices stay hidden in the strip while `hide_pricing = 1` (it reuses `product_card`)

### Order again — inquiry mode (`hide_pricing = 1`)
- [ ] Button appears on `/user/orders` rows and on the order detail page
- [ ] Clicking it opens WhatsApp pre-filled with quantities and product names
- [ ] The cart stays **empty** — nothing is added in this mode

### Order again — pricing enabled (`hide_pricing = 0`)
- [ ] Clicking it lands on `/cart` with the order's items and quantities
- [ ] Items merge into an existing cart rather than replacing it
- [ ] A deleted/deactivated product is skipped, with a message naming how many
- [ ] An order whose items are all unavailable returns an error and changes nothing
- [ ] Another user's order returns **403**

> Switching `hide_pricing` must change the behaviour with **no code or view change** — that
> is the point of the design. If it doesn't, the branch in
> `User\OrderController::reorder()` is wrong.

## 7d. Product Status
- [ ] Set a product Inactive in admin → its URL 404s
- [ ] It disappears from listing, search, homepage sections and recently-viewed
- [ ] Set it back to Active → reachable and listed again
- [ ] Status persists on save (it was silently dropped before 2026-08-17 — not in `$fillable`)

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
