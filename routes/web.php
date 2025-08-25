<?php

use App\Http\Controllers\OrderController;
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
use App\Http\Controllers\XmlImportController;

use App\Http\Controllers\admin\AdminProductsController;
use App\Http\Controllers\admin\AdminCategoryController;
use App\Http\Controllers\admin\AdminCatalogController;

Route::get('/', [indexController::class, 'index'])->name('welcome');
Route::get('/thank-you', function() {
    return view('thanks_you_page');
})->name('thanks');
Route::get('/uhoda-korystuvacha', function() {
    return view('information.uhoda_korystuvacha');
})->name('uhoda_korystuvacha');
Route::get('/dohovir-oferty', function() {
    return view('information.dohovir_oferty');
})->name('dohovir_oferty');
Route::get('/privacy-policy', function() {
    return view('information.privacy_policy');
})->name('privacy_policy');
Route::get('/oplata-i-dostavka', function() {
    return view('information.oplata_i_dostavka');
})->name('oplata_i_dostavka');
Route::get('/obmin-ta-povernennia', function() {
    return view('information.obmin_ta_povernennia');
})->name('obmin_ta_povernennia');
Route::get('/kontaktna-informatsiia', function() {
    return view('information.kontaktna_informatsiia');
})->name('kontaktna_informatsiia');
Route::get('/pro-kompaniiu', function() {
    return view('information.about');
})->name('pro_kompaniiu');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/api/products', function () {
    return \App\Models\Product::inRandomOrder()->paginate(80);
});
Route::post('/order-submit', [OrderController::class, 'submit'])->name('order_submit');
Route::post('/save-abandoned-cart', [OrderController::class, 'saveAbandoned']);

Route::get('/checkout', [indexController::class, 'checkout'])->name('checkout');
Route::get('/cities', [indexController::class, 'getCities']);
Route::post('/warehouses', [indexController::class, 'getWarehouses']);

Route::group(['prefix' => 'catalog'], function () {
    Route::get('{url}', [CatalogController::class, 'product_page'])->name('catalog_product_page');
    Route::get('categoriya/{url}', [CatalogController::class, 'category_page'])->name('catalog_category_page');
});

Route::get('/search', [CatalogController::class, 'searchSubmit'])->name('search.index');

Route::group(['prefix' => 'admin', 'middleware' => 'email.role'], function () {
    Route::get('/', [AdminMainController::class, 'index'])->name('admin.index');
    Route::get('/table/orders', [AdminMainController::class, 'orders'])->name('admin.orders');
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
    Route::post('/generate-xml', [XmlImportController::class, 'generate'])->name('xml.generator');
});

Auth::routes();

Route::get('logout', function () {
    Auth::logout();
    return redirect('/');
});