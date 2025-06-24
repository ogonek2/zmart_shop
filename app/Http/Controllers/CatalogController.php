<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;

class CatalogController extends Controller
{
    public function product_page($url)
    {
        $getProduct = Product::where('url', $url)->firstOrFail();
        if ($getProduct) {
            return view('productPage', [
                'product' => $getProduct
            ]);
        }
    }
    public function category_page($url)
    {
        $getCategory = Category::where('url', $url)->firstOrFail();

        // Получаем продукты, связанные с категорией
        $getProducts = $getCategory->products()->paginate(25); // укажи нужное количество

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
}
