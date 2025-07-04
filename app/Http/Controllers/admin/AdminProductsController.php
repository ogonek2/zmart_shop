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
        $product = Product::findOrFail($id);
        $productImages = productImage::where('product_id', $id)->get();

        return view('admin.editProductCard', [
            'product' => $product,
            'images' => $productImages,
            'categories' => Category::all()
        ]);
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

        if ($request->type_form == "demo_modal") {
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

            return redirect()->back()->with('success', 'Товар успешно обновлен!');

        } else if ($request->type_form == "demo_full") {
            $request->validate([
                'title' => 'required|string',
                'price' => 'integer|required',
                'articule' => 'required|string'
            ]);
            $product->update([
                'articule' => $request->articule,
                'name' => $request->title,
                'discount' => $request->discount ?? 0,
                'price' => $request->price,
                'description' => $request->description ?? null
            ]);

            $product->categories()->sync($request->category);

            return redirect()->back()->with('success', 'Товар успешно обновлен!');

        } else if ($request->type_form == "demo_values") {
            $product->update([
                'seo_title' => $request->meta_title ?? null,
                'seo_keywords' => $request->meta_keqwords ?? null,
                'seo_description' => $request->meta_description ?? null
            ]);

            // Удалить старые характеристики (если нужно)
            $product->package()->delete();

            // Сохранить новые характеристики, игнорируя пустые
            foreach ($request->specs as $spec) {
                // Пропустить, если и имя, и значение пустые (или одно из них)
                if (empty(trim($spec['name'])) || empty(trim($spec['value']))) {
                    continue;
                }

                $product->package()->create([
                    'name' => $spec['name'],
                    'value' => $spec['value'],
                ]);
            }

            return redirect()->back()->with('success', 'Товар успешно обновлен!');
        } else {
            return redirect()->back()->with('error', 'Возникла ошибка - Неизвестная форма!');
        }
    }

    public function addImage(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $image = $request->file('image');
        $publicUrl = FileUploadHelper::uploadToBunnyCDN($image, 'products');
        if (!$publicUrl) {
            return back()->withErrors(['upload' => 'Не удалось загрузить файл на CDN.']);
        }
        productImage::create([
            'src' => $publicUrl,
            'product_id' => $id,
        ]);

        return redirect()->back()->with('success', 'Изображение загружено!');
    }

    public function destroyImage(Request $request, $id, $image)
    {
        $product = Product::findOrFail($id);
        $getImage = productImage::findOrFail($image);

        // Удаление с BunnyCDN
        FileUploadHelper::deleteFromBunnyCDN($getImage->src);

        // Удаление записи из базы
        $getImage->delete();

        return redirect()->back()->with('success', 'Изображение удалено!');
    }

    public function firstImage(Request $request, $id, $image)
    {
        $product = Product::findOrFail($id);
        $getImage = productImage::findOrFail($image);

        $product->update([
            'image_path' => $getImage->src
        ]);

        return redirect()->back()->with('success', 'Главное изображение изменено!');
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
