# Order Flow & Checkout Life Cycle

The checkout process handles registration, fulfillment, and payment verification.

## 1. Cart Management
- **Controller**: `App\Http\Controllers\CartController`
- **Logic**: 
    - Logged-in users' carts are synchronized to `users.cart_data`.
    - Guest carts are stored in the PHP session.
    - **Sync**: Carts are merged or restored upon login.

## 2. Checkout Flow
- **Controller**: `App\Http\Controllers\CartController@checkout`
- **Logic**: 
    - **Authentication**: Users must be either logged-in (and verified) or in **Guest Mode**.
    - **Store Selection**: Manual select or auto-nearest branch.
    - **Fulfillment**: **Pickup** or **Delivery** (delivery radius is restricted by `max_delivery_km` in global settings).
    - **Distance Calculation**: Using the Haversine formula to verify delivery feasibility.

## 3. Order Creation & Routing
- **Routing**: Orders are strictly routed via **UUID** (route key binding) to prevent incremental ID exposure.
- **Order Number**: A human-friendly `JB-YYYYMMDD-XXXXXX` is generated for invoices and internal lookup.
- **Payment States**: 
    - **EFT**: Status set to `awaiting_payment`. Administrative verification of `payment_screenshot` is required.
    - **Confirmed**: Setting status to `processing` via **Confirm Payment** (Admin) triggers final documentation dispatch.
    - **Stripe**: Auto-updates to `processing` upon successful webhook callback.
    - **VAT**: 15% South African VAT is calculated and stored per line-item and total.

## 4. Administrative Verification
- **Logic**: 
    - Admins receive a database notification for every new order.
    - **EFT Verification**: Admins manually audit EFT screenshots and click **Confirm Payment**.
    - **Status Updates**: Successive status changes: `Pending` → `Processing` → `Shipped/Ready` → `Delivered/Completed`.
