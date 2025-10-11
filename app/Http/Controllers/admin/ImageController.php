<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\productImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Установить изображение как главное
     */
    public function setAsMainImage(Request $request, $productId)
    {
        try {
            $imageId = $request->input('image_id');
            
            if (!$imageId) {
                return response()->json(['success' => false, 'message' => 'ID изображения не указан']);
            }
            
            // Находим изображение по ID
            $image = productImage::find($imageId);
            
            if (!$image) {
                return response()->json(['success' => false, 'message' => 'Изображение не найдено']);
            }
            
            // Находим товар
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Товар не найден']);
            }
            
            // Сохраняем путь к старому главному изображению
            $oldMainImagePath = $product->image_path;
            
            // Устанавливаем новое изображение как главное
            $product->image_path = $image->src;
            $product->save();
            
            // Если было старое главное изображение и оно отличается от нового
            if ($oldMainImagePath && $oldMainImagePath !== $image->src) {
                // Проверяем, есть ли уже это изображение в галерее
                $existingGalleryImage = productImage::where('product_id', $productId)
                    ->where('src', $oldMainImagePath)
                    ->first();
                
                // Если изображения нет в галерее, добавляем его
                if (!$existingGalleryImage) {
                    productImage::create([
                        'product_id' => $productId,
                        'src' => $oldMainImagePath,
                    ]);
                }
            }
            
            \Log::info('Main image changed', [
                'product_id' => $product->id,
                'new_main_image' => $image->src,
                'old_main_image' => $oldMainImagePath,
                'moved_to_gallery' => $oldMainImagePath && $oldMainImagePath !== $image->src
            ]);
            
            return response()->json(['success' => true, 'message' => 'Главное изображение установлено']);
            
        } catch (\Exception $e) {
            \Log::error('Error setting main image: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Удалить изображение из галереи
     */
    public function deleteImage(Request $request, $productId)
    {
        try {
            $imageId = $request->input('image_id');
            
            \Log::info('Delete image request', [
                'product_id' => $productId,
                'image_id' => $imageId
            ]);
            
            if (!$imageId) {
                return response()->json(['success' => false, 'message' => 'ID изображения не указан']);
            }
            
            // Находим изображение по ID
            $image = productImage::find($imageId);
            
            \Log::info('Image found', [
                'image' => $image ? $image->toArray() : null
            ]);
            
            if (!$image) {
                return response()->json(['success' => false, 'message' => 'Изображение не найдено']);
            }
            
            // Удаляем файл с диска (если не из CDN)
            if ($image->src && !str_starts_with($image->src, 'http')) {
                Storage::disk('public')->delete($image->src);
            }
            
            $image->delete();
            
            \Log::info('Image deleted successfully', [
                'image_id' => $imageId,
                'image_src' => $image->src
            ]);
            
            return response()->json(['success' => true, 'message' => 'Изображение удалено']);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Заменить изображение в галерее
     */
    public function replaceImage(Request $request, $productId)
    {
        try {
            $imageId = $request->input('image_id');
            $newImage = $request->file('new_image');
            
            if (!$imageId || !$newImage) {
                return response()->json(['success' => false, 'message' => 'Недостаточно данных']);
            }
            
            // Находим изображение по ID
            $image = productImage::find($imageId);
            
            if (!$image) {
                return response()->json(['success' => false, 'message' => 'Изображение не найдено']);
            }
            
            // Удаляем старое изображение с диска
            $oldSrc = $image->src;
            if ($oldSrc && !str_starts_with($oldSrc, 'http')) {
                Storage::disk('public')->delete($oldSrc);
            }
            
            // Сохраняем новое изображение локально сначала
            $fileName = generateUniqueImageName($newImage->getClientOriginalName(), 'gallery');
            $newImagePath = $newImage->storeAs('products/gallery', $fileName, 'public');
            
            // Загружаем на CDN
            $localPath = storage_path('app/public/' . $newImagePath);
            $cdnUrl = null;
            
            if (file_exists($localPath)) {
                try {
                    // Используем глобальный хелпер uploadToBunnyCDN
                    $cdnUrl = uploadToBunnyCDN($localPath, $newImagePath);
                    
                    // Удаляем локальный файл после успешной загрузки на CDN
                    unlink($localPath);
                    
                    \Log::info('Replaced image uploaded to CDN', ['cdnUrl' => $cdnUrl]);
                } catch (\Exception $e) {
                    \Log::error('Failed to upload replaced image to CDN: ' . $e->getMessage());
                    // Если загрузка на CDN не удалась, используем локальный путь
                    $cdnUrl = $newImagePath;
                }
            }
            
            // Обновляем запись в базе данных
            $image->src = $cdnUrl ?: $newImagePath;
            $image->save();
            
            // Если это было главное изображение товара, обновляем и его
            $product = Product::find($productId);
            if ($product && $product->image_path === $oldSrc) {
                $product->image_path = $cdnUrl ?: $newImagePath;
                $product->save();
            }
            
            return response()->json(['success' => true, 'message' => 'Изображение успешно заменено']);
            
        } catch (\Exception $e) {
            \Log::error('Error replacing image: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
    }
}