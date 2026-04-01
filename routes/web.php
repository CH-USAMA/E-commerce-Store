<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/sitemap.xml', function () {
    return response()->view('frontend.sitemap')->header('Content-Type', 'text/xml');
});

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Http\Controllers\HomeController::class, 'about'])->name('about');
Route::get('/services', [\App\Http\Controllers\HomeController::class, 'services'])->name('services');
Route::get('/products', [\App\Http\Controllers\HomeController::class, 'products'])->name('products');
Route::get('/product/{slug}', [\App\Http\Controllers\HomeController::class, 'productDetail'])->name('product.detail');
Route::get('/blog', [\App\Http\Controllers\HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [\App\Http\Controllers\HomeController::class, 'blogDetail'])->name('blog.detail');
Route::get('/gallery', [\App\Http\Controllers\HomeController::class, 'gallery'])->name('gallery');
Route::get('/video-gallery', [\App\Http\Controllers\HomeController::class, 'videoGallery'])->name('video.gallery');
Route::get('/testimonials', [\App\Http\Controllers\HomeController::class, 'testimonials'])->name('testimonials');
Route::get('/team', [\App\Http\Controllers\HomeController::class, 'team'])->name('team');
Route::get('/stores', [\App\Http\Controllers\HomeController::class, 'stores'])->name('stores');
Route::get('/store/{store}', [\App\Http\Controllers\HomeController::class, 'storeDetail'])->name('store.detail');
Route::get('/specials', [\App\Http\Controllers\HomeController::class, 'specials'])->name('specials');
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\HomeController::class, 'submitContact'])->name('contact.submit')->middleware('throttle:6,1');
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('search');
Route::view('/privacy-policy', 'frontend.privacy')->name('privacy');
Route::view('/terms-of-service', 'frontend.terms')->name('terms');

// Authentication Routes
Route::middleware(['throttle:6,1'])->group(function () {
    Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
});
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Profile Completion & Address Management
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/complete', [\App\Http\Controllers\ProfileController::class, 'showCompleteProfile'])->name('profile.complete');
    Route::post('/profile/complete', [\App\Http\Controllers\ProfileController::class, 'storeCompleteProfile'])->name('profile.complete.store');

    Route::middleware(['profile.complete'])->group(function () {
        Route::get('/profile/addresses', [\App\Http\Controllers\ProfileController::class, 'manageAddresses'])->name('profile.addresses');
        Route::post('/profile/addresses', [\App\Http\Controllers\ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
        Route::delete('/profile/addresses/{address}', [\App\Http\Controllers\ProfileController::class, 'deleteAddress'])->name('profile.addresses.delete');
        Route::post('/profile/addresses/{address}/default', [\App\Http\Controllers\ProfileController::class, 'setDefaultAddress'])->name('profile.addresses.default');
    });
});

// Email Verification Routes
Route::middleware(['auth'])->prefix('email')->name('verification.')->group(function () {
    Route::get('/verify', [\App\Http\Controllers\VerificationController::class, 'show'])->name('notice');
    Route::get('/verify/{id}/{hash}', [\App\Http\Controllers\VerificationController::class, 'verify'])->name('verify')->middleware(['signed', 'throttle:6,1']);
    Route::post('/verification-notification', [\App\Http\Controllers\VerificationController::class, 'resend'])->name('send')->middleware(['throttle:6,1']);
});

// Social Login Routes
Route::get('auth/google', [\App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\SocialAuthController::class, 'handleGoogleCallback']);

// Cart Routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [\App\Http\Controllers\CartController::class, 'count'])->name('cart.count');
Route::post('/cart/nearest-store', [\App\Http\Controllers\CartController::class, 'nearestStore'])->name('cart.nearest-store');
Route::get('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
// Checkout Routes
Route::get('/checkout/auth', [\App\Http\Controllers\CartController::class, 'checkoutAuth'])->name('checkout.auth');
Route::post('/checkout/guest', [\App\Http\Controllers\CartController::class, 'guestCheckout'])->name('checkout.guest');

Route::middleware(['profile.complete'])->group(function () {
    Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [\App\Http\Controllers\CartController::class, 'processCheckout'])->name('checkout.process');
});

Route::get('/order-success', [\App\Http\Controllers\CartController::class, 'orderSuccess'])->name('order.success');
Route::get('/track-order', [\App\Http\Controllers\OrderTrackingController::class, 'index'])->name('order.track');
Route::post('/track-order', [\App\Http\Controllers\OrderTrackingController::class, 'track'])->name('order.track.submit');

// User Portal Routes (Protected by verified and profile.complete middleware)
Route::middleware(['auth', 'verified', 'role:user', 'profile.complete'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [\App\Http\Controllers\User\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\User\OrderController::class, 'show'])->name('orders.show');
    Route::get('/notifications', [\App\Http\Controllers\User\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/mark-read', [\App\Http\Controllers\User\NotificationController::class, 'markAllRead'])->name('notifications.mark-read');
    Route::get('/orders-export', [\App\Http\Controllers\User\OrderController::class, 'export'])->name('orders.export');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('stores', \App\Http\Controllers\Admin\StoreController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::get('products/export', [\App\Http\Controllers\Admin\ProductController::class, 'export'])->name('products.export');
    Route::post('products/import', [\App\Http\Controllers\Admin\ProductController::class, 'import'])->name('products.import');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('team', \App\Http\Controllers\Admin\TeamMemberController::class);
    Route::resource('blog', \App\Http\Controllers\Admin\BlogPostController::class);
    Route::resource('blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class);
    Route::resource('gallery', \App\Http\Controllers\Admin\GalleryItemController::class);
    Route::get('orders/export', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    Route::get('orders/fake', [\App\Http\Controllers\Admin\OrderController::class, 'createFakeOrder'])->name('orders.fake');
    Route::get('orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::post('orders/{order}/confirm-payment', [\App\Http\Controllers\Admin\OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('settings/payments', [\App\Http\Controllers\Admin\SystemController::class, 'payments'])->name('settings.payments');
    Route::post('settings/payments', [\App\Http\Controllers\Admin\SystemController::class, 'updatePayments'])->name('settings.payments.update');
    Route::get('settings/invoice', [\App\Http\Controllers\Admin\SystemController::class, 'invoiceSettings'])->name('settings.invoice');
    Route::post('settings/invoice', [\App\Http\Controllers\Admin\SystemController::class, 'updateInvoiceSettings'])->name('settings.invoice.update');
    
    // Marketing & Notifications
    Route::get('notifications/mark-all-read', [\App\Http\Controllers\Admin\MarketingController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('marketing', [\App\Http\Controllers\Admin\MarketingController::class, 'index'])->name('marketing.index');
    Route::get('marketing/create', [\App\Http\Controllers\Admin\MarketingController::class, 'create'])->name('marketing.create');
    Route::post('marketing', [\App\Http\Controllers\Admin\MarketingController::class, 'push'])->name('marketing.push');
    Route::post('marketing/{campaign}/resend', [\App\Http\Controllers\Admin\MarketingController::class, 'resend'])->name('marketing.resend');
    Route::delete('marketing/{campaign}', [\App\Http\Controllers\Admin\MarketingController::class, 'destroy'])->name('marketing.destroy');

    // Guest Management (GDPR/POPIA)
    Route::get('guests', '\App\Http\Controllers\Admin\GuestController@index')->name('guests.index');
    Route::post('guests/purge', '\App\Http\Controllers\Admin\GuestController@purge')->name('guests.purge');
    Route::post('guests/purge-old', '\App\Http\Controllers\Admin\GuestController@purgeOld')->name('guests.purge-old');
});

// Store Manager Routes
Route::middleware(['auth', 'role:manager'])->prefix('branch')->name('branch.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Branch\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [\App\Http\Controllers\Branch\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Branch\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [\App\Http\Controllers\Branch\OrderController::class, 'updateStatus'])->name('orders.status');

    Route::get('/stocks', [\App\Http\Controllers\Branch\StockController::class, 'index'])->name('stocks.index');
    Route::post('/stocks/update', [\App\Http\Controllers\Branch\StockController::class, 'update'])->name('stocks.update');
});
