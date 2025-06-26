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

Route::get('/', [indexController::class, 'index'])->name('welcome');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/import-products', [ProductImportController::class, 'import'])->name('products.import');
Route::get('/api/products', function () {
    return \App\Models\Product::paginate(12)->items();
});

Route::get('/checkout', [indexController::class, 'checkout'])->name('checkout');
Route::get('/cities', [indexController::class, 'getCities']);
Route::post('/warehouses', [indexController::class, 'getWarehouses']);


Route::post('/import-products', [AdminProductsUploadController::class, 'upload'])->name('products.upload');

Route::group(['prefix' => 'catalog'], function () {
    Route::get('{url}', [CatalogController::class, 'product_page'])->name('catalog_product_page');
    Route::get('categoriya/{url}', [CatalogController::class, 'category_page'])->name('catalog_category_page');
});

Auth::routes();