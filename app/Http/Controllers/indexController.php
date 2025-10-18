<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Product;

class indexController extends Controller
{
    public function index()
    {
        // Получаем популярные товары на основе недавних заказов
        $popularProducts = $this->getPopularProducts();
        
        // Получаем случайные названия товаров для популярных поисковых запросов
        $popularSearchTerms = $this->getPopularSearchTerms();

        return view('home', [
            'popularProducts' => $popularProducts,
            'popularSearchTerms' => $popularSearchTerms
        ]);
    }

    public function catalog(Request $request)
    {
        $query = Product::select([
            'id', 'name', 'price', 'discount', 'image_path', 'url', 
            'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity'
        ]);

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
        
        $categories = get_all_category()->whereNull('parent_id');

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
        
        // Получаем популярные товары на основе недавних заказов
        $popularProducts = $this->getPopularProducts();

        return view('welcome', [
            'getProducts' => $getProducts,
            'categories' => $categories,
            'popularProducts' => $popularProducts
        ]);
    }

    public function checkout()
    {
        return view('checkout');
    }

    /**
     * Получение популярных товаров на основе недавних заказов
     */
    private function getPopularProducts()
    {
        try {
            // Пока что возвращаем случайные товары, так как данные корзин зашифрованы
            // В будущем можно будет добавить анализ заказов, когда решим проблему с шифрованием
            return \App\Models\Product::where('availability', 'in_stock')
                ->select([
                    'id', 'name', 'price', 'discount', 'image_path', 'url', 
                    'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity'
                ])
                ->inRandomOrder()
                ->limit(8)
                ->get();

        } catch (\Exception $e) {
            \Log::error('Error getting popular products: ' . $e->getMessage());
            
            // В случае ошибки возвращаем случайные товары
            return \App\Models\Product::where('availability', 'in_stock')
                ->select([
                    'id', 'name', 'price', 'discount', 'image_path', 'url', 
                    'articule', 'availability', 'is_wholesale', 'wholesale_price', 'wholesale_min_quantity'
                ])
                ->inRandomOrder()
                ->limit(8)
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

    public function getCities()
    {
        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            'apiKey' => env('NOVA_POSHTA_API_KEY'),
            'modelName' => 'Address',
            'calledMethod' => 'getCities',
        ]);

        $data = $response['data'];

        $formatted = collect($data)->map(function ($city) {
            $type = $city['SettlementTypeDescription'] ?? 'місто';
            $label = $city['Description'] . " ({$type})";
            return [
                'Ref' => $city['Ref'],
                'Description' => $label,
            ];
        });

        return response()->json($formatted);
    }
    public function getWarehouses(Request $request)
    {
        $response = Http::post('https://api.novaposhta.ua/v2.0/json/', [
            'apiKey' => env('NOVA_POSHTA_API_KEY'),
            'modelName' => 'Address',
            'calledMethod' => 'getWarehouses',
            'methodProperties' => [
                'CityRef' => $request->cityRef
            ]
        ]);

        return $response['data'];
    }

}
