<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products/search', [CatalogController::class, 'search']);

// Nova Poshta API
Route::prefix('nova-poshta')->group(function () {
    Route::get('/cities', function () {
        try {
            $cities = [
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567628', 'Description' => 'Київ'],
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567629', 'Description' => 'Харків'],
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567630', 'Description' => 'Одеса'],
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567631', 'Description' => 'Дніпро'],
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567632', 'Description' => 'Львів'],
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567633', 'Description' => 'Запоріжжя'],
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567634', 'Description' => 'Миколаїв'],
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567635', 'Description' => 'Вінниця'],
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567636', 'Description' => 'Херсон'],
                ['Ref' => '8d5a980d-391c-11dd-90d9-001a92567637', 'Description' => 'Полтава']
            ];
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load cities'], 500);
        }
    });
    
    Route::post('/warehouses', function (Request $request) {
        try {
            $cityRef = $request->input('cityRef');
            $warehouses = [
                ['Ref' => 'warehouse1', 'Description' => 'Відділення №1 (до 30 кг)'],
                ['Ref' => 'warehouse2', 'Description' => 'Відділення №2 (до 30 кг)'],
                ['Ref' => 'warehouse3', 'Description' => 'Відділення №3 (до 30 кг)'],
                ['Ref' => 'warehouse4', 'Description' => 'Відділення №4 (до 30 кг)'],
                ['Ref' => 'warehouse5', 'Description' => 'Відділення №5 (до 30 кг)']
            ];
            return response()->json($warehouses);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load warehouses'], 500);
        }
    });
});

// API для управления категориями
Route::prefix('categories')->group(function () {
    Route::get('/tree', [CategoryController::class, 'tree']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
    Route::delete('/{categoryId}/products/{productId}', [CategoryController::class, 'detachProduct']);
    Route::post('/{id}/move', [CategoryController::class, 'move']);
});