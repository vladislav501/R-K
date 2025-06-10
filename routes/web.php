<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClothingTypeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PickupPointController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [StaticPageController::class, 'home'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [ProductController::class, 'category'])->name('products.category');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/delivery', [StaticPageController::class, 'delivery'])->name('delivery');
Route::get('/payment', [StaticPageController::class, 'payment'])->name('payment');
Route::get('/pickup', [StaticPageController::class, 'pickup'])->name('pickup');
Route::get('/about', [StaticPageController::class, 'about'])->name('about');
Route::get('/stores', [StaticPageController::class, 'stores'])->name('stores');
Route::get('/contact', [StaticPageController::class, 'contact'])->name('contact');
Route::get('/privacy', [StaticPageController::class, 'privacy'])->name('privacy');

Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'register'])->name('register');
    Route::post('/register', [UserController::class, 'store']);
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/login', [UserController::class, 'authenticate']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('home');
    })->name('logout');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile.index');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/orders/{cart}/confirm', [UserController::class, 'confirmOrder'])->name('orders.confirm');

    Route::post('/cart/place-order', [CartController::class, 'placeOrder'])->name('cart.place-order');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/cart/confirmation/{cartId}', [CartController::class, 'confirmation'])->name('cart.confirmation');
    Route::get('/cart/download-receipt/{cartId}', [CartController::class, 'downloadReceipt'])->name('cart.downloadReceipt');

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{product}', [FavoriteController::class, 'add'])->name('favorites.add');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

Route::prefix('admin')->middleware(['auth', 'can:is-admin'])->name('admin.')->group(function () {
    Route::get('/', [ProductController::class, 'admin'])->name('index');
    Route::resource('brands', BrandController::class)->except(['show']);
    Route::resource('categories', CategoryController::class);
    Route::patch('categories/{category}/toggle', [CategoryController::class, 'toggleActive'])->name('categories.toggle');
    Route::resource('clothing_types', ClothingTypeController::class)->except(['show']);
    Route::resource('colors', ColorController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('sizes', SizeController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('collections', CollectionController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::resource('pickup_points', PickupPointController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/orders', [ManagerController::class, 'index'])->name('orders.index');
    Route::get('/supplies', [SupplyController::class, 'index'])->name('supplies.index');
    Route::get('/supplies/create', [SupplyController::class, 'create'])->name('supplies.create');
    Route::post('/supplies', [SupplyController::class, 'store'])->name('supplies.store');
    Route::get('/supplies/archive', [SupplyController::class, 'archive'])->name('supplies.archive');
    Route::get('/supplies/{supply}/download', [SupplyController::class, 'downloadSupplyList'])->name('supplies.download');
});

Route::prefix('manager')->middleware('can:is-manager')->name('manager.')->group(function () {
    Route::get('/', [ManagerController::class, 'dashboard'])->name('index');
    Route::get('/orders', [ManagerController::class, 'index'])->name('orders.index');
    Route::get('/orders/archive', [ManagerController::class, 'ordersArchive'])->name('orders.archive');
    Route::get('/orders/{cart}', [ManagerController::class, 'orderShow'])->name('orders.show');
    Route::put('/orders/{cart}/status', [ManagerController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    Route::get('/products', [ManagerController::class, 'productsIndex'])->name('products.index');
    Route::put('/products/{product}/availability', [ManagerController::class, 'updateProductAvailability'])->name('products.updateAvailability');
    Route::get('/supplies', [ManagerController::class, 'suppliesIndex'])->name('supplies.index');
    Route::get('/supplies/archive', [ManagerController::class, 'suppliesArchive'])->name('supplies.archive');
    Route::get('/supplies/{supply}/check', [ManagerController::class, 'showSupplyCheck'])->name('supply.check');
    Route::post('/supplies/{supply}/confirm', [ManagerController::class, 'confirmSupply'])->name('supplies.confirm');
    Route::get('/supplies/{supply}/download', [ManagerController::class, 'downloadSupplyList'])->name('supplies.download');
});