<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// Helper
use App\Helpers\FileUploadHelper;

use App\Models\Category;
use App\Models\Product;
use App\Models\productImage;

class AdminProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.makeProducts', [
            'categories' => Category::all()
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
            'images' => 'required|array',
            'images.*' => 'image|max:5120',
            'title' => 'required|string',
            'price' => 'integer|required',
            'articule' => 'required|string',
        ]);

        $product = Product::create([
            'name' => $request->title,
            'articule' => $request->articule,
            'description' => $request->description ?? null,
            'price' => $request->price,
            'image_path' => null, // главное изображение
        ]);

        if ($request->has('category')) {
            $product->categories()->syncWithoutDetaching($request->category);
        }

        $saved = [];

        foreach ($request->file('images', []) as $image) {
            $publicUrl = FileUploadHelper::uploadToBunnyCDN($image, 'products');
            if (!$publicUrl) {
                return back()->withErrors(['upload' => 'Не удалось загрузить файл на CDN.']);
            }
            productImage::create([
                'src' => $publicUrl,
                'product_id' => $product->id,
            ]);
            $saved[] = $publicUrl;
        }

        $product->update([
            'image_path' => $saved[0] ?? null
        ]);

        // Если хочешь сохранить все картинки — сохрани как JSON или через другую таблицу
        // $product->update(['image_paths' => json_encode($saved)]);

        return response()->json(['message' => 'Загружено']);
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

        $product = Product::findOrFail($id);

        if ($request->type_form === "demo_modal") {
            $request->validate([
                'title' => 'required|string',
                'price' => 'integer|required',
                'articule' => 'required|string',
            ]);

            $product->update([
                'name' => $request->title,
                'articule' => $request->articule,
                'price' => $request->price,
            ]);
        } else if ($request->type_form === "demo_full") {
            return "Форма в обработке";
        } else {
            return redirect()->back()->with('error', 'Возникла ошибка - Неизвестная форма!');
        }

        return redirect()->back()->with('success', 'Товар успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->back()->with('success', 'Товар успешно удален!');
    }
}
