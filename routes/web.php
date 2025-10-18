<?php

use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\indexController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\AdminProductsUploadController;
use App\Http\Controllers\admin\AdminMainController;

use App\Http\Controllers\admin\AdminProductsController;
use App\Http\Controllers\admin\AdminCategoryController;
use App\Http\Controllers\admin\AdminCatalogController;
use App\Http\Controllers\admin\AdminOrdersController;

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

Route::get('/', [indexController::class, 'index'])->name('welcome');
Route::get('/home', [indexController::class, 'index'])->name('home');
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

Route::get('/api/products', function () {
    return \App\Models\Product::select('id', 'name', 'articule', 'price', 'discount', 'image_path', 'availability', 'url')
        ->inRandomOrder()
        ->paginate(80);
});
Route::post('/order-submit', [OrderController::class, 'submit'])->name('order_submit');
Route::post('/save-abandoned-cart', [OrderController::class, 'saveAbandoned']);

Route::match(['get', 'post'], '/catalog', [indexController::class, 'catalog'])->name('catalog');
Route::get('/checkout', [indexController::class, 'checkout'])->name('checkout');
Route::get('/cities', [indexController::class, 'getCities']);
Route::post('/warehouses', [indexController::class, 'getWarehouses']);

Route::group(['prefix' => 'catalog'], function () {
    Route::get('search', [CatalogController::class, 'search'])->name('catalog.search');
    Route::match(['get', 'post'], 'categoriya/{url}', [CatalogController::class, 'category_page'])->name('catalog_category_page');
    Route::get('{url}', [CatalogController::class, 'product_page'])->name('product_page');
    Route::get('{url}', [CatalogController::class, 'product_page'])->name('catalog_product_page');
});

Route::get('/search', [CatalogController::class, 'searchSubmit'])->name('search.index');
Route::get('/api/recommended-products', [CatalogController::class, 'getRecommendedProducts'])->name('api.recommended_products');
Route::get('/test-recommended', [CatalogController::class, 'getRecommendedProducts'])->name('test.recommended_products');

Route::group(['prefix' => 'admin', 'middleware' => 'email.role', 'as' => 'admin.'], function () {
    Route::get('/', [AdminMainController::class, 'index'])->name('index');
    Route::get('/table/orders', [AdminMainController::class, 'index'])->name('orders');
    Route::get('/table/carts', [AdminMainController::class, 'index'])->name('carts');

    Route::resource('products', AdminProductsController::class)->names('products');
    Route::resource('category', AdminCategoryController::class)->names('category');
    Route::resource('catalog', AdminCatalogController::class)->names('catalog');
    
    // Маршруты для заказов
    Route::resource('orders', AdminOrdersController::class)->names('orders');
    Route::get('/orders/{id}/export-pdf', [AdminOrdersController::class, 'exportPdf'])->name('orders.exportPdf');
    Route::put('/orders/{id}/status', [AdminOrdersController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/statistics', [AdminOrdersController::class, 'statistics'])->name('orders.statistics');
    Route::get('/orders/create/test', [AdminOrdersController::class, 'createTestOrder'])->name('orders.createTest');

    Route::get('/edit/products', [AdminMainController::class, 'edit_products'])->name('edit_products');
    Route::prefix('products')->name('products.')->group(function () {
        Route::post('/add-image/{id}', [AdminProductsController::class, 'addImage'])->name('addImage');
        Route::get('/destroy-image/{id}/{image}', [AdminProductsController::class, 'destroyImage'])->name('destroyImage');
        Route::get('/first-image/{id}/{image}', [AdminProductsController::class, 'firstImage'])->name('firstImage');
    });

    Route::post('/import-products', [ProductImportController::class, 'import'])->name('excel.upload');
    
    // Тестовый маршрут для проверки удаления
    Route::delete('/test-delete/{id}', [AdminProductsController::class, 'destroy'])->name('test.delete');
});

// Маршруты для управления изображениями товаров в админке
Route::prefix('admin/resources/products')->middleware(['auth'])->group(function () {
    Route::post('/{id}/set-main-image', [App\Http\Controllers\Admin\ImageController::class, 'setAsMainImage']);
    Route::post('/{id}/delete-gallery-image', [App\Http\Controllers\Admin\ImageController::class, 'deleteImage']);
    Route::post('/{id}/replace-gallery-image', [App\Http\Controllers\Admin\ImageController::class, 'replaceImage']);
});

// Маршруты для генерации PDF инвойсов
Route::get('/invoice/{orderId}/download', [App\Http\Controllers\InvoiceController::class, 'generateInvoice'])->name('invoice.download');
Route::get('/invoice/{orderId}/view', [App\Http\Controllers\InvoiceController::class, 'viewInvoice'])->name('invoice.view');

Auth::routes();

Route::get('logout', function () {
    Auth::logout();
    return redirect('/');
});