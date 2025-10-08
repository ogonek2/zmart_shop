<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\productImage;

class CatalogController extends Controller
{
    public function product_page($url)
    {
        try {
            \Log::info('Попытка загрузки товара с URL: ' . $url);
            
            $getProduct = Product::where('url', $url)->firstOrFail();
            \Log::info('Товар найден: ' . $getProduct->id . ' - ' . $getProduct->name);
            \Log::info('Путь к изображению товара: ' . $getProduct->image_path);
            
            $images = productImage::where('product_id', $getProduct->id)->get();
            \Log::info('Найдено изображений: ' . $images->count());
            
            // Получаем характеристики из шаблона (новая система)
            $characteristics = $getProduct->getTemplateCharacteristics();
            $modifications = $getProduct->getTemplateModifications();
            $additionalFields = $getProduct->getTemplateAdditionalFields();
            
            \Log::info('Характеристики из шаблона: ' . count($characteristics));
            
            // Получаем рекомендуемые товары из тех же категорий
            $recommendedProducts = collect();
            try {
                if ($getProduct->categories->count() > 0) {
                    \Log::info('Категории товара: ' . $getProduct->categories->pluck('name')->implode(', '));
                    $categoryIds = $getProduct->categories->pluck('id')->toArray();
                    \Log::info('ID категорий: ' . implode(', ', $categoryIds));
                    
                    $recommendedProducts = Product::whereHas('categories', function($query) use ($categoryIds) {
                        $query->whereIn('categories.id', $categoryIds);
                    })
                    ->where('id', '!=', $getProduct->id) // Исключаем текущий товар
                    ->inRandomOrder() // Случайный порядок
                    ->limit(8) // Максимум 8 товаров
                    ->get(['id', 'name', 'price', 'discount', 'image_path', 'url', 'is_wholesale', 'wholesale_price']);
                    
                    \Log::info('Рекомендуемые товары найдены: ' . $recommendedProducts->count());
                } else {
                    \Log::info('У товара нет категорий');
                }
            } catch (\Exception $e) {
                \Log::error('Ошибка при получении рекомендуемых товаров: ' . $e->getMessage());
                $recommendedProducts = collect();
            }
            
            \Log::info('Готовимся к отображению страницы товара');
            
            return view('productPage', [
                'product' => $getProduct,
                'images' => $images,
                'characteristics' => $characteristics,
                'modifications' => $modifications,
                'additionalFields' => $additionalFields,
                'recommendedProducts' => $recommendedProducts,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('Товар не найден: ' . $url);
            abort(404, 'Товар не найден');
        } catch (\Exception $e) {
            \Log::error('Ошибка при загрузке страницы товара: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            abort(500, 'Произошла ошибка при загрузке страницы');
        }
    }
    public function category_page($url)
    {
        $getCategory = Category::where('url', $url)->firstOrFail();

        // Получаем подкатегории текущей категории
        $subcategories = $getCategory->childCategories()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Получаем продукты, связанные с категорией
        $getProducts = $getCategory->products()->paginate(45); // укажи нужное количество

        $paginationData = [
            'current_page' => $getProducts->currentPage(),
            'last_page' => $getProducts->lastPage(),
            'per_page' => $getProducts->perPage(),
            'total' => $getProducts->total(),
            'path' => $getProducts->path(),
        ];

        return view('categoryPage', [
            'category' => $getCategory,
            'subcategories' => $subcategories,
            'products' => $getProducts,
            'paginationData' => $paginationData
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('articule', 'like', '%' . $query . '%')
              ->orWhere('description', 'like', '%' . $query . '%');
        })
        ->where('availability', 1)
        ->select([
            'id', 
            'name', 
            'price', 
            'image_path', 
            'discount', 
            'url',
            'articule',
            'availability'
        ])
        ->limit(10)
        ->get();

        return response()->json($products);
    }

    public function searchSubmit(Request $request)
    {
        $query = $request->input('q');
        $originalQuery = $query;

        if (empty($query)) {
            return redirect()->route('home');
        }

        // Поиск товаров по названию и артикулу
        $getProducts = Product::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('articule', 'like', '%' . $query . '%')
              ->orWhere('description', 'like', '%' . $query . '%');
        })
        ->where('availability', 1)
        ->with('categories')
        ->paginate(12);

        // Генерируем предложения для поиска
        $suggestions = $this->generateSearchSuggestions($query);
        
        // Проверяем, использовали ли мы альтернативный запрос
        $usedAlternative = false;
        if ($getProducts->isEmpty() && !empty($suggestions)) {
            // Пробуем поиск по первому предложению
            $alternativeQuery = $suggestions[0];
            $getProducts = Product::where(function($q) use ($alternativeQuery) {
                $q->where('name', 'like', '%' . $alternativeQuery . '%')
                  ->orWhere('articule', 'like', '%' . $alternativeQuery . '%')
                  ->orWhere('description', 'like', '%' . $alternativeQuery . '%');
            })
            ->where('availability', 1)
            ->with('categories')
            ->paginate(12);
            
            $query = $alternativeQuery;
            $usedAlternative = true;
        }

        // Подготавливаем данные для пагинации
        $paginationData = [
            'current_page' => $getProducts->currentPage(),
            'last_page' => $getProducts->lastPage(),
            'per_page' => $getProducts->perPage(),
            'total' => $getProducts->total(),
            'from' => $getProducts->firstItem(),
            'to' => $getProducts->lastItem(),
        ];

        return view('searchResult', [
            'query' => $query,
            'originalQuery' => $originalQuery,
            'getProducts' => $getProducts,
            'suggestions' => $suggestions,
            'usedAlternative' => $usedAlternative,
            'paginationData' => $paginationData
        ]);
    }

    /**
     * Генерация предложений для поиска
     */
    private function generateSearchSuggestions($query)
    {
        $suggestions = [];
        
        // Разбиваем запрос на слова
        $words = explode(' ', trim($query));
        
        foreach ($words as $word) {
            if (strlen($word) >= 3) {
                // Ищем похожие названия товаров
                $similarProducts = Product::where('name', 'like', '%' . $word . '%')
                    ->where('availability', 1)
                    ->limit(3)
                    ->pluck('name')
                    ->toArray();
                
                $suggestions = array_merge($suggestions, $similarProducts);
            }
        }
        
        // Убираем дубликаты и ограничиваем количество
        $suggestions = array_unique($suggestions);
        $suggestions = array_slice($suggestions, 0, 5);
        
        return $suggestions;
    }

    /**
     * Получение рекомендуемых товаров для слайдера
     * По 1-2 товара из каждой категории
     */
    public function getRecommendedProducts()
    {
        try {
            \Log::info('Начинаем загрузку рекомендуемых товаров');
            
            // Попробуем альтернативный способ - получить товары напрямую
            $recommendedProducts = Product::select(
                'products.id',
                'products.name',
                'products.price',
                'products.discount',
                'products.image_path',
                'products.url',
                'products.articule',
                'products.availability',
                'products.description'
            )
            ->where('products.availability', 1)
            ->whereHas('categories') // Только товары с категориями
            ->inRandomOrder() // Случайный порядок
            ->limit(12)
            ->get();
            
            \Log::info('Получено товаров альтернативным способом: ' . $recommendedProducts->count());
            
            // Если получили товары, добавляем информацию о категориях
            if ($recommendedProducts->count() > 0) {
                $recommendedProducts->each(function($product) {
                    // Получаем первую категорию товара
                    $category = $product->categories()->first();
                    if ($category) {
                        $product->category_name = $category->name;
                        $product->category_url = $category->url;
                    } else {
                        $product->category_name = 'Без категории';
                        $product->category_url = '';
                    }
                });
            }
            
            // Проверяем, что у нас есть товары
            if ($recommendedProducts->count() === 0) {
                \Log::warning('Нет доступных товаров для рекомендаций');
                
                // Возвращаем пустой результат, но не ошибку
                return response()->json([
                    'success' => true,
                    'products' => [],
                    'total' => 0,
                    'message' => 'Нет доступных товаров для рекомендаций'
                ]);
            }
            
            \Log::info('Финальное количество товаров: ' . $recommendedProducts->count());
            
            return response()->json([
                'success' => true,
                'products' => $recommendedProducts,
                'total' => $recommendedProducts->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Ошибка при получении рекомендуемых товаров: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при загрузке рекомендуемых товаров: ' . $e->getMessage(),
                'products' => []
            ], 500);
        }
    }


}
