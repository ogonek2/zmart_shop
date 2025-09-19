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
            $publicUrl = FileUploadHelper::uploadFile($image, 'products');
            if (!$publicUrl) {
                return back()->withErrors(['upload' => 'Не удалось загрузить файл.']);
            }
            \App\Models\productImage::create([
                'src' => $publicUrl,
                'product_id' => $product->id,
            ]);
            $saved[] = $publicUrl;
        }

        $product->update([
            'image_path' => $saved[0] ?? null
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
        $product = Product::findOrFail($id);
        $productImages = \App\Models\productImage::where('product_id', $id)->get();

        // Получаем максимальный индекс для характеристик
        $lastIndex = $product->packages()->max('packages.id') ?? 0;

        return view('admin.editProductCard', [
            'product' => $product,
            'images' => $productImages,
            'categories' => Category::all(),
            'lastIndex' => $lastIndex
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
            if (!empty($request->brand)) {
                Product::where('name', 'like', '%' . $request->brand . '%')
                    ->orWhere('description', 'like', '%' . $request->brand . '%')->update(['brand' => $request->brand]);
            }

            $product->update([
                'articule' => $request->articule,
                'name' => $request->title,
                'discount' => $request->discount ?? 0,
                'price' => $request->price,
                'description' => $request->description ?? null,
                'brand' => $request->brand ?? null,
                'availability' => $request->availability,
                'condition_item' => $request->condition_item,
            ]);

            $product->categories()->sync($request->category);

            return redirect()->back()->with('success', 'Товар успешно обновлен!');

        } else if ($request->type_form == "demo_values") {
            $product->update([
                'seo_title' => $request->meta_title ?? null,
                'complectation' => $request->complectation ?? null,
                'seo_keywords' => $request->meta_keywords ?? null,
                'seo_description' => $request->meta_description ?? null
            ]);

            // Удалить старые характеристики (если нужно)
            $product->packages()->delete();

            // Сохранить новые характеристики, игнорируя пустые
            if ($request->has('specs') && is_array($request->specs)) {
                foreach ($request->specs as $spec) {
                    // Пропустить, если и имя, и значение пустые

                    $product->packages()->create([
                        'name' => $spec['name'],
                        'value' => $spec['value'],
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Товар успешно обновлен!');
        } else {
            return redirect()->back()->with('error', 'Возникла ошибка - Неизвестная форма!');
        }
    }

    public function addImage(Request $request, $id)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
            ]);

            $product = Product::findOrFail($id);

            if (!$request->hasFile('image')) {
                return redirect()->back()->withErrors(['upload' => 'Файл не был загружен.']);
            }

            $image = $request->file('image');

            // Проверяем размер файла
            if ($image->getSize() > 5 * 1024 * 1024) { // 5MB
                return redirect()->back()->withErrors(['upload' => 'Файл слишком большой. Максимальный размер: 5MB.']);
            }

            $publicUrl = FileUploadHelper::uploadFile($image, 'products');

            if (!$publicUrl) {
                \Log::error('Ошибка загрузки на CDN для товара ID: ' . $id);
                return redirect()->back()->withErrors(['upload' => 'Не удалось загрузить файл. Проверьте настройки.']);
            }

            productImage::create([
                'src' => $publicUrl,
                'product_id' => $id,
            ]);

            return redirect()->back()->with('success', 'Изображение успешно загружено!');

        } catch (\Exception $e) {
            \Log::error('Ошибка при загрузке изображения: ' . $e->getMessage());
            return redirect()->back()->withErrors(['upload' => 'Произошла ошибка при загрузке изображения: ' . $e->getMessage()]);
        }
    }

    public function destroyImage(Request $request, $id, $image)
    {
        $product = Product::findOrFail($id);
        $getImage = \App\Models\productImage::findOrFail($image);

        // Удаление с BunnyCDN
        FileUploadHelper::deleteFromBunnyCDN($getImage->src);

        // Удаление записи из базы
        $getImage->delete();

        return redirect()->back()->with('success', 'Изображение удалено!');
    }

    public function firstImage(Request $request, $id, $image)
    {
        $product = Product::findOrFail($id);
        $getImage = \App\Models\productImage::findOrFail($image);

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
        $productName = $product->name;
        $deleted = $product->delete();

        return redirect()->back()->with('success', 'Товар "' . $productName . '" успешно удален!');
    }
}
