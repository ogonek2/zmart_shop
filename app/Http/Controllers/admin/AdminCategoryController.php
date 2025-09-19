<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Models\Catalog;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.makeCategoryCatalog', [
            'categories' => Category::all(),
            'products' => Product::all(),
            'catalog' => Catalog::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoryName' => 'required',
        ]);

        Category::create([
            'name' => $request->categoryName
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'categoryName' => 'required',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->categoryName
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Получаем категорию с услугами
        $category = Category::with('products')->findOrFail($id);

        // Находим или создаём категорию "Корзина"
        $trashCategory = Category::firstOrCreate(
            ['name' => 'Корзина'],
            ['href' => 'trash']
        );

        // Перепривязка услуг
        foreach ($category->products as $product) {
            $product->categories()->detach($category->id);

            if (!$product->categories->contains($trashCategory->id)) {
                $product->categories()->attach($trashCategory->id);
            }
        }

        // Удаляем категорию
        $category->delete();

        return redirect()->back()->with('success', 'Категория удалена, товары перемещены в "Корзину".');
    }
}
