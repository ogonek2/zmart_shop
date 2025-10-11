<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Category;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    
    protected $galleryImagesToSave = [];

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Получаем товар с категориями
        $product = $this->record;
        $categories = $product->categories()->with('template')->get();
        
        // Загружаем данные из шаблона категории, если они пустые
        if (empty($data['characteristics']) || empty($data['modifications']) || empty($data['additional_fields'])) {
            $templateData = $this->loadTemplateDataFromCategories($categories);
            
            if (empty($data['characteristics'])) {
                $data['characteristics'] = $templateData['characteristics'];
            }
            if (empty($data['modifications'])) {
                $data['modifications'] = $templateData['modifications'];
            }
            if (empty($data['additional_fields'])) {
                $data['additional_fields'] = $templateData['additional_fields'];
            }
        }
        
        // Галерея изображений теперь управляется отдельно через ManageGallery
        // Не нужно загружать gallery_images в форму
        
        // Для основного изображения оставляем как есть (CDN URL или локальный путь)
        // FileUpload может работать с URL
        
        return $data;
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Обрабатываем основное изображение только если оно было изменено
        if (!empty($data['image_path'])) {
            // Проверяем, не является ли это уже CDN URL
            if (!str_contains($data['image_path'], env('BUNNY_CDN_URL'))) {
                // Это новое изображение, загружаем на CDN
                $data['image_path'] = $this->uploadImageToCDN($data['image_path'], 'products');
            }
            // Если это CDN URL, оставляем как есть
        } else {
            // Если поле пустое, сохраняем старое значение
            $data['image_path'] = $this->record->image_path;
        }
        
        // Галерея изображений теперь управляется отдельно через ManageGallery
        // Не обрабатываем gallery_images, так как их нет в форме
        
        return $data;
    }
    
    protected function afterSave(): void
    {
        // Галерея изображений теперь управляется отдельно через ManageGallery
        // Не нужно вызывать saveGalleryImages()
    }
    
    protected function uploadImageToCDN($imagePath, $directory = 'products')
    {
        if (empty($imagePath)) {
            return $imagePath;
        }
        
        // Проверяем, не загружено ли уже на CDN
        if (str_contains($imagePath, env('BUNNY_CDN_URL'))) {
            return $imagePath;
        }
        
        $localPath = storage_path('app/public/' . $imagePath);
        
        \Log::info('Trying to upload image to CDN', [
            'imagePath' => $imagePath,
            'localPath' => $localPath,
            'exists' => file_exists($localPath)
        ]);
        
        if (file_exists($localPath)) {
            try {
                // Используем глобальный хелпер uploadToBunnyCDN
                $cdnUrl = uploadToBunnyCDN($localPath, $imagePath);
                \Log::info('Image uploaded to CDN successfully', ['cdnUrl' => $cdnUrl]);
                
                // Удаляем локальный файл после успешной загрузки на CDN
                unlink($localPath);
                \Log::info('Local file deleted after CDN upload', ['localPath' => $localPath]);
                
                return $cdnUrl;
            } catch (\Exception $e) {
                \Log::error('Failed to upload image to CDN: ' . $e->getMessage(), [
                    'localPath' => $localPath,
                    'imagePath' => $imagePath
                ]);
                // Возвращаем оригинальный путь если загрузка не удалась
                return $imagePath;
            }
        }
        
        \Log::warning('Image file not found', ['localPath' => $localPath]);
        return $imagePath;
    }
    
    protected function saveGalleryImages()
    {
        $galleryImages = $this->galleryImagesToSave;
        
        // Если галерея не была изменена (пустой массив), не трогаем существующие изображения
        if (!is_array($galleryImages)) {
            \Log::info('Gallery images not changed, skipping update');
            return;
        }
        
        \Log::info('Saving gallery images', ['count' => count($galleryImages)]);
        
        // Получаем существующие изображения
        $existingImages = $this->record->images()->pluck('src')->toArray();
        $newImagesSrc = array_column($galleryImages, 'src');
        
        // Удаляем только те изображения, которых нет в новом списке
        $imagesToDelete = array_diff($existingImages, $newImagesSrc);
        if (!empty($imagesToDelete)) {
            $this->record->images()->whereIn('src', $imagesToDelete)->delete();
            \Log::info('Deleted images', ['count' => count($imagesToDelete)]);
        }
        
        // Добавляем только новые изображения (которых еще нет в базе)
        if (!empty($galleryImages)) {
            foreach ($galleryImages as $imageData) {
                if (!empty($imageData['src'])) {
                    $imageSrc = $imageData['src'];
                    
                    // Проверяем, существует ли уже это изображение
                    $exists = $this->record->images()->where('src', $imageSrc)->exists();
                    
                    if (!$exists) {
                        // Загружаем на CDN только если это новое изображение (не CDN URL)
                        if (!str_contains($imageSrc, env('BUNNY_CDN_URL'))) {
                            $imageSrc = $this->uploadImageToCDN($imageSrc, 'products/gallery');
                        }
                        
                        \App\Models\productImage::create([
                            'product_id' => $this->record->id,
                            'src' => $imageSrc,
                        ]);
                        
                        \Log::info('Added new image', ['src' => $imageSrc]);
                    }
                }
            }
        }
    }
    
    protected function loadTemplateDataFromCategories($categories)
    {
        if ($categories->isEmpty()) {
            return [
                'characteristics' => [],
                'modifications' => [],
                'additional_fields' => [],
            ];
        }
        
        // Получаем первую категорию с шаблоном
        $category = $categories->first();
        
        if (!$category || !$category->template) {
            return [
                'characteristics' => [],
                'modifications' => [],
                'additional_fields' => [],
            ];
        }
        
        return [
            'characteristics' => $category->template->characteristics ?? [],
            'modifications' => $category->template->modifications ?? [],
            'additional_fields' => $category->template->additional_fields ?? [],
        ];
    }
    
    protected function loadTemplateData($categories)
    {
        if (empty($categories)) {
            return [
                'characteristics' => [],
                'modifications' => [],
                'additional_fields' => [],
            ];
        }
        
        // Получаем первую категорию и её шаблон
        $category = Category::with('template')->find($categories[0]);
        
        if (!$category || !$category->template) {
            return [
                'characteristics' => [],
                'modifications' => [],
                'additional_fields' => [],
            ];
        }
        
        return [
            'characteristics' => $category->template->characteristics ?? [],
            'modifications' => $category->template->modifications ?? [],
            'additional_fields' => $category->template->additional_fields ?? [],
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            // Добавляем виджет с JavaScript для управления изображениями
        ];
    }
    
    protected function getFooterActions(): array
    {
        return [
            Actions\Action::make('manage_gallery')
                ->label('Управление галереей')
                ->icon('heroicon-o-photograph')
                ->url(fn () => route('filament.resources.products.manage-gallery', $this->record))
                ->color('success')
                ->openUrlInNewTab(),
        ];
    }
    
    public function setAsMainImage()
    {
        $imageId = request()->input('image_id');
        $image = \App\Models\productImage::find($imageId);
        
        if ($image && $image->product_id === $this->record->id) {
            $this->record->image_path = $image->src;
            $this->record->save();
            
            return response()->json(['success' => true, 'message' => 'Главное изображение установлено']);
        }
        
        return response()->json(['success' => false, 'message' => 'Изображение не найдено']);
    }
    
    public function deleteGalleryImage()
    {
        $imageId = request()->input('image_id');
        $image = \App\Models\productImage::find($imageId);
        
        if ($image && $image->product_id === $this->record->id) {
            // Удаляем файл с диска (если не из CDN)
            if ($image->src && !str_starts_with($image->src, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($image->src);
            }
            
            $image->delete();
            
            return response()->json(['success' => true, 'message' => 'Изображение удалено']);
        }
        
        return response()->json(['success' => false, 'message' => 'Изображение не найдено']);
    }
}
