<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('download_invoice')
                ->label('📄 Завантажити PDF')
                ->icon('heroicon-o-document-download')
                ->url(fn () => route('invoice.download', $this->record->id))
                ->openUrlInNewTab()
                ->color('success'),
            Actions\Action::make('view_invoice')
                ->label('👁️ Переглянути PDF')
                ->icon('heroicon-o-eye')
                ->url(fn () => route('invoice.view', $this->record->id))
                ->openUrlInNewTab()
                ->color('info'),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Принудительно расшифровываем данные перед заполнением формы
        $encryptable = [
            'delivery_service', 'city', 'warehouse', 'manual_address',
            'name', 'lastname', 'fathername', 'phone', 'comment',
            'cart', 'total_price', 'payment'
        ];
        
        foreach ($encryptable as $field) {
            if (isset($data[$field]) && $data[$field]) {
                try {
                    $data[$field] = \Illuminate\Support\Facades\Crypt::decryptString($data[$field]);
                } catch (\Exception $e) {
                    // Если не удалось расшифровать, оставляем как есть
                }
            }
        }
        
        return $data;
    }
}
