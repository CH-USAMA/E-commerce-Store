# User Portal — Authenticated Customer Journey

---

## 1. Access Requirements

To access `/user/*`, a user must pass ALL of:
1. `auth` — must be logged in
2. `verified` — `email_verified_at` must not be null
3. `role:user` — `users.role === 'user'`
4. `profile.complete` — `users.phone` must not be null

If any fails:
- Not logged in → `/login`
- Not verified → `/email/verify` (notice page)
- Wrong role → 403 Unauthorized
- Profile incomplete → `/profile/complete`

---

## 2. Profile Completion Gate

**Controller**: `App\Http\Controllers\ProfileController`
**Route**: `GET/POST /profile/complete` → `profile.complete` / `profile.complete.store`

Required fields: `phone` (via `profile.complete` middleware check)
Once completed, user is redirected to their intended destination (stored in `session('url.intended')`).

---

## 3. Email Verification Flow

**Local**: `MAIL_MAILER=log` → email link appears in `storage/logs/laravel-YYYY-MM-DD.log`
**Production**: `MAIL_MAILER=smtp` → sent from `info@jabulanigroupofcompanies.co.za`

Flow:
```
Register / Google OAuth → user created (email_verified_at = null, role = 'user')
→ Verification email dispatched
→ User clicks link → GET /email/verify/{id}/{hash}?expires=...&signature=...
→ VerificationController@verify validates signed URL
→ email_verified_at = now()
→ Redirect to user dashboard
```

Google OAuth: Email is auto-verified on first login (`email_verified_at = now()` set in `SocialAuthController`).

---

## 4. User Portal Routes

| Method | URI | Controller@Method | Route Name |
|:---|:---|:---|:---|
| GET | `/user/dashboard` | `User\DashboardController@index` | `user.dashboard` |
| GET | `/user/orders` | `User\OrderController@index` | `user.orders.index` |
| GET | `/user/orders/{order}` | `User\OrderController@show` | `user.orders.show` |
| GET | `/user/notifications` | `User\NotificationController@index` | `user.notifications.index` |
| GET | `/user/notifications/mark-read` | `User\NotificationController@markAllRead` | `user.notifications.mark-read` |
| GET | `/user/orders-export` | `User\OrderController@export` | `user.orders.export` |
| GET | `/profile/addresses` | `ProfileController@manageAddresses` | `profile.addresses` |
| POST | `/profile/addresses` | `ProfileController@storeAddress` | `profile.addresses.store` |
| DELETE | `/profile/addresses/{address}` | `ProfileController@deleteAddress` | `profile.addresses.delete` |
| POST | `/profile/addresses/{address}/default` | `ProfileController@setDefaultAddress` | `profile.addresses.default` |

**Order routes** (`/user/orders/{order}`) use Route Model Binding on `Order::uuid` — always UUID.

---

## 5. Dashboard Display

**Controller**: `User\DashboardController@index`

Passes to view:
- `$user` — authenticated user object
- Recent orders with status
- `total_spent` — sum of all order totals
- `active_orders` — count of non-delivered/non-cancelled orders
- Unread notification count

---

## 6. Notification Centre

- Source: `$user->unreadNotifications` (Eloquent on `notifications` DB table)
- Display: Bell icon in layout header shows badge count
- Full page: `/user/notifications`
- Mark read: `/user/notifications/mark-read` (marks all, redirects back)
- Data structure: JSON payload with `message`, `url` (UUID-based), `type`, `order_number`

---

## 7. Address Book

- Users can save multiple addresses
- One can be marked `is_default = true`
- At checkout, default address auto-fills the form
- Addresses stored in `addresses` table linked by `user_id`

---

## 8. Google OAuth (Social Login)

**Controller**: `App\Http\Controllers\Auth\SocialAuthController`
**Routes**:
- `GET /auth/google` → redirectToGoogle()
- `GET /auth/google/callback` → handleGoogleCallback()

Flow:
```
Redirect to Google → callback with Socialite user data
→ Check if email exists → update google tokens
→ OR create new user:
    name: from Google
    email: from Google
    google_id, google_token, google_refresh_token: from Socialite
    email_verified_at: now()   ← auto-verified
    role: 'user'               ← always assigned (NOT via fillable)
→ Login → redirect to session('url.intended') or user.dashboard
```

**Note**: `token` and `refreshToken` properties exist on Socialite user object, not on the `User` model.
PHPDoc annotations on `User` model declare `$google_token` and `$google_refresh_token` for IDE hints.
