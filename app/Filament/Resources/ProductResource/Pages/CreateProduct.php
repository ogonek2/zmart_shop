<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Category;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    
    protected $galleryImagesToSave = [];
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Загружаем данные из шаблона категории, если они пустые
        if (empty($data['characteristics']) || empty($data['modifications']) || empty($data['additional_fields'])) {
            $templateData = $this->loadTemplateData($data['categories'] ?? []);
            
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
        
        // Обрабатываем основное изображение
        if (!empty($data['image_path'])) {
            $data['image_path'] = $this->uploadImageToCDN($data['image_path'], 'products');
        }
        
        // Сохраняем галерею изображений во временную переменную
        if (!empty($data['gallery_images'])) {
            $this->galleryImagesToSave = $data['gallery_images'];
        }
        unset($data['gallery_images']); // Удаляем из данных для сохранения
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        // Сохраняем галерею изображений
        $this->saveGalleryImages();
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
        
        if (empty($galleryImages) || !is_array($galleryImages)) {
            \Log::info('No gallery images to save');
            return;
        }
        
        \Log::info('Saving gallery images', ['count' => count($galleryImages)]);
        
        foreach ($galleryImages as $imageData) {
            // Проверяем, есть ли ключ 'src' в массиве
            $imageSrc = is_array($imageData) && isset($imageData['src']) ? $imageData['src'] : $imageData;
            
            if (!empty($imageSrc)) {
                // Загружаем на CDN
                $uploadedSrc = $this->uploadImageToCDN($imageSrc, 'products/gallery');
                
                \App\Models\productImage::create([
                    'product_id' => $this->record->id,
                    'src' => $uploadedSrc,
                ]);
                
                \Log::info('Gallery image saved', ['src' => $uploadedSrc]);
            }
        }
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
}
