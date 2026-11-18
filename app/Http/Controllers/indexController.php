<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;

class indexController extends Controller
{
    public function index()
    {
        $productColumns = [
            'id', 'name', 'price', 'discount', 'image_path', 'url',
            'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity',
        ];

        $baseQuery = fn () => Product::query()
            ->where('availability', 'in_stock')
            ->select($productColumns)
            ->with('packages');

        $popularProducts = $this->getPopularProducts(10);
        $saleProducts = $baseQuery()
            ->where('discount', '>', 0)
            ->orderByDesc('discount')
            ->limit(10)
            ->get();

        $wholesaleProducts = $baseQuery()
            ->where('is_wholesale', 1)
            ->whereNotNull('wholesale_price')
            ->where('wholesale_price', '>', 0)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $newProducts = $baseQuery()
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $categoryShowcases = get_all_category()
            ->whereNull('parent_id')
            ->take(3)
            ->map(function ($category) use ($productColumns) {
                $products = Product::query()
                    ->where('availability', 'in_stock')
                    ->whereHas('categories', function ($q) use ($category) {
                        $q->where(function ($inner) use ($category) {
                            $inner->where('categories.id', $category->id)
                                ->orWhere('categories.parent_id', $category->id);
                        });
                    })
                    ->select($productColumns)
                    ->with('packages')
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();

                return [
                    'category' => $category,
                    'products' => $products,
                ];
            })
            ->filter(fn ($row) => $row['products']->count() > 0)
            ->values();

        $popularSearchTerms = $this->getPopularSearchTerms();
        $heroSlides = HeroSlide::active()->ordered()->get();

        return view('home', [
            'popularProducts' => $popularProducts,
            'saleProducts' => $saleProducts,
            'wholesaleProducts' => $wholesaleProducts,
            'newProducts' => $newProducts,
            'categoryShowcases' => $categoryShowcases,
            'popularSearchTerms' => $popularSearchTerms,
            'heroSlides' => $heroSlides,
        ]);
    }

    public function catalog(Request $request)
    {
        $query = Product::select([
            'id', 'name', 'price', 'discount', 'image_path', 'url',
            'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity',
        ])->with('packages');

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
                'products' => $this->mapCatalogProducts($products->items()),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
            ]);
        }

        return view('catalog', [
            'products' => $products,
            'categories' => $rootCategories,
            'subTree' => [],
            'subcategories' => collect(),
            'facets' => [],
            'category' => null,
            'endpoint' => route('catalog'),
            'pageTitle' => 'Каталог товарів',
            'pageSubtitle' => 'Обирайте техніку та господарські товари з фільтрами під ваші задачі',
            'pageDescription' => 'Широкий вибір якісної техніки та господарських товарів. Швидка доставка по Україні.',
        ]);
    }

    private function mapCatalogProducts(array $items): array
    {
        return collect($items)->map(function ($product) {
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
        })->values()->all();
    }

    public function checkout()
    {
        return view('checkout');
    }

    /**
     * Получение популярных товаров на основе недавних заказов
     */
    private function getPopularProducts(int $limit = 10)
    {
        try {
            return Product::where('availability', 'in_stock')
                ->select([
                    'id', 'name', 'price', 'discount', 'image_path', 'url',
                    'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity',
                ])
                ->with('packages')
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error getting popular products: ' . $e->getMessage());

            return Product::where('availability', 'in_stock')
                ->select([
                    'id', 'name', 'price', 'discount', 'image_path', 'url',
                    'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity',
                ])
                ->with('packages')
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        }
    }

    /**
     * Получение случайных названий товаров для популярных поисковых запросов
     */
    private function getPopularSearchTerms()
    {
        try {
            return \App\Models\Product::where('availability', 'in_stock')
                ->select('name')
                ->inRandomOrder()
                ->limit(4)
                ->pluck('name')
                ->toArray();
        } catch (\Exception $e) {
            \Log::error('Error getting popular search terms: ' . $e->getMessage());
            return ['пилосос', 'електрочайник', 'фен', 'конструктор'];
        }
    }

    public function getCities(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $apiKey = env('NOVA_POSHTA_KEY', env('NOVA_POSHTA_API_KEY'));

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        $methodProperties = [
            'FindByString' => $query,
            'Limit' => 30,
        ];

        try {
            $response = Http::timeout(20)->post('https://api.novaposhta.ua/v2.0/json/', [
                'apiKey' => $apiKey,
                'modelName' => 'Address',
                'calledMethod' => 'getCities',
                'methodProperties' => $methodProperties,
            ]);

            $payload = $response->json();
            $data = collect($payload['data'] ?? []);

            $formatted = $data->map(function ($city) {
                $type = $city['SettlementTypeDescription'] ?? 'місто';
                $area = $city['AreaDescription'] ?? '';
                $label = trim($city['Description'] . " ({$type})" . ($area ? ", {$area}" : ''));

                return [
                    'Ref' => $city['Ref'],
                    'Description' => $label,
                    'CityName' => $city['Description'] ?? '',
                ];
            })->values();

            return response()->json($formatted);
        } catch (\Throwable $e) {
            \Log::error('Nova Poshta getCities failed: ' . $e->getMessage());

            return response()->json(['error' => 'Не вдалося завантажити міста'], 500);
        }
    }

    public function getWarehouses(Request $request)
    {
        $cityRef = (string) $request->input('cityRef', '');
        $apiKey = env('NOVA_POSHTA_KEY', env('NOVA_POSHTA_API_KEY'));

        if ($cityRef === '') {
            return response()->json([]);
        }

        try {
            $response = Http::timeout(20)->post('https://api.novaposhta.ua/v2.0/json/', [
                'apiKey' => $apiKey,
                'modelName' => 'Address',
                'calledMethod' => 'getWarehouses',
                'methodProperties' => [
                    'CityRef' => $cityRef,
                    'Limit' => 500,
                ],
            ]);

            $payload = $response->json();
            $data = collect($payload['data'] ?? [])->map(function ($warehouse) {
                return [
                    'Ref' => $warehouse['Ref'] ?? '',
                    'Description' => $warehouse['Description'] ?? '',
                    'Number' => $warehouse['Number'] ?? '',
                ];
            })->filter(fn ($row) => $row['Ref'] !== '' && $row['Description'] !== '')->values();

            return response()->json($data);
        } catch (\Throwable $e) {
            \Log::error('Nova Poshta getWarehouses failed: ' . $e->getMessage());

            return response()->json(['error' => 'Не вдалося завантажити відділення'], 500);
        }
    }

}
