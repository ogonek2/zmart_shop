<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Получить дерево категорий
     */
    public function tree()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['childCategories' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->withCount('products')
            ->get();

        return response()->json($this->buildTree($categories));
    }

    /**
     * Получить данные категории с подкатегориями и товарами
     */
    public function show($id)
    {
        $category = Category::with(['products', 'childCategories'])
            ->withCount(['products', 'childCategories'])
            ->findOrFail($id);

        $subcategories = $category->childCategories()
            ->where('is_active', true)
            ->withCount('products')
            ->get();

        $products = $category->products()
            ->with(['categories', 'catalogs'])
            ->get();

        return response()->json([
            'category' => $category,
            'subcategories' => $subcategories,
            'products' => $products
        ]);
    }

    /**
     * Создать новую категорию
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'category' => $category
        ], 201);
    }

    /**
     * Обновить категорию
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    /**
     * Удалить категорию
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Перемещаем дочерние категории на уровень выше
        Category::where('parent_id', $id)
            ->update(['parent_id' => $category->parent_id]);

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Категория удалена'
        ]);
    }

    /**
     * Отвязать товар от категории
     */
    public function detachProduct($categoryId, $productId)
    {
        $category = Category::findOrFail($categoryId);
        $category->products()->detach($productId);

        return response()->json([
            'success' => true,
            'message' => 'Товар отвязан от категории'
        ]);
    }

    /**
     * Переместить категорию
     */
    public function move(Request $request, $id)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'position' => 'in:inside,after'
        ]);

        $category = Category::findOrFail($id);

        if ($validated['position'] === 'inside') {
            $category->update(['parent_id' => $validated['parent_id']]);
        } elseif ($validated['position'] === 'after') {
            $targetCategory = Category::find($validated['parent_id']);
            if ($targetCategory) {
                $category->update(['parent_id' => $targetCategory->parent_id]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Категория перемещена'
        ]);
    }

    /**
     * Построить дерево категорий рекурсивно
     */
    private function buildTree($categories)
    {
        return $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'products_count' => $category->products_count ?? 0,
                'children' => $category->childCategories ? $this->buildTree($category->childCategories) : []
            ];
        });
    }
}
