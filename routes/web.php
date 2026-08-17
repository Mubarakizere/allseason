<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckRoleAdmin;
use App\Http\Middleware\CheckRoleCustomer;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\RedirectIfNotAdmin;
use App\Http\Controllers\MainSiteController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\TableBookingController;
use App\Http\Controllers\MainSite\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TestimonyController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\GeneralSettingsController;
use App\Http\Controllers\Admin\TermsAndConditionController;
use App\Http\Controllers\Admin\VenueController as AdminVenueController;
use App\Http\Controllers\Admin\VenuePackageController;
use App\Http\Controllers\Admin\VenueBookingController;
use App\Http\Controllers\Admin\TableBookingController as AdminTableBookingController;
use App\Http\Controllers\FrontRoomController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\RoomBookingController as AdminRoomBookingController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\StockCategoryController;
use App\Http\Controllers\Admin\StockItemController;
use App\Http\Controllers\Admin\StockPurchaseController;
use App\Http\Controllers\Admin\StockIssueController;
use App\Http\Controllers\Admin\StockHistoryController;
use App\Http\Controllers\Admin\GlobalReportController;


Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', [MainSiteController::class, 'home'])->name('home');

Route::post('table-booking/', [TableBookingController::class, 'bookTable'])->name('table.booking');

Route::get('menu/', [MainSiteController::class, 'menu'])->name('menu');
Route::get('menu-item/{id}', [MainSiteController::class, 'menuItem'])->name('menu.item');

// Venues & Bookings
Route::get('venues', [VenueController::class, 'index'])->name('venues.index');
Route::get('venues/{id}', [VenueController::class, 'show'])->name('venues.show');
Route::get('venues-check-availability', [VenueController::class, 'checkAvailability'])->name('venues.checkAvailability');

Route::middleware(['auth'])->group(function () {
    Route::post('venues/checkout', [VenueController::class, 'checkout'])->name('venues.checkout');
    Route::get('venues-success', [VenueController::class, 'success'])->name('venues.success');
    Route::get('venues-cancel', [VenueController::class, 'cancel'])->name('venues.cancel');
});

// Rooms & Bookings
Route::get('rooms', [FrontRoomController::class, 'index'])->name('rooms.index');
Route::get('rooms/{id}', [FrontRoomController::class, 'show'])->name('rooms.show');
Route::get('rooms-check-availability', [FrontRoomController::class, 'checkAvailability'])->name('rooms.checkAvailability');
Route::post('rooms/checkout', [FrontRoomController::class, 'checkout'])->name('rooms.checkout')->middleware('auth');
Route::get('rooms-success', [FrontRoomController::class, 'success'])->name('rooms.success')->middleware('auth');
Route::get('rooms-cancel', [FrontRoomController::class, 'cancel'])->name('rooms.cancel')->middleware('auth');

// Customer Cart 
Route::get('cart/', [MainSiteController::class, 'cart'])->name('customer.cart');
Route::post('cart/add', [MainSiteController::class, 'addToCart'])->name('customer.cart.add');
Route::post('cart/remove', [MainSiteController::class, 'removeFromCart'])->name('customer.cart.remove');
Route::get('cart/view', [MainSiteController::class, 'getCart'])->name('customer.cart.view');
Route::post('cart/clear', [MainSiteController::class, 'clearCart'])->name('customer.cart.clear');
Route::post('cart/update', [MainSiteController::class, 'updateCartQuantity'])->name('customer.cart.update');

Route::get('getcart-totalitems/', [MainSiteController::class, 'getTotalItems'])->name('customer.getcart.totalitems');

 // Payment routes
Route::get('payment/', [PaymentController::class, 'payment'])->name('payment');

Route::get('payment-success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('payment-cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
Route::post('weflexfy/webhook', [PaymentController::class, 'handleWeFlexfyWebhook'])->name('weflexfy.webhook');

  

Route::get('about/', [MainSiteController::class, 'about'])->name('about');
Route::get('contact/', [MainSiteController::class, 'contact'])->name('contact');

Route::get('blogs/', [MainSiteController::class, 'blogs'])->name('blogs');
Route::get('blog/view/{id}', [MainSiteController::class, 'blogView'])->name('blog.view');

Route::get('privacy-policy/', [MainSiteController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('terms-conditions/', [MainSiteController::class, 'termsConditions'])->name('terms.conditions');


//Resetting Password
Route::middleware(['guest'])->group(function () {

    // Customer account creation routes
    Route::get('customer/create-account', [CustomerController::class, 'create'])->name('customer.account.create');
    Route::post('customer/store-account', [CustomerController::class, 'store'])->name('customer.account.store');

    // login routes
    Route::get('auth/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('auth/process-login/', [AuthController::class, 'login'])->name('auth.login.process');


    // activate route
    Route::get('auth/activate-link-request', [AuthController::class, 'requestActivationLink'])->name('auth.activate.link.request');
    Route::get('auth/activate-account/{token}', [AuthController::class, 'activateAccount'])->name('auth.activate.account');
    Route::post('auth/process-activate-account/', [AuthController::class, 'processApdatePassword'])->name('auth.process.activate.account');

    //password reset routes
    Route::get('auth/password/request', [AuthController::class, 'showLinkRequestForm'])->name('auth.password.request');
    Route::post('auth/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('auth.password.email');
    Route::get('auth/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('auth/password/reset', [AuthController::class, 'resetPassword'])->name('auth.password.update');
});

//Logout route
Route::middleware(['auth'])->group(function () {
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});


// Customer Dashboard routes
Route::prefix('customer')->middleware(CheckRoleCustomer::class)->group(function () {
    
    Route::get('/', [CustomerController::class, 'account'])->name('customer.account');
    Route::get('/orders/{filter?}', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/order-details/{id}', [CustomerController::class, 'orderDetails'])->name('customer.order.details');

    // Profile edit and update
    Route::get('/edit-profile', [CustomerController::class, 'editAccount'])->name('customer.edit.profile');
    Route::put('/update-profile', [CustomerController::class, 'updateAccount'])->name('customer.update.profile');
 
    // Change password
    Route::get('/change-password', [CustomerController::class, 'showChangePasswordForm'])->name('customer.change.password');
    Route::post('/change-password', [CustomerController::class, 'changePassword'])->name('customer.change.password.post');

    Route::post('proccess-checkout/', [CheckoutController::class, 'proccessCheckout'])->name('customer.proccess.checkout');



    // Step 1: Customer details (review)
    Route::get('/checkout/details', [CheckoutController::class, 'details'])->name('customer.checkout.details');
    Route::post('/checkout/details', [CheckoutController::class, 'detailsPost'])->name('customer.checkout.details.post');

    // Step 2: Fulfilment (pickup or delivery)
    Route::get('/checkout/fulfilment', [CheckoutController::class, 'fulfilment'])->name('customer.checkout.fulfilment');
    Route::post('/checkout/fulfilment', [CheckoutController::class, 'fulfilmentPost'])->name('customer.checkout.fulfilment.post');


    Route::delete('/address/{id}', [AddressController::class, 'destroy'])->name('customer.address.destroy');

    // Step 3a: Pickup location
    Route::get('/checkout/pickup', [CheckoutController::class, 'pickup'])->name('customer.checkout.pickup');
    Route::post('/checkout/pickup', [CheckoutController::class, 'pickupPost'])->name('customer.checkout.pickup.post');

    // Step 3b: Delivery address
    Route::get('/checkout/delivery', [CheckoutController::class, 'delivery'])->name('customer.checkout.delivery');
    Route::post('/checkout/delivery', [CheckoutController::class, 'deliveryPost'])->name('customer.checkout.delivery.post');

    // step 4: order review
    Route::get('/checkout/review', [CheckoutController::class, 'review'])->name('customer.checkout.review');

    // Step 4: Payment & review
    Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('customer.checkout.payment');
    Route::post('/checkout/payment', [CheckoutController::class, 'paymentPost'])->name('customer.checkout.payment.post');

    // Step 5: Confirmation
    Route::get('/checkout/complete/{order}', [CheckoutController::class, 'complete'])->name('customer.checkout.complete');



});


//Admin Dashboard routes
Route::prefix('admin')->middleware(RedirectIfNotAdmin::class)->group(function () {
     Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');


    Route::get('profile', [AdminController::class, 'viewMyProfile'])->name('admin.view.myprofile');
    Route::get('profile/edit', [AdminController::class, 'editMyProfile'])->name('admin.myprofile.edit');
    Route::put('profile/update', [AdminController::class, 'updateMyProfile'])->name('admin.myprofile.update');


    //change password
    Route::get('change-password', [AdminController::class, 'showChangePasswordForm'])->name('change.password.form');
    Route::post('change-password', [AdminController::class, 'changePassword'])->name('change-password.update');

    //Admin Blog routes
    Route::get('blog', [BlogController::class, 'index'])->name('admin.blog.index');
    Route::get('blog/create', [BlogController::class, 'create'])->name('admin.blog.create');
    Route::post('blog', [BlogController::class, 'store'])->name('admin.blog.store');
    Route::get('blog/{id}/edit', [BlogController::class, 'edit'])->name('admin.blog.edit');
    Route::put('blog/{id}', [BlogController::class, 'update'])->name('admin.blog.update');
    Route::delete('blog/{id}', [BlogController::class, 'destroy'])->name('admin.blog.destroy');
     


    // Admin Cart / POS routes
    Route::get('pos/', [CartController::class, 'index'])->name('admin.pos.index');
    Route::post('cart/add', [CartController::class, 'addToCart'])->name('admin.cart.add');
    Route::post('cart/remove', [CartController::class, 'removeFromCart'])->name('admin.cart.remove');
    Route::get('cart/view', [CartController::class, 'getCart'])->name('admin.cart.view');
    Route::post('cart/clear', [CartController::class, 'clearCart'])->name('admin.cart.clear');
    Route::post('cart/update', [CartController::class, 'updateCartQuantity'])->name('admin.cart.update');
    Route::post('cart/update-note', [CartController::class, 'updateCartItemNote'])->name('admin.cart.update-note');

 
    //Admin Order routes
    Route::get('orders/unprinted', [OrderController::class, 'unprinted'])->name('admin.orders.unprinted');
    Route::get('orders/table/{id}', [OrderController::class, 'getOpenOrder'])->name('admin.orders.table');
    Route::get('orders/{id}/receipt', [OrderController::class, 'receipt'])->name('admin.orders.receipt');
    Route::post('orders/{id}/mark-printed', [OrderController::class, 'markPrinted'])->name('admin.orders.mark-printed');
    Route::get('orders/{filter?}', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('order/{id}', [OrderController::class, 'show'])->name('admin.order.show');
    Route::post('order/create', [OrderController::class, 'createOrder'])->name('admin.order.store');
    Route::post('orders/update/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('orders/destroy/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy')->middleware(CheckRoleAdmin::class);
    

    //Admin Manage Booking
    Route::get('table-bookings', [AdminTableBookingController::class, 'index'])->name('admin.table-bookings');
    Route::post('table-bookings/store', [AdminTableBookingController::class, 'store'])->name('admin.table-bookings.store');
    Route::put('table-bookings/{id}', [AdminTableBookingController::class, 'update'])->name('admin.table-bookings.update');
    Route::delete('table-bookings/{id}', [AdminTableBookingController::class, 'destroy'])->name('admin.table-bookings.destroy');
   

    // Routes with CheckRoleAdmin is Global Admin middleware
    Route::middleware(CheckRoleAdmin::class)->group(function () {

        // Global Reports
        Route::get('reports/global', [GlobalReportController::class, 'index'])->name('admin.reports.global');

        // Admin Settings Category
        Route::get('category', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('category/store', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::match(['POST', 'PUT'], 'category/update/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::match(['POST', 'DELETE'], 'category/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // Admin Waiters
        Route::get('waiters', [\App\Http\Controllers\Admin\WaiterController::class, 'index'])->name('admin.waiters.index');
        Route::post('waiters/store', [\App\Http\Controllers\Admin\WaiterController::class, 'store'])->name('admin.waiters.store');
        Route::match(['POST', 'PUT'], 'waiters/update/{id}', [\App\Http\Controllers\Admin\WaiterController::class, 'update'])->name('admin.waiters.update');
        Route::match(['POST', 'DELETE'], 'waiters/delete/{id}', [\App\Http\Controllers\Admin\WaiterController::class, 'destroy'])->name('admin.waiters.destroy');

        // Admin Tables
        Route::get('restaurant-tables', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'index'])->name('admin.restaurant-tables.index');
        Route::post('restaurant-tables/store', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'store'])->name('admin.restaurant-tables.store');
        Route::match(['POST', 'PUT'], 'restaurant-tables/update/{id}', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'update'])->name('admin.restaurant-tables.update');
        Route::match(['POST', 'DELETE'], 'restaurant-tables/delete/{id}', [\App\Http\Controllers\Admin\RestaurantTableController::class, 'destroy'])->name('admin.restaurant-tables.destroy');

        //Admin Settings Menu
        Route::get('menu', [MenuController::class, 'index'])->name('admin.menus.index');
        Route::post('menu', [MenuController::class, 'store'])->name('admin.menus.store');
        Route::patch('menu/{id}', [MenuController::class, 'update'])->name('admin.menus.update');
        Route::delete('menu/{id}', [MenuController::class, 'destroy'])->name('admin.menus.destroy');
        
        // Admin Venues
        Route::get('venues', [AdminVenueController::class, 'index'])->name('admin.venues.index');
        Route::post('/venues', [AdminVenueController::class, 'store'])->name('admin.venues.store');
        Route::put('/venues/{id}', [AdminVenueController::class, 'update'])->name('admin.venues.update');
        Route::delete('/venues/{id}', [AdminVenueController::class, 'destroy'])->name('admin.venues.destroy');
        Route::delete('/venues/image/{id}', [AdminVenueController::class, 'deleteImage'])->name('admin.venues.delete-image');

        Route::get('/venue-packages', [VenuePackageController::class, 'index'])->name('admin.venue-packages.index');
        Route::post('/venue-packages', [VenuePackageController::class, 'store'])->name('admin.venue-packages.store');
        Route::put('/venue-packages/{id}', [VenuePackageController::class, 'update'])->name('admin.venue-packages.update');
        Route::delete('/venue-packages/{id}', [VenuePackageController::class, 'destroy'])->name('admin.venue-packages.destroy');
        Route::delete('/venue-packages/image/{id}', [VenuePackageController::class, 'deleteImage'])->name('admin.venue-packages.delete-image');

        // Admin Venue Bookings
        Route::get('venue-bookings', [VenueBookingController::class, 'index'])->name('admin.venue-bookings.index');
        Route::put('venue-bookings/{id}', [VenueBookingController::class, 'update'])->name('admin.venue-bookings.update');
        Route::delete('venue-bookings/{id}', [VenueBookingController::class, 'destroy'])->name('admin.venue-bookings.destroy');
    
        // Admin Rooms
        Route::get('rooms', [AdminRoomController::class, 'index'])->name('admin.rooms.index');
        Route::post('/rooms', [AdminRoomController::class, 'store'])->name('admin.rooms.store');
        Route::put('/rooms/{id}', [AdminRoomController::class, 'update'])->name('admin.rooms.update');
        Route::delete('/rooms/{id}', [AdminRoomController::class, 'destroy'])->name('admin.rooms.destroy');
        Route::delete('/rooms/image/{id}', [AdminRoomController::class, 'deleteImage'])->name('admin.rooms.delete-image');

        // Admin Room Bookings
        Route::get('room-bookings', [AdminRoomBookingController::class, 'index'])->name('admin.room-bookings.index');
        Route::put('room-bookings/{id}', [AdminRoomBookingController::class, 'update'])->name('admin.room-bookings.update');
        Route::delete('room-bookings/{id}', [AdminRoomBookingController::class, 'destroy'])->name('admin.room-bookings.destroy');

        // Stock Management
        Route::resource('suppliers', SupplierController::class, ['as' => 'admin'])->except(['create', 'show', 'edit']);
        Route::resource('stock-categories', StockCategoryController::class, ['as' => 'admin'])->except(['create', 'show', 'edit']);
        Route::resource('stock-items', StockItemController::class, ['as' => 'admin'])->except(['create', 'show', 'edit']);
        Route::resource('stock-purchases', StockPurchaseController::class, ['as' => 'admin'])->only(['index', 'store', 'destroy']);
        Route::resource('stock-issues', StockIssueController::class, ['as' => 'admin'])->only(['index', 'store', 'destroy']);
        Route::get('stock-history', [StockHistoryController::class, 'index'])->name('admin.stock-history.index');

        Route::get('general-settings', [GeneralSettingsController::class, 'index'])->name('admin.general-settings');

        
        //Admin Settings Phone Number routes
        Route::post('phone-number', [GeneralSettingsController::class, 'storePhoneNumber'])->name('admin.phone-number.store');
        Route::put('phone-number/{id}', [GeneralSettingsController::class, 'updatePhoneNumber'])->name('admin.phone-number.update');
        Route::delete('phone-number/{id}', [GeneralSettingsController::class, 'deletePhoneNumber'])->name('admin.phone-number.delete');

        //Admin Settings Address routes 
        Route::post('address', [GeneralSettingsController::class, 'storeAddress'])->name('admin.address.store');
        Route::put('address/{id}', [GeneralSettingsController::class, 'updateAddress'])->name('admin.address.update');
        Route::delete('address/{id}', [GeneralSettingsController::class, 'deleteAddress'])->name('admin.address.delete');

        //Admin Settings Working hour routes 
        Route::post('working-hour', [GeneralSettingsController::class, 'storeWorkingHour'])->name('admin.working-hour.store');
        Route::put('working-hour/{id}', [GeneralSettingsController::class, 'updateWorkingHour'])->name('admin.working-hour.update');
        Route::delete('working-hour/{id}', [GeneralSettingsController::class, 'deleteWorkingHour'])->name('admin.working-hour.delete');

        //Admin Settings Social Media routes 
        Route::post('social-media-handles', [GeneralSettingsController::class, 'storeSocialMediaHandle'])->name('admin.social-media-handles.store');
        Route::put('social-media-handles/{id}', [GeneralSettingsController::class, 'updateSocialMediaHandle'])->name('admin.social-media-handles.update');
        Route::delete('social-media-handles/{id}', [GeneralSettingsController::class, 'deleteSocialMediaHandle'])->name('admin.social-media-handles.delete');

        //Admin Settings Livechat routes 
        Route::post('livechat', [GeneralSettingsController::class, 'createLiveChatScript'])->name('admin.livechat.store');
        Route::put('livechat/{id}', [GeneralSettingsController::class, 'updateLiveChatScript'])->name('admin.livechat.update');
        Route::delete('livechat/{id}', [GeneralSettingsController::class, 'destroyLiveChatScript'])->name('admin.livechat.destroy');

        //Admin Settings Orders
        Route::post('order-settings', [GeneralSettingsController::class, 'updateOrderSettings'])->name('admin.order-settings.update');
        Route::post('/site-settings/save', [GeneralSettingsController::class, 'siteSettings'])->name('site-settings.save');

        //Admin Terms And Condition routes
        Route::get('terms-and-conditions/edit', [TermsAndConditionController::class, 'edit'])->name('admin.terms.edit');
        Route::post('terms-and-conditions/update', [TermsAndConditionController::class, 'update'])->name('admin.terms.update');
    
    
        // Admin Privacy Policy routes
        Route::get('privacy-policy/edit', [PrivacyPolicyController::class, 'edit'])->name('admin.privacy-policy.edit');
        Route::post('privacy-policy/update', [PrivacyPolicyController::class, 'update'])->name('admin.privacy-policy.update');  
        
        
        //Admin testimonies routes
        Route::get('testimonies', [TestimonyController::class, 'index'])->name('admin.testimonies.index');
        Route::post('testimonies/store', [TestimonyController::class, 'store'])->name('admin.testimonies.store');
        Route::put('testimonies/{id}', [TestimonyController::class, 'update'])->name('admin.testimonies.update');
        Route::delete('testimonies/{id}', [TestimonyController::class, 'destroy'])->name('admin.testimonies.destroy');

        //Admin Hero Banners routes
        Route::get('banners', [BannerController::class, 'index'])->name('admin.banners.index');
        Route::post('banners/store', [BannerController::class, 'store'])->name('admin.banners.store');
        Route::put('banners/{id}', [BannerController::class, 'update'])->name('admin.banners.update');
        Route::delete('banners/{id}', [BannerController::class, 'destroy'])->name('admin.banners.destroy');
        Route::patch('banners/{id}/toggle-status', [BannerController::class, 'toggleStatus'])->name('admin.banners.toggle-status');


        // Admin Manage Users routes
        Route::get('users', [UserAdminController::class, 'index'])->name('admin.users.index');
        Route::post('users', [UserAdminController::class, 'store'])->name('admin.users.store');
        Route::put('users/{id}', [UserAdminController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{id}', [UserAdminController::class, 'destroy'])->name('admin.users.destroy');

        // Admin Payroll Management
        Route::resource('payroll', \App\Http\Controllers\Admin\PayrollController::class, ['as' => 'admin'])->except(['create', 'show', 'edit']);
        Route::get('payroll/{id}/payslip', [\App\Http\Controllers\Admin\PayrollController::class, 'payslip'])->name('admin.payroll.payslip');

        // Independent Kitchen Management Module
        Route::get('kitchen/kot', [\App\Http\Controllers\Admin\KitchenController::class, 'kot'])->name('admin.kitchen.kot');
        Route::get('kitchen/ingredients', [\App\Http\Controllers\Admin\KitchenController::class, 'ingredients'])->name('admin.kitchen.ingredients');
        Route::post('kitchen/ingredients', [\App\Http\Controllers\Admin\KitchenController::class, 'storeIngredient'])->name('admin.kitchen.ingredients.store');
        Route::put('kitchen/ingredients/{id}', [\App\Http\Controllers\Admin\KitchenController::class, 'updateIngredient'])->name('admin.kitchen.ingredients.update');
        Route::delete('kitchen/ingredients/{id}', [\App\Http\Controllers\Admin\KitchenController::class, 'destroyIngredient'])->name('admin.kitchen.ingredients.destroy');

        Route::get('kitchen/recipes', [\App\Http\Controllers\Admin\KitchenController::class, 'recipes'])->name('admin.kitchen.recipes');
        Route::post('kitchen/recipes', [\App\Http\Controllers\Admin\KitchenController::class, 'storeRecipe'])->name('admin.kitchen.recipes.store');
        Route::delete('kitchen/recipes/{id}', [\App\Http\Controllers\Admin\KitchenController::class, 'destroyRecipe'])->name('admin.kitchen.recipes.destroy');

        Route::get('kitchen/production', [\App\Http\Controllers\Admin\KitchenController::class, 'production'])->name('admin.kitchen.production');
        Route::post('kitchen/production', [\App\Http\Controllers\Admin\KitchenController::class, 'storeProduction'])->name('admin.kitchen.production.store');

        Route::get('kitchen/reports', [\App\Http\Controllers\Admin\KitchenController::class, 'reports'])->name('admin.kitchen.reports');

        // Independent Bar Management Module
        Route::get('bar/inventory', [\App\Http\Controllers\Admin\BarController::class, 'inventory'])->name('admin.bar.inventory');
        Route::post('bar/inventory', [\App\Http\Controllers\Admin\BarController::class, 'storeDrink'])->name('admin.bar.inventory.store');
        Route::put('bar/inventory/{id}', [\App\Http\Controllers\Admin\BarController::class, 'updateDrink'])->name('admin.bar.inventory.update');
        Route::delete('bar/inventory/{id}', [\App\Http\Controllers\Admin\BarController::class, 'destroyDrink'])->name('admin.bar.inventory.destroy');

        Route::get('bar/recipes', [\App\Http\Controllers\Admin\BarController::class, 'recipes'])->name('admin.bar.recipes');
        Route::post('bar/recipes', [\App\Http\Controllers\Admin\BarController::class, 'storeRecipe'])->name('admin.bar.recipes.store');
        Route::delete('bar/recipes/{id}', [\App\Http\Controllers\Admin\BarController::class, 'destroyRecipe'])->name('admin.bar.recipes.destroy');

        Route::get('bar/tickets', [\App\Http\Controllers\Admin\BarController::class, 'tickets'])->name('admin.bar.tickets');
        Route::get('bar/reports', [\App\Http\Controllers\Admin\BarController::class, 'reports'])->name('admin.bar.reports');

    });
        
});

 