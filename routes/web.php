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

Route::get('/', [indexController::class, 'index'])->name('welcome');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/import-products', [ProductImportController::class, 'import'])->name('products.import');
Route::get('/api/products', function () {
    return \App\Models\Product::paginate(12)->items();
});

Auth::routes();