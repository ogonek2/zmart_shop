<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\indexController;
use App\Http\Controllers\CatalogController;

use App\Http\Controllers\AdminProductsUploadController;
use App\Http\Controllers\admin\AdminMainController;

use App\Http\Controllers\admin\AdminProductsController;
use App\Http\Controllers\admin\AdminCategoryController;
use App\Http\Controllers\admin\AdminCatalogController;

Route::get('/', [indexController::class, 'index'])->name('welcome');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/api/products', function () {
    return \App\Models\Product::inRandomOrder()->paginate(32)->items();
});

Route::get('/checkout', [indexController::class, 'checkout'])->name('checkout');
Route::get('/cities', [indexController::class, 'getCities']);
Route::post('/warehouses', [indexController::class, 'getWarehouses']);

Route::group(['prefix' => 'catalog'], function () {
    Route::get('{url}', [CatalogController::class, 'product_page'])->name('catalog_product_page');
    Route::get('categoriya/{url}', [CatalogController::class, 'category_page'])->name('catalog_category_page');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminMainController::class, 'index'])->name('admin.index');
    Route::get('/table/orders', [AdminMainController::class, 'index'])->name('admin.orders');
    Route::get('/table/carts', [AdminMainController::class, 'index'])->name('admin.carts');

    Route::resource('products', AdminProductsController::class);
    Route::resource('category', AdminCategoryController::class);
    Route::resource('catalog', AdminCatalogController::class);

    Route::get('/edit/products', [AdminMainController::class, 'edit_products'])->name('admin.edit_products');
    Route::prefix('products')->name('products.')->group(function () {
        Route::post('/add-image/{id}', [AdminProductsController::class, 'addImage'])->name('addImage');
        Route::get('/destroy-image/product={id}/{image}', [AdminProductsController::class, 'destroyImage'])->name('destroyImage');
        Route::get('/first-image/product={id}/{image}', [AdminProductsController::class, 'firstImage'])->name('firstImage');
    });

    Route::post('/import-products', [ProductImportController::class, 'import'])->name('excel.upload');
});

Auth::routes();

Route::get('logout', function () {
    Auth::logout();
});