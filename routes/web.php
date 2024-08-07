<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\OrderController;
use Illuminate\Support\Facades\Route;

// FRONTEND
Route::get('/', [FrontendController::class, 'index'])->name('/');
Route::get('/products', [FrontendController::class, 'products'])->name('products');
Route::get('/product-details/{product:slug}', [FrontendController::class, 'productDetails'])->name('productDetails');
Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
Route::post('/cart/add', [FrontendController::class, 'addCart'])->name('addCart');
Route::post('/cart/update',  [FrontendController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove',  [FrontendController::class, 'removeCart'])->name('cart.remove');
Route::get('/cart/count', [FrontendController::class, 'getCartCount']);


//ADMIN
Route::group(["prefix" => "admin", 'middleware' => ['auth:sanctum', config('jetstream.auth_session'), 'has_any_admin_role'], "as" => 'admin.'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('category', [CategoryController::class, 'index'])->name('category');
    Route::get('category/{category:slug}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::delete('category/{category}', [CategoryController::class, 'destroy']);

    Route::get('product', [ProductController::class, 'create'])->name('product');
    Route::get('manage-products', [ProductController::class, 'manage'])->name('productsManage');
    Route::get('product/{product:slug}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::delete('product/{product}', [ProductController::class, 'destroy']);

    Route::get('manage-orders', [ManageOrderController::class, 'manageOrder'])->name('manageOrders');
    Route::post('orders/{order}/update-status', [ManageOrderController::class, 'updateStatus'])->name('order.updateStatus');
});


//USER
Route::group(["prefix" => "user", 'middleware' => ['auth:sanctum', config('jetstream.auth_session'), 'role:user'], "as" => 'user.'], function () {
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');
    Route::get('/thank-you', [FrontendController::class, 'thankYou'])->name('thankYou');

    Route::get('orders', [OrderController::class, 'orders'])->name('orders');
});

//USER AND ADMIN
Route::group(["prefix" => "", 'middleware' => ['auth:sanctum', config('jetstream.auth_session'), 'require_roles']], function () {
    Route::get('order-items/{order}', [OrderController::class, 'orderItems'])->name('orderItems');
});
