<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\productImage;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Storage;

class ManageGallery extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.manage-gallery';

    public Product $record;

    public $uploadedImages = [];

    public function mount($record)
    {
        $this->record = $record->load('images');
    }

    protected function getActions(): array
    {
        return [
            Action::make('upload_images')
                ->label('Загрузить изображения')
                ->icon('heroicon-o-upload')
                ->form([
                    FileUpload::make('images')
                        ->label('Выберите изображения')
                        ->image()
                        ->multiple()
                        ->directory('products/gallery')
                        ->disk('public')
                        ->preserveFilenames()
                        ->maxFiles(10)
                        ->helperText('Загрузите до 10 изображений одновременно'),
                ])
                ->action(function (array $data) {
                    if (!empty($data['images'])) {
                        foreach ($data['images'] as $imagePath) {
                            // Загружаем на CDN
                            $localPath = storage_path('app/public/' . $imagePath);
                            
                            if (file_exists($localPath)) {
                                try {
                                    // Используем глобальный хелпер uploadToBunnyCDN
                                    $cdnUrl = uploadToBunnyCDN($localPath, $imagePath);
                                    
                                    // Удаляем локальный файл после успешной загрузки на CDN
                                    unlink($localPath);
                                    
                                    productImage::create([
                                        'product_id' => $this->record->id,
                                        'src' => $cdnUrl,
                                    ]);
                                    
                                    \Log::info('Gallery image uploaded to CDN', ['cdnUrl' => $cdnUrl]);
                                } catch (\Exception $e) {
                                    \Log::error('Failed to upload gallery image to CDN: ' . $e->getMessage());
                                    
                                    // Если загрузка на CDN не удалась, сохраняем локально
                                    productImage::create([
                                        'product_id' => $this->record->id,
                                        'src' => $imagePath,
                                    ]);
                                }
                            }
                        }
                        
                        Notification::make()
                            ->success()
                            ->title('Изображения загружены')
                            ->body('Загружено изображений: ' . count($data['images']))
                            ->send();
                        
                        // Перезагружаем страницу
                        return redirect()->route('filament.resources.products.manage-gallery', $this->record);
                    }
                })
                ->color('success')
                ->modalHeading('Загрузка изображений')
                ->modalButton('Загрузить'),
        ];
    }

    public function deleteImage($record)
    {
        try {
            $imageId = request()->input('image_id');
            
            if (!$imageId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID изображения не указан'
                ], 400);
            }
            
            $image = productImage::find($imageId);
            
            if ($image && $image->product_id == $record) {
                // Удаляем файл с диска (если не из CDN)
                if ($image->src && !str_starts_with($image->src, 'http')) {
                    Storage::disk('public')->delete($image->src);
                }
                
                $image->delete();
                
                Notification::make()
                    ->success()
                    ->title('Изображение удалено')
                    ->send();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Изображение успешно удалено'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Изображение не найдено или не принадлежит данному товару'
                ], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при удалении изображения: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setAsMainImage($record)
    {
        try {
            $imageId = request()->input('image_id');
            
            if (!$imageId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID изображения не указан'
                ], 400);
            }
            
            $image = productImage::find($imageId);
            
            if ($image && $image->product_id == $record) {
                $this->record = Product::find($record);
                $this->record->image_path = $image->src;
                $this->record->save();
                
                Notification::make()
                    ->success()
                    ->title('Главное изображение установлено')
                    ->send();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Главное изображение успешно установлено'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Изображение не найдено или не принадлежит данному товару'
                ], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Error setting main image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при установке главного изображения: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function replaceImage($record)
    {
        try {
            $imageId = request()->input('image_id');
            
            if (!$imageId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID изображения не указан'
                ], 400);
            }
            
            $image = productImage::find($imageId);
            
            if (!$image || $image->product_id != $record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Изображение не найдено или не принадлежит данному товару'
                ], 404);
            }
            
            // Получаем новое изображение из запроса
            $newImage = request()->file('new_image');
            
            if (!$newImage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Новое изображение не найдено'
                ], 400);
            }
            
            // Удаляем старое изображение с диска (если не из CDN)
            if ($image->src && !str_starts_with($image->src, 'http')) {
                Storage::disk('public')->delete($image->src);
            }
            
            // Сохраняем новое изображение
            $newImagePath = $newImage->store('products/gallery', 'public');
            
            // Обновляем запись в базе данных
            $image->src = $newImagePath;
            $image->save();
            
            // Если это было главное изображение, обновляем и его
            $this->record = Product::find($record);
            if ($this->record->image_path === $image->src) {
                $this->record->image_path = $newImagePath;
                $this->record->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Изображение успешно заменено'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error replacing image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при замене изображения: ' . $e->getMessage()
            ], 500);
        }
    }
}

