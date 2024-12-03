<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Admin\ManageController;
use App\Http\Controllers\Client\CouponController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ManageOrderController;
use App\Http\Controllers\Client\RestaurantController;

Route::get('/', [UserController::class, 'Index'])->name('index');

Route::get('/dashboard', function () {
    return view('frontend.dashboard.profile');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/profile/store', [UserController::class, 'ProfileStore'])->name('profile.store');
    Route::get('/user/logout', [UserController::class, 'UserLogout'])->name('user.logout');
    Route::get('/change/password', [UserController::class, 'ChangePassword'])->name('change.password');
    Route::post('/user/password/update', [UserController::class, 'UserPasswordUpdate'])->name('user.password.update');

    // Get Wishlist data for user 
    Route::get('/all/wishlist', [HomeController::class, 'AllWishList'])->name('all.wishlist');
    Route::get('/remove/wishlist/{id}', [HomeController::class, 'RemoveWishlist'])->name('remove.wishlist');

    Route::controller(ManageOrderController::class)->group(function () {
        Route::get('/user/order/list', 'UserOrderList')->name('user.order.list');
        Route::get('/user/order/details/{id}', 'UserOrderDetails')->name('user.order.details'); 
    });
});

require __DIR__ . '/auth.php';


// Admin Routing
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.admin_dashboard');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/profile/update', [AdminController::class, 'AdminProfileUpdate'])->name('admin.profile.update');
});

Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');
Route::post('/admin/login_submit', [AdminController::class, 'AdminLoginSubmit'])->name('admin.login_submit');
Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
Route::get('/admin/forget_password', [AdminController::class, 'AdminForgetPassword'])->name('admin.forget_password');

Route::post('/admin/password_submit', [AdminController::class, 'AdminPasswordSubmit'])->name('admin.password_submit');

Route::get('/admin/reset-password/{token}/{email}', [AdminController::class, 'AdminResetPassword'])->name('admin.reset_password');
Route::post('/admin/reset_password_submit', [AdminController::class, 'AdminResetPasswordSubmit'])->name('admin.reset_password_submit');


// Client Routing
Route::middleware('client')->group(function () {
    Route::get('/client/dashboard', [ClientController::class, 'ClientDashboard'])->name('client.client_dashboard');
    Route::get('/client/profile', [ClientController::class, 'ClientProfile'])->name('client.profile');
    Route::post('/client/profile/store', [ClientController::class, 'ClientProfileStore'])->name('client.profile.store');
    Route::get('/client/change/password', [ClientController::class, 'ClientChangePassword'])->name('client.change.password');
    Route::post('/client/password/update', [ClientController::class, 'ClientPasswordUpdate'])->name('client.password.update');
});

Route::get('/client/login', [ClientController::class, 'ClientLogin'])->name('client.login');
Route::post('/client/login_submit', [ClientController::class, 'ClientLoginSubmit'])->name('client.login_submit');
Route::get('/client/register', [ClientController::class, 'ClientRegister'])->name('client.register');
Route::post('/client/register_submit', [ClientController::class, 'ClientRegisterSubmit'])->name('client.register_submit');
Route::get('/client/logout', [ClientController::class, 'ClientLogout'])->name('client.logout');


// All Admin Category
Route::middleware('admin')->group(function () {

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/all/category', 'AllCategory')->name('all.category');
        Route::get('/add/category', 'AddCategory')->name('add.category');
        Route::post('/store/category', 'StoreCategory')->name('store.category');
        Route::get('/edit/category/{id}', 'EditCategory')->name('edit.category');
        Route::post('/update/category', 'UpdateCategory')->name('update.category');
        Route::get('/delete/category/{id}', 'DeleteCategory')->name('delete.category');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/all/city', 'AllCity')->name('all.city');
        // Route::get('/add/city', 'AddCity')->name('add.city');
        Route::post('/store/city', 'StoreCity')->name('store.city');
        Route::get('/edit/city/{id}', 'EditCity')->name('edit.city');
        Route::post('/update/city', 'UpdateCity')->name('update.city');
        Route::get('/delete/city/{id}', 'DeleteCity')->name('delete.city');
    });

    Route::controller(ManageController::class)->group(function () {
        Route::get('/admin/all/product', 'AdminAllProduct')->name('admin.all.product');
        Route::get('admin/add/product', 'AdminAddProduct')->name('admin.add.product');
        Route::post('/admin/store/product', 'AdminStoreProduct')->name('admin.store.product');
        Route::get('/admin/edit/product/{id}', 'AdminEditProduct')->name('admin.edit.product');
        Route::post('/admin/update/product', 'AdminUpdateProduct')->name('admin.update.product');
        Route::get('/admin/delete/product/{id}', 'AdminDeleteProduct')->name('admin.delete.product');
        // Route::get('/changeStatus', 'ChangeStatus');
    });

    Route::controller(ManageController::class)->group(function () {
        Route::get('/pending/restaurant', 'PendingRestaurant')->name('pending.restaurant');
        Route::get('/clientChangeStatus', 'ClientChangeStatus');
        Route::get('/approve/restaurant', 'ApproveRestaurant')->name('approve.restaurant');
    });

    Route::controller(ManageController::class)->group(function () {
        Route::get('/all/banner', 'AllBanner')->name('all.banner');
        Route::post('/banner/store', 'BannerStore')->name('banner.store');
        Route::get('/edit/banner/{id}', 'EditBanner');
        Route::post('/banner/update', 'BannerUpdate')->name('banner.update');
        Route::get('/delete/banner/{id}', 'DeleteBanner')->name('delete.banner');
    });

    Route::controller(ManageOrderController::class)->group(function () {
        Route::get('/pending/order', 'PendingOrder')->name('pending.order');
        Route::get('/confirm/order', 'ConfirmOrder')->name('confirm.order');
        Route::get('/processing/order', 'ProcessingOrder')->name('processing.order');
        Route::get('/delivered/order', 'DeliveredOrder')->name('delivered.order');

        Route::get('/admin/order/details/{id}', 'AdminOrderDetails')->name('admin.order.details');
    });

    Route::controller(ManageOrderController::class)->group(function () {
        Route::get('/pending_to_confirm/{id}', 'PendingToConfirm')->name('pending_to_confirm');
        Route::get('/confirm_to_processing/{id}', 'ConfirmToProcessing')->name('confirm_to_processing');
        Route::get('/processing_to_delivered/{id}', 'ProcessingToDelivered')->name('processing_to_delivered');
    });
});



Route::middleware(['client', 'status'])->group(function () {

    Route::controller(RestaurantController::class)->group(function () {
        Route::get('/all/menu', 'AllMenu')->name('all.menu');
        Route::get('/add/menu', 'AddMenu')->name('add.menu');
        Route::post('/store/menu', 'StoreMenu')->name('store.menu');
        Route::get('/edit/menu/{id}', 'EditMenu')->name('edit.menu');
        Route::post('/update/menu', 'UpdateMenu')->name('update.menu');
        Route::get('/delete/menu/{id}', 'DeleteMenu')->name('delete.menu');
    });

    Route::controller(RestaurantController::class)->group(function () {
        Route::get('/all/product', 'AllProduct')->name('all.product');
        Route::get('/add/product', 'AddProduct')->name('add.product');
        Route::post('/store/product', 'StoreProduct')->name('store.product');
        Route::get('/edit/product/{id}', 'EditProduct')->name('edit.product');
        Route::post('/update/product', 'UpdateProduct')->name('update.product');
        Route::get('/delete/product/{id}', 'DeleteProduct')->name('delete.product');
        // Route::get('/changeStatus', 'ChangeStatus');
    });

    route::controller(RestaurantController::class)->group(function () {
        Route::get('/all/gallery', 'AllGallery')->name('all.gallery');
        Route::get('/add/gallery', 'AddGallery')->name('add.gallery');
        Route::post('/store/gallery', 'StoreGallery')->name('store.gallery');
        Route::get('/edit/gallery/{id}', 'EditGallery')->name('edit.gallery');
        Route::post('/update/gallery', 'UpdateGallery')->name('update.gallery');
        Route::get('/delete/gallery/{id}', 'DeleteGallery')->name('delete.gallery');
    });

    Route::controller(CouponController::class)->group(function () {
        Route::get('/all/coupon', 'AllCoupon')->name('all.coupon');
        Route::get('/add/coupon', 'AddCoupon')->name('add.coupon');
        Route::post('/store/coupon', 'StoreCoupon')->name('store.coupon');
        Route::get('/edit/coupon/{id}', 'EditCoupon')->name('edit.coupon');
        Route::post('/update/coupon', 'UpdateCoupon')->name('update.coupon');
        Route::get('/delete/coupon/{id}', 'DeleteCoupon')->name('delete.coupon');
    });

    Route::controller(ManageOrderController::class)->group(function () {
        Route::get('/all/client/orders', 'AllClientOrders')->name('all.client.orders');
        Route::get('/client/order/details/{id}', 'ClientOrderDetails')->name('client.order.details');
    });
});

Route::get('/changeStatus', [RestaurantController::class, 'ChangeStatus']);

Route::controller(HomeController::class)->group(function () {
    Route::get('/restaurant/details/{id}', 'RestaurantDetails')->name('res.details');
    Route::get('/add-wish-list/{id}', 'AddWishList');
});

Route::controller(CartController::class)->group(function () {
    Route::get('/add_to_cart/{id}', 'AddToCart')->name('add_to_cart');
    Route::post('/cart/update-quantity', 'updateCartQuanity')->name('cart.updateQuantity');
    Route::post('/cart/remove', 'CartRemove')->name('cart.remove');
    Route::post('/apply-coupon', 'ApplyCoupon');
    Route::get('/remove-coupon', 'CouponRemove');
    Route::get('/checkout', 'ShopCheckout')->name('checkout');
});

Route::controller(OrderController::class)->group(function () {
    Route::post('/cash_order', 'CashOrder')->name('cash_order');
});
