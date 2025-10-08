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
        
        $destinationPath = $directory . '/' . basename($imagePath);
        $localPath = storage_path('app/public/' . $imagePath);
        
        \Log::info('Trying to upload image to CDN', [
            'imagePath' => $imagePath,
            'localPath' => $localPath,
            'exists' => file_exists($localPath)
        ]);
        
        if (file_exists($localPath)) {
            try {
                $cdnUrl = uploadToBunnyCDN($localPath, $destinationPath);
                \Log::info('Image uploaded to CDN successfully', ['cdnUrl' => $cdnUrl]);
                return $cdnUrl;
            } catch (\Exception $e) {
                \Log::error('Failed to upload image to CDN: ' . $e->getMessage(), [
                    'localPath' => $localPath,
                    'destinationPath' => $destinationPath
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
        
        \Log::info('Saving gallery images', ['count' => count($galleryImages)]);
        
        if (!empty($galleryImages)) {
            foreach ($galleryImages as $imageData) {
                if (!empty($imageData['src'])) {
                    $imageSrc = $this->uploadImageToCDN($imageData['src'], 'products/gallery');
                    
                    \App\Models\productImage::create([
                        'product_id' => $this->record->id,
                        'src' => $imageSrc,
                    ]);
                }
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
