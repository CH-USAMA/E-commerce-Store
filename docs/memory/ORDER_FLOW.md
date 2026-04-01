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

## 3. Order Creation & Payment
- **Logic**: 
    - Order is created in `orders` with a unique `JB-YYYYMMDD-XXXXXX` number.
    - **EFT**: Order status set to `awaiting_payment`. Customer uploads Proof of Payment (`payment_screenshot`).
    - **Stripe**: Redirected to Stripe Checkout. Upon success, order status is updated to `processing`.
    - **VAT**: Total VAT (15%) is calculated and stored in the order record.

## 4. Administrative Verification
- **Logic**: 
    - Admins receive a database notification for every new order.
    - **EFT Verification**: Admins manually audit EFT screenshots and click **Confirm Payment**.
    - **Status Updates**: Successive status changes: `Pending` → `Processing` → `Shipped/Ready` → `Delivered/Completed`.
