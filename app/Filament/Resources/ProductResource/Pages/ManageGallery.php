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

    public function deleteImage($imageId)
    {
        $image = productImage::find($imageId);
        
        if ($image && $image->product_id === $this->record->id) {
            // Удаляем файл с диска (если не из CDN)
            if ($image->src && !str_starts_with($image->src, 'http')) {
                Storage::disk('public')->delete($image->src);
            }
            
            $image->delete();
            
            Notification::make()
                ->success()
                ->title('Изображение удалено')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Изображение не найдено')
                ->send();
        }
    }

    public function setAsMainImage($imageId)
    {
        $image = productImage::find($imageId);
        
        if ($image && $image->product_id === $this->record->id) {
            $this->record->image_path = $image->src;
            $this->record->save();
            
            Notification::make()
                ->success()
                ->title('Главное изображение установлено')
                ->send();
        }
    }
}

