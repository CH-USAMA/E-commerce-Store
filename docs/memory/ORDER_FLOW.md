# Order Flow & Checkout Life Cycle

---

## 1. Cart Management

**Controller**: `App\Http\Controllers\CartController`
**Storage**: Session (`cart` key) + DB sync (`users.cart_data` JSON for auth users)

| Event | Logic |
|:---|:---|
| Add to cart | `CartController@add` → session `cart[$productId] += $quantity` → synced to DB |
| Update quantity | `CartController@update` → replaces qty, 0 = remove |
| Remove item | `CartController@remove` → `unset($cart[$productId])` |
| Login | Session cart merged with DB cart (session takes precedence if non-empty) |
| Logout | Cart persisted in `users.cart_data` for next session |

---

## 2. Checkout Entry Point

**Route**: `GET /checkout/auth` → `CartController@checkoutAuth`

| Scenario | Redirect |
|:---|:---|
| Auth + cart | → `/checkout` |
| Guest (no session flag) | → `/checkout/auth` (shows login/guest choice) |
| Guest (has `guest_checkout` session) | → `/checkout` |
| Empty cart | → `/products` with error |
| Auth + unverified email | → `/email/verify` with warning |

---

## 3. Order Creation — `CartController@processCheckout`

**Route**: `POST /checkout` | **Middleware**: `profile.complete`

### Validation Rules
```
customer_name:     required|string|max:255
customer_email:    required|email|max:255
customer_phone:    required|string|max:20
customer_address:  required|string
customer_city:     required|string|max:100
customer_postal_code: nullable|string|max:10
payment_method:    required|in:eft,payfast    ← payfast = Stripe in UI
order_type:        required|in:pickup,delivery
lat/lng:           nullable|numeric
payment_screenshot: nullable|file|mimes:jpg,jpeg,png,pdf|max:2048
```

### Order Number Format
`JB-YYYYMMDD-XXXXXX` where XXXXXX is `Str::random(6)` uppercased.

### Delivery Radius Check (Server-Side)
```php
if (order_type === 'delivery' && lat && lng) {
    $dist = Haversine(customer_lat, customer_lng, store_lat, store_lng);
    $max = Setting::get('max_delivery_km') ?? 300;
    if ($dist > $max) → override order_type to 'pickup', flash info message
}
```

### Store & Cart Category Constraints
- **Pure Stone Cart**: If an order ONLY contains products from the "Crush Stone" category, the order should ideally be fulfilled by `Jabulani Quarries`.
- **Mixed Cart Fallback**: If an order contains "Crush Stone" products AND any other non-stone products (e.g., hardware), it cannot be sent to a Quarry. The nearest store logic automatically falls back to `Jabulani Hardware Tsolo` to handle mixed inventory.

### VAT Handling
- The system currently calculates **NO VAT** (`vat = 0`) across both order totals and line items. Checkout is strictly base cost.


### Status Assignment at Creation
| Payment Method | Initial Status |
|:---|:---|
| `eft` | `awaiting_payment` |
| `payfast` (Stripe) | `pending` |

### EFT Screenshot Upload
Stored at `public/payments/{timestamp}_{originalname}`. Path saved to `orders.payment_screenshot`.

---

## 4. Payment Routing After Order Creation

### Path A — EFT
```
Order created (status: awaiting_payment)
→ Redirect to order.success page
→ Customer performs bank transfer manually
→ Admin reviews EFT screenshot → clicks "Confirm Payment"
→ OrderController@confirmPayment:
    - order.status → 'processing'
    - order.payment_confirmed_at → now()
    - ActivityLog::record('payment_confirmed', ...)
    - User notified via OrderStatusChangedNotification (DB + email)
    - OrderConfirmed mail dispatched to customer_email (with PDF invoice attached)
```

### Path B — Stripe (value: `payfast`)
```
Order created (status: pending)
→ CartController checks:
    - Setting::get('stripe_enabled') === '1'
    - Setting::get('stripe_secret_key') is not empty
→ If valid: Stripe\Checkout\Session::create([...]) → redirect to stripe.com
→ On stripe success: redirect to /order-success?order_number=JB-...
→ orderSuccess() detects payment_method === 'payfast' AND status === 'pending'
    → status → 'processing'
    → OrderConfirmed mail dispatched
→ If Stripe fails: order still recorded, warning flash shown, redirect to success page
```

---

## 5. Order Status Lifecycle

```
awaiting_payment  ← EFT orders start here
     ↓ (admin confirms EFT)
pending           ← Stripe orders start here
     ↓ (auto on Stripe callback, or admin update)
processing
     ↓
shipped
     ↓
delivered
     ↓
completed

(any stage → cancelled)
```

**Status change actions** (`OrderController@update`):
- `ActivityLog::record('status_update', ...)`
- `$order->user->notify(new OrderStatusChangedNotification($order))` — DB notification with UUID URL

---

## 6. Administrative Verification

**Controller**: `App\Http\Controllers\Admin\OrderController`

| Action | Method | Route | Guard |
|:---|:---|:---|:---|
| List all orders | `index()` | `GET /admin/orders` | `role:admin` |
| View order detail | `show(Order $order)` | `GET /admin/orders/{uuid}` | `role:admin` |
| Update status | `update()` | `PUT /admin/orders/{uuid}` | `role:admin` |
| Confirm EFT payment | `confirmPayment()` | `POST /admin/orders/{uuid}/confirm-payment` | `role:admin` |
| Download invoice PDF | `invoice()` | `GET /admin/orders/{uuid}/invoice` | `role:admin` |
| Export CSV | `export()` | `GET /admin/orders/export` | `role:admin` |

**Confirm Payment guard**: Only processes if `$order->status === 'awaiting_payment'`, else returns error.

---

## 7. Branch Manager Order Flow

**Controller**: `App\Http\Controllers\Branch\OrderController`
- Managers only see orders assigned to their branch
- Can update status (`pending` → `processing` → `shipped` → `completed` / `cancelled`)
- Route: `POST /branch/orders/{uuid}/status`

---

## 8. Notification System

| Event | Notification Class | Channel | Recipients |
|:---|:---|:---|:---|
| New order created | `NewOrderNotification` | DB | All admin users |
| Order status changed | `OrderStatusChangedNotification` | DB | Order's user |

Notification `data` payload:
```json
{
  "order_id": "{uuid}",
  "order_number": "JB-20260402-ABC123",
  "customer_name": "...",
  "total": 1500.00,
  "message": "New order received: #...",
  "type": "new_order|status_changed",
  "url": "https://store.../admin/orders/{uuid}"
}
```

---

## 9. Guest Order Tracking (Public)

**Controller**: `OrderTrackingController@track`
**Route**: `POST /track-order`
- Lookup by `order_number` (human-readable code, not UUID)
- No authentication required
- Does NOT expose `id` or `uuid`
