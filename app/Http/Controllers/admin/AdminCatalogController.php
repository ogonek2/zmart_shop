<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Catalog;
use App\Models\Product;

class AdminCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'catalogNameSetting' => 'required|string',
            'catalogSelectProductsSetting' => 'required|array',
            'catalogSelectProductsSetting.*' => 'exists:products,id', // безопасность
        ]);

        $makeCatalog = Catalog::create([
            'name' => $request->catalogNameSetting
        ]);

        // Привязка всех выбранных продуктов к каталогу
        $makeCatalog->products()->attach($request->catalogSelectProductsSetting);

        return redirect()->back()->with('success', 'Каталог создан и товары привязаны.');
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
            'catalogNameSetting' => 'required|string',
            'catalogSelectProductsSetting' => 'required|array',
            'catalogSelectProductsSetting.*' => 'exists:products,id', // безопасность
        ]);
        $updateCatalog = Catalog::findOrFail($id);
        $updateCatalog->update([
            'name' => $request->catalogNameSetting
        ]);

        $updateCatalog->products()->sync($request->catalogSelectProductsSetting);

        return redirect()->back()->with('success', 'Каталог обновлен.');
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
        $catalog = Catalog::with('products')->findOrFail($id);

        // Удаление услуг
        foreach ($catalog->products as $product) {
            $product->catalog()->detach($catalog->id);
        }
        // Удаляем категорию
        $catalog->delete();

        return redirect()->back()->with('success', 'Каталог удален!');
    }
}
