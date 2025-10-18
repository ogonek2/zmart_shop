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
                    ->where('availability', 'in_stock') // Только товары в наличии
                    ->select([
                        'id', 'name', 'price', 'discount', 'image_path', 'url', 
                        'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity'
                    ])
                    ->inRandomOrder() // Случайный порядок
                    ->limit(8) // Максимум 8 товаров
                    ->get();
                    
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
    public function category_page(Request $request, $url)
    {
        $getCategory = Category::where('url', $url)->firstOrFail();

        // Получаем подкатегории текущей категории
        $subcategories = $getCategory->childCategories()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Строим запрос для товаров
        $query = Product::select([
            'id', 'name', 'price', 'discount', 'image_path', 'url', 
            'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity'
        ]);

        // Определяем, какие категории использовать для поиска товаров
        $categoryProducts = $getCategory->products();
        
        if ($categoryProducts->count() === 0 && $subcategories->count() > 0) {
            // Собираем ID всех подкатегорий
            $subcategoryIds = $subcategories->pluck('id')->toArray();
            $subcategoryIds[] = $getCategory->id; // добавляем текущую категорию
            
            // Получаем товары из всех подкатегорий
            $query->whereHas('categories', function($q) use ($subcategoryIds) {
                $q->whereIn('categories.id', $subcategoryIds);
            });
        } else {
            // Обычная логика - товары только из текущей категории
            $query->whereHas('categories', function($q) use ($getCategory) {
                $q->where('categories.id', $getCategory->id);
            });
        }

        // Apply filters
        if ($request->filled('price_min')) {
            $query->whereRaw('CAST(price AS DECIMAL(10,2)) >= ?', [(float)$request->price_min]);
        }

        if ($request->filled('price_max')) {
            $query->whereRaw('CAST(price AS DECIMAL(10,2)) <= ?', [(float)$request->price_max]);
        }

        if ($request->has('availability')) {
            $query->where('availability', 'in_stock');
        }

        if ($request->has('discount')) {
            $query->where('discount', '>', 0);
        }

        if ($request->has('wholesale')) {
            $query->where('is_wholesale', 1);
        }

        // Apply sorting
        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $getProducts = $query->paginate(45);
        
        // Append query parameters to pagination links
        $getProducts->appends($request->query());

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'products' => $getProducts->items(),
                'pagination' => [
                    'current_page' => $getProducts->currentPage(),
                    'last_page' => $getProducts->lastPage(),
                    'per_page' => $getProducts->perPage(),
                    'total' => $getProducts->total(),
                    'from' => $getProducts->firstItem(),
                    'to' => $getProducts->lastItem(),
                ]
            ]);
        }

        return view('categoryPage', [
            'category' => $getCategory,
            'subcategories' => $subcategories,
            'products' => $getProducts,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        // Очищаем запрос от лишних символов
        $cleanQuery = trim($query);
        
        $products = Product::where(function($q) use ($cleanQuery) {
            $q->where('name', 'like', '%' . $cleanQuery . '%')
              ->orWhere('articule', 'like', '%' . $cleanQuery . '%')
              ->orWhere('description', 'like', '%' . $cleanQuery . '%');
        })
        ->where('availability', 'in_stock')
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
        ->orderByRaw("CASE 
            WHEN name LIKE ? THEN 1 
            WHEN name LIKE ? THEN 2 
            WHEN articule LIKE ? THEN 3 
            ELSE 4 
        END", [
            $cleanQuery . '%',  // Точное совпадение в начале названия
            '%' . $cleanQuery . '%',  // Частичное совпадение в названии
            '%' . $cleanQuery . '%'   // Совпадение в артикуле
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

        // Очищаем запрос от лишних символов
        $cleanQuery = trim($query);
        
        // Поиск товаров по названию и артикулу
        $getProducts = Product::select([
                'id', 'name', 'price', 'discount', 'image_path', 'url', 
                'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity'
            ])
            ->where(function($q) use ($cleanQuery) {
                $q->where('name', 'like', '%' . $cleanQuery . '%')
                  ->orWhere('articule', 'like', '%' . $cleanQuery . '%')
                  ->orWhere('description', 'like', '%' . $cleanQuery . '%');
            })
            ->where('availability', 'in_stock')
            ->orderByRaw("CASE 
                WHEN name LIKE ? THEN 1 
                WHEN name LIKE ? THEN 2 
                WHEN articule LIKE ? THEN 3 
                ELSE 4 
            END", [
                $cleanQuery . '%',  // Точное совпадение в начале названия
                '%' . $cleanQuery . '%',  // Частичное совпадение в названии
                '%' . $cleanQuery . '%'   // Совпадение в артикуле
            ])
            ->with('categories')
            ->paginate(12);

        // Генерируем предложения для поиска
        $suggestions = $this->generateSearchSuggestions($query);
        
        // Проверяем, использовали ли мы альтернативный запрос
        $usedAlternative = false;
        if ($getProducts->isEmpty() && !empty($suggestions)) {
            // Пробуем поиск по первому предложению
            $alternativeQuery = $suggestions[0];
            $getProducts = Product::select([
                    'id', 'name', 'price', 'discount', 'image_path', 'url', 
                    'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity'
                ])
                ->where(function($q) use ($alternativeQuery) {
                    $q->where('name', 'like', '%' . $alternativeQuery . '%')
                      ->orWhere('articule', 'like', '%' . $alternativeQuery . '%')
                      ->orWhere('description', 'like', '%' . $alternativeQuery . '%');
                })
                ->where('availability', 'in_stock')
                ->orderByRaw("CASE 
                    WHEN name LIKE ? THEN 1 
                    WHEN name LIKE ? THEN 2 
                    WHEN articule LIKE ? THEN 3 
                    ELSE 4 
                END", [
                    $alternativeQuery . '%',  // Точное совпадение в начале названия
                    '%' . $alternativeQuery . '%',  // Частичное совпадение в названии
                    '%' . $alternativeQuery . '%'   // Совпадение в артикуле
                ])
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
                    ->where('availability', 'in_stock')
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
                'products.description',
                'products.is_wholesale',
                'products.wholesale_price',
                'products.wholesale_min_quantity'
            )
            ->where('products.availability', 'in_stock')
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
