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
        $getProduct = Product::where('url', $url)->firstOrFail();
        if ($getProduct) {
            return view('productPage', [
                'product' => $getProduct,
                'images' => productImage::where('product_id', $getProduct->id)->get(),
            ]);
        }
    }
    public function category_page($url)
    {
        $getCategory = Category::where('url', $url)->firstOrFail();

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
            'products' => $getProducts,
            'paginationData' => $paginationData
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'name', 'price', 'image_path', 'discount', 'url', 'articule']); // image — путь к изображению

        return response()->json($products);
    }

    public function searchSubmit(Request $request)
    {
        $originalQuery = trim($request->input('q'));
        $query = $originalQuery;

        if (!$query || mb_strlen($query) < 2) {
            return view('searchResult', [
                'getProducts' => collect(),
                'query' => $query,
                'paginationData' => [],
                'suggestions' => [],
                'usedAlternative' => false,
                'originalQuery' => $originalQuery
            ]);
        }

        $usedAlternative = false;
        $suggestions = [];

        // Получаем имена товаров
        $allProductNames = Product::select('name')->pluck('name')->unique();

        // Ищем похожее имя по similarity
        $similarProducts = [];
        foreach ($allProductNames as $name) {
            similar_text(mb_strtolower($query), mb_strtolower($name), $percent);
            if ($percent >= 50) {
                $similarProducts[$name] = $percent;
            }
        }

        // Если не найдено результатов, но есть похожее — подставим его
        if (!empty($similarProducts)) {
            arsort($similarProducts);
            $topMatch = array_key_first($similarProducts);

            // Только если прямо не найдено по оригиналу
            $preCheck = Product::where('name', 'LIKE', '%' . $query . '%')
                ->orWhere('description', 'LIKE', '%' . $query . '%')
                ->count();

            if ($preCheck === 0 && mb_strtolower($topMatch) !== mb_strtolower($query)) {
                $query = $topMatch;
                $usedAlternative = true;
            }

            // Предложения всегда показываем
            $suggestions = array_slice(array_keys($similarProducts), 0, 5);
        }

        // Итоговый поиск
        $getProducts = Product::where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('description', 'LIKE', '%' . $query . '%')
            ->paginate(25)
            ->appends(['q' => $originalQuery]);

        $paginationData = [
            'current_page' => $getProducts->currentPage(),
            'last_page' => $getProducts->lastPage(),
            'per_page' => $getProducts->perPage(),
            'total' => $getProducts->total(),
            'path' => $getProducts->path(),
        ];

        return view('searchResult', compact(
            'getProducts',
            'query',
            'originalQuery',
            'paginationData',
            'suggestions',
            'usedAlternative'
        ));
    }


}
