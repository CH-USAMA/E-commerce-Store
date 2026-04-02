# Feature Map — Jabulani Store

> Quick lookup: Feature → Route → Controller → Models → Middleware
> Use this to find the right files when adding or debugging any feature.

---

## Public Frontend

| Feature | Route | Controller@Method | Models Touched | Middleware |
|:---|:---|:---|:---|:---|
| Home page | `GET /` | `HomeController@index` | Category, Product, Banner, Store, Brand | web |
| Product listing | `GET /products` | `HomeController@products` | Product, Category, Brand | web |
| Product detail | `GET /product/{slug}` | `HomeController@productDetail` | Product | web |
| Blog listing | `GET /blog` | `HomeController@blog` | BlogPost, BlogCategory | web |
| Blog detail | `GET /blog/{slug}` | `HomeController@blogDetail` | BlogPost | web |
| Store listing | `GET /stores` | `HomeController@stores` | Store | web |
| Store detail | `GET /store/{store}` | `HomeController@storeDetail` | Store | web |
| Search | `GET /search?q=` | `HomeController@search` | Product | web |
| Contact page | `GET /contact` | `HomeController@contact` | Setting | web |
| Contact submit | `POST /contact` | `HomeController@submitContact` | — | throttle:6,1 |
| Track order | `GET/POST /track-order` | `OrderTrackingController@index/@track` | Order | web |
| Sitemap | `GET /sitemap.xml` | closure | — | web |
| Privacy Policy | `GET /privacy-policy` | view | — | web |
| Terms | `GET /terms-of-service` | view | — | web |

---

## Cart

| Feature | Route | Controller@Method | Models Touched | Middleware |
|:---|:---|:---|:---|:---|
| View cart | `GET /cart` | `CartController@index` | Product, User | web |
| Add to cart | `POST /cart/add` | `CartController@add` | Product, User | web |
| Update quantity | `POST /cart/update` | `CartController@update` | User | web |
| Remove item | `POST /cart/remove` | `CartController@remove` | User | web |
| Cart count (AJAX) | `GET /cart/count` | `CartController@count` | — | web |
| Clear cart | `GET /cart/clear` | `CartController@clear` | User | web |
| Find nearest store | `POST /cart/nearest-store` | `CartController@nearestStore` | Store | web |
| Checkout auth gate | `GET /checkout/auth` | `CartController@checkoutAuth` | — | web |
| Guest checkout | `POST /checkout/guest` | `CartController@guestCheckout` | — | web |
| Checkout page | `GET /checkout` | `CartController@checkout` | Product, Store, User, Address, Setting | profile.complete |
| Process checkout | `POST /checkout` | `CartController@processCheckout` | Order, OrderItem, Product, User, Store, Setting, Address | profile.complete |
| Order success | `GET /order-success` | `CartController@orderSuccess` | Order | web |

---

## Authentication

| Feature | Route | Controller@Method | Models Touched | Middleware |
|:---|:---|:---|:---|:---|
| Show register | `GET /register` | `AuthController@showRegister` | — | throttle:6,1 |
| Register | `POST /register` | `AuthController@register` | User | throttle:6,1 |
| Show login | `GET /login` | `AuthController@showLogin` | — | throttle:6,1 |
| Login | `POST /login` | `AuthController@login` | User | throttle:6,1 |
| Logout | `POST /logout` | `AuthController@logout` | — | web |
| Google redirect | `GET /auth/google` | `SocialAuthController@redirectToGoogle` | — | web |
| Google callback | `GET /auth/google/callback` | `SocialAuthController@handleGoogleCallback` | User | web |
| Email verify notice | `GET /email/verify` | `VerificationController@show` | — | auth |
| Verify email | `GET /email/verify/{id}/{hash}` | `VerificationController@verify` | User | auth, signed, throttle:6,1 |
| Resend verification | `POST /verification-notification` | `VerificationController@resend` | — | auth, throttle:6,1 |

---

## User Portal (`/user/*`)

| Feature | Route | Controller@Method | Models Touched | Middleware |
|:---|:---|:---|:---|:---|
| User dashboard | `GET /user/dashboard` | `User\DashboardController@index` | Order, User | auth,verified,role:user,profile.complete |
| Order history | `GET /user/orders` | `User\OrderController@index` | Order | auth,verified,role:user,profile.complete |
| Order detail | `GET /user/orders/{order}` | `User\OrderController@show` | Order, OrderItem, Product | auth,verified,role:user,profile.complete |
| Notifications | `GET /user/notifications` | `User\NotificationController@index` | Notification | auth,verified,role:user,profile.complete |
| Mark all read | `GET /user/notifications/mark-read` | `User\NotificationController@markAllRead` | Notification | auth,verified,role:user,profile.complete |
| Export orders | `GET /user/orders-export` | `User\OrderController@export` | Order | auth,verified,role:user,profile.complete |

---

## Profile

| Feature | Route | Controller@Method | Models Touched | Middleware |
|:---|:---|:---|:---|:---|
| Complete profile | `GET/POST /profile/complete` | `ProfileController@showCompleteProfile/@storeCompleteProfile` | User | auth |
| Manage addresses | `GET /profile/addresses` | `ProfileController@manageAddresses` | Address | auth,profile.complete |
| Add address | `POST /profile/addresses` | `ProfileController@storeAddress` | Address | auth,profile.complete |
| Delete address | `DELETE /profile/addresses/{address}` | `ProfileController@deleteAddress` | Address | auth,profile.complete |
| Set default address | `POST /profile/addresses/{address}/default` | `ProfileController@setDefaultAddress` | Address | auth,profile.complete |

---

## Admin Portal (`/admin/*`)

| Feature | Route | Controller@Method | Models Touched | Middleware |
|:---|:---|:---|:---|:---|
| Dashboard | `GET /admin/dashboard` | `Admin\DashboardController@index` | Order, User, Product, Store | auth,role:admin |
| Orders (CRUD) | `/admin/orders/**` | `Admin\OrderController` | Order, OrderItem, ActivityLog | auth,role:admin |
| Confirm EFT payment | `POST /admin/orders/{order}/confirm-payment` | `Admin\OrderController@confirmPayment` | Order, ActivityLog | auth,role:admin |
| Invoice PDF | `GET /admin/orders/{order}/invoice` | `Admin\OrderController@invoice` | Order, Setting | auth,role:admin |
| Products (CRUD+Import/Export) | `/admin/products/**` | `Admin\ProductController` | Product, Category, Brand, ProductStoreStock | auth,role:admin |
| Categories (CRUD) | `/admin/categories/**` | `Admin\CategoryController` | Category | auth,role:admin |
| Brands (CRUD) | `/admin/brands/**` | `Admin\BrandController` | Brand | auth,role:admin |
| Stores (CRUD) | `/admin/stores/**` | `Admin\StoreController` | Store, User | auth,role:admin |
| Users (CRUD) | `/admin/users/**` | `Admin\UserController` | User | auth,role:admin |
| Banners (CRUD) | `/admin/banners/**` | `Admin\BannerController` | Banner | auth,role:admin |
| Services (CRUD) | `/admin/services/**` | `Admin\ServiceController` | Service | auth,role:admin |
| Blog (CRUD) | `/admin/blog/**` | `Admin\BlogPostController` | BlogPost, BlogCategory | auth,role:admin |
| Gallery (CRUD) | `/admin/gallery/**` | `Admin\GalleryItemController` | GalleryItem | auth,role:admin |
| Team (CRUD) | `/admin/team/**` | `Admin\TeamMemberController` | TeamMember | auth,role:admin |
| Payment Settings | `GET/POST /admin/settings/payments` | `Admin\SystemController@payments/@updatePayments` | Setting | auth,role:admin |
| Invoice Settings | `GET/POST /admin/settings/invoice` | `Admin\SystemController@invoiceSettings/@updateInvoiceSettings` | Setting | auth,role:admin |
| Marketing push | `/admin/marketing/**` | `Admin\MarketingController` | Campaign, User | auth,role:admin |
| Notifications mark read | `GET /admin/notifications/mark-all-read` | `Admin\MarketingController@markAllRead` | Notification | auth,role:admin |
| Guest management | `/admin/guests/**` | `Admin\GuestController` | Order, User | auth,role:admin |

---

## Branch Manager Portal (`/branch/*`)

| Feature | Route | Controller@Method | Models Touched | Middleware |
|:---|:---|:---|:---|:---|
| Branch dashboard | `GET /branch/dashboard` | `Branch\DashboardController@index` | Order, Store | auth,role:manager |
| Branch orders | `GET /branch/orders` | `Branch\OrderController@index` | Order, Store | auth,role:manager |
| Branch order detail | `GET /branch/orders/{order}` | `Branch\OrderController@show` | Order, OrderItem, Product | auth,role:manager |
| Update order status | `POST /branch/orders/{order}/status` | `Branch\OrderController@updateStatus` | Order | auth,role:manager |
| Branch stock | `GET /branch/stocks` | `Branch\StockController@index` | ProductStoreStock, Store, Product | auth,role:manager |
| Update stock | `POST /branch/stocks/update` | `Branch\StockController@update` | ProductStoreStock | auth,role:manager |
