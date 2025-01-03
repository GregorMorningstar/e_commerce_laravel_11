<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop',[ShopController::class,'index'])->name('shop.index');
Route::get('/shop/product/details/{id}',[ShopController::class,'details_product'])->name('shop.details_product');

Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::post('/cart/add',[CartController::class,'add_to_cart'])->name('cart.add');
Route::put('/cart/increase-quantity/{rowId}',[CartController::class,'increase_cart_quantity'])->name('cart.increase_quantity');
Route::put('/cart/decrease-quantity/{rowId}',[CartController::class,'decrease_cart_quantity'])->name('cart.decrease_quantity');
Route::delete('/cart/remove/{rowId}',[CartController::class,'remove_item'])->name('cart.remove');
Route::delete('/cart/clear',[CartController::class,'clear_cart'])->name('cart.clear');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::middleware(['auth', 'UserMiddleware'])->group(function () {
    Route::get('user/dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth', 'AdminMiddleware'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.index');
//    brand
    Route::get('admin/brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('admin/brand/add',[AdminController::class,'brand_add'])->name('admin.add_brand');
    Route::post('admin/brand/store',[AdminController::class,'brand_store'])->name('admin.store_brand');
    Route::get('/admin/brand/edit/{id}',[AdminController::class,'brand_edit'])->name('admin.edit_brand');
    Route::put('/admin/brand/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brand/delete/{id}', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');
//  categorie
    Route::get('admin/categories',[AdminController::class,'categories'])->name('admin.categories');
    Route::get('admin/category/add',[AdminController::class,'category_add'])->name('admin.add_category');
    Route::post('admin/category/store',[AdminController::class,'category_store'])->name('admin.store_category');
    Route::get('/admin/category/edit/{id}',[AdminController::class,'category_edit'])->name('admin.edit_category');
    Route::put('/admin/category/update', [AdminController::class, 'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/delete/{id}', [AdminController::class, 'category_delete'])->name('admin.category.delete');
//produkty
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('admin/products/add',[AdminController::class,'product_add'])->name('admin.add_product');
    Route::post('/admin/products/store', [AdminController::class, 'product_store'])->name('admin.store_product');
    Route::get('/admin/products/edit/{id}',[AdminController::class,'product_edit'])->name('admin.edit_products');
    Route::put('/admin/products/update', [AdminController::class, 'products_update'])->name('admin.update.products');
    Route::delete('/admin/product/delete/{id}', [AdminController::class, 'products_delete'])->name('admin.delete_product');

});
