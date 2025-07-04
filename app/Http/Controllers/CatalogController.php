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
            ->get(['id', 'name', 'price', 'image_path', 'discount', 'url']); // image — путь к изображению

        return response()->json($products);
    }
}
