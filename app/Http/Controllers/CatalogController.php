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
        $getProduct = Product::with(['categories', 'packages', 'images'])
            ->where('url', $url)
            ->firstOrFail();

        $images = $getProduct->images;
        $characteristics = $getProduct->getTemplateCharacteristics();
        $modifications = $getProduct->getTemplateModifications();
        $additionalFields = $getProduct->getTemplateAdditionalFields();

        $recommendedProducts = collect();
        $categoryIds = $getProduct->categories->pluck('id')->all();
        if ($categoryIds) {
            $recommendedProducts = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
                ->where('id', '!=', $getProduct->id)
                ->where(function ($q) {
                    $q->where('availability', 'in_stock')
                        ->orWhereNull('availability')
                        ->orWhere('availability', 1)
                        ->orWhere('availability', '1');
                })
                ->with('packages')
                ->select([
                    'id', 'name', 'price', 'discount', 'image_path', 'url',
                    'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity',
                ])
                ->inRandomOrder()
                ->limit(8)
                ->get();
        }

        return view('productPage', [
            'product' => $getProduct,
            'images' => $images,
            'characteristics' => $characteristics,
            'modifications' => $modifications,
            'additionalFields' => $additionalFields,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }

    public function wishlist()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with(['childCategories' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->with(['childCategories' => function ($q2) {
                        $q2->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                    }]);
            }])
            ->get()
            ->map(fn (Category $cat) => map_category_tree_node($cat))
            ->values();

        return view('wishlist', [
            'categories' => $categories,
        ]);
    }
    public function category_page(Request $request, $url)
    {
        $getCategory = Category::where('url', $url)->firstOrFail();
        $getCategory->load([
            'childCategories' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->with(['childCategories' => function ($q2) {
                        $q2->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                    }]);
            },
        ]);

        $branchIds = get_category_descendant_ids($getCategory);
        $selectedSubcats = collect($request->input('subcats', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0 && in_array($id, $branchIds, true))
            ->values()
            ->all();

        $scopeIds = count($selectedSubcats) ? $selectedSubcats : $branchIds;

        $query = Product::select([
            'id', 'name', 'price', 'discount', 'image_path', 'url',
            'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity',
            'characteristics', 'modifications', 'additional_fields',
        ])->with('packages')
            ->whereHas('categories', function ($q) use ($scopeIds) {
                $q->whereIn('categories.id', $scopeIds);
            });

        if ($request->filled('price_min')) {
            $query->whereRaw('CAST(price AS DECIMAL(10,2)) >= ?', [(float) $request->price_min]);
        }

        if ($request->filled('price_max')) {
            $query->whereRaw('CAST(price AS DECIMAL(10,2)) <= ?', [(float) $request->price_max]);
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

        $attrs = $request->input('attrs', []);
        if (is_array($attrs)) {
            foreach ($attrs as $key => $values) {
                $key = trim((string) $key);
                $values = array_values(array_filter(array_map('strval', (array) $values), fn ($v) => $v !== ''));
                if ($key === '' || !$values) {
                    continue;
                }
                $query->where(function ($outer) use ($key, $values) {
                    foreach ($values as $value) {
                        $outer->orWhere(function ($inner) use ($key, $value) {
                            $inner->where('characteristics->' . $key, $value)
                                ->orWhere('modifications->' . $key, $value)
                                ->orWhere('additional_fields->' . $key, $value);
                        });
                    }
                });
            }
        }

        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('CAST(price AS DECIMAL(10,2)) asc');
                break;
            case 'price_desc':
                $query->orderByRaw('CAST(price AS DECIMAL(10,2)) desc');
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

        $mapProduct = function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'discount' => $product->discount,
                'image_path' => $product->image_path,
                'url' => $product->url,
                'articule' => $product->articule,
                'availability' => $product->availability,
                'is_wholesale' => (bool) $product->is_wholesale,
                'wholesale_price' => $product->wholesale_price,
                'wholesale_min_quantity' => $product->wholesale_min_quantity,
                'packages' => $product->relationLoaded('packages')
                    ? $product->packages->map(fn ($p) => ['value' => $p->value])->values()
                    : [],
            ];
        };

        if ($request->boolean('preview')) {
            $previewProducts = (clone $query)->limit(10)->get();

            return response()->json([
                'success' => true,
                'products' => $previewProducts->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'discount' => $product->discount,
                        'image_path' => $product->image_path,
                        'url' => $product->url,
                    ];
                })->values(),
            ]);
        }

        $facetProducts = (clone $query)
            ->select(['id', 'characteristics', 'modifications', 'additional_fields'])
            ->limit(400)
            ->get();
        $facets = build_catalog_facets($facetProducts);

        $products = $query->paginate(24)->appends($request->query());

        $rootCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with(['childCategories' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->with(['childCategories' => function ($q2) {
                        $q2->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                    }]);
            }])
            ->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'products' => collect($products->items())->map($mapProduct)->values(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'facets' => $facets,
            ]);
        }

        return view('catalog', [
            'products' => $products,
            'categories' => $rootCategories,
            'subTree' => map_category_tree_node($getCategory)['children'] ?? [],
            'subcategories' => $getCategory->childCategories,
            'facets' => $facets,
            'category' => $getCategory,
            'endpoint' => route('catalog_category_page', $getCategory->url),
            'pageTitle' => $getCategory->name,
            'pageSubtitle' => $getCategory->description ?: 'Товари категорії в наявності',
            'pageDescription' => $getCategory->description ?? ('Купити ' . $getCategory->name . ' в інтернет-магазині ZMART'),
        ]);
    }

    public function search(Request $request)
    {
        $ids = collect(explode(',', (string) $request->input('ids', '')))
            ->map(fn ($id) => (int) trim($id))
            ->filter(fn ($id) => $id > 0)
            ->values()
            ->all();

        if ($ids) {
            $products = Product::whereIn('id', $ids)
                ->select(['id', 'name', 'price', 'image_path', 'discount', 'url', 'articule', 'availability'])
                ->with(['categories' => function ($q) {
                    $q->select('categories.id', 'categories.name', 'categories.url', 'categories.parent_id');
                }])
                ->get()
                ->sortBy(fn ($product) => array_search($product->id, $ids, true))
                ->values()
                ->map(function (Product $product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image_path' => $product->image_path,
                        'discount' => $product->discount,
                        'url' => $product->url,
                        'articule' => $product->articule,
                        'availability' => $product->availability,
                        'categories' => $product->categories->map(fn ($cat) => [
                            'id' => (int) $cat->id,
                            'name' => $cat->name,
                            'url' => $cat->url,
                            'parent_id' => $cat->parent_id ? (int) $cat->parent_id : null,
                        ])->values()->all(),
                        'category_ids' => $product->categories->pluck('id')->map(fn ($id) => (int) $id)->values()->all(),
                    ];
                });

            return response()->json($products);
        }

        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $cleanQuery = trim($query);

        $products = Product::where(function ($q) use ($cleanQuery) {
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
            'availability',
        ])
        ->orderByRaw("CASE 
            WHEN name LIKE ? THEN 1 
            WHEN name LIKE ? THEN 2 
            WHEN articule LIKE ? THEN 3 
            ELSE 4 
        END", [
            $cleanQuery . '%',
            '%' . $cleanQuery . '%',
            '%' . $cleanQuery . '%',
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
