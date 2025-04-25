<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/somepath', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/womans', [ProductController::class, 'indexWomans'])->name('womans.index');
Route::get('/mans', [ProductController::class, 'indexMans'])->name('mans.index');
Route::get('/kids', [ProductController::class, 'indexKids'])->name('kids.index');
Route::get('/accessorys', [ProductController::class, 'indexAccessory'])->name('accessory.index');
Route::get('/shoes', [ProductController::class, 'indexShoes'])->name('shoes.index');
Route::get('/sale', [ProductController::class, 'indexSale'])->name('sale.index');
Route::get('/admin/addProduct', [ProductController::class, 'create'])->name('product.create');
Route::post('/products', [ProductController::class, 'store'])->name('product.store');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::patch('/products/{product}', [ProductController::class, 'update'])->name('product.update');
Route::delete('/products/{product}', [ProductController::class, 'delete'])->name('product.delete');

Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/admin/addBrand', [BrandController::class, 'create'])->name('brand.create');
Route::post('/brands', [BrandController::class, 'store'])->name('brand.store');
Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brand.show');
Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brand.edit');
Route::patch('/brands/{brand}', [BrandController::class, 'update'])->name('brand.update');
Route::delete('/brands/{brand}', [BrandController::class, 'delete'])->name('brand.delete');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/admin/addProduct', [AdminController::class, 'indexNewProduct'])->name('addProduct.index');
Route::get('/admin/addBrand', [AdminController::class, 'indexNewBrand'])->name('addBrand.index');
Route::get('/admin/addCollection', [AdminController::class, 'indexNewCollection'])->name('addCollection.index');

Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/login', [LoginController::class, 'authentication'])->name('authentication');
Route::post('/register', [RegisterController::class, 'registerCreate'])->name('registerCreate');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{productId}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::delete('/cart/{productId}', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorite.index');
Route::post('/favorite/add/{productId}', [FavoriteController::class, 'addToFavorites'])->name('favorites.add');
Route::delete('/favorites/{productId}', [FavoriteController::class, 'removeFromFavorites'])->name('favorites.remove');

Route::get('/admin/orders', [OrderController::class, 'completedOrders'])->name('admin.orders.index');
Route::post('/admin/orders/{id}/update', [OrderController::class, 'updateStatus'])->name('admin.orders.update');
Route::get('/admin/preorders', [OrderController::class, 'index'])->name('admin.preorders.index');
Route::get('/order/confirmation/{preOrderId}', [CartController::class, 'confirmation'])->name('order.confirmation');
Route::get('/order/invoice/{preOrderId}', [CartController::class, 'downloadInvoice'])->name('order.invoice.download');
Route::get('/search', [ProductController::class, 'search'])->name('search');