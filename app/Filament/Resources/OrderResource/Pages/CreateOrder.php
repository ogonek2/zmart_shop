<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Принудительно шифруем данные перед созданием
        $encryptable = [
            'delivery_service', 'city', 'warehouse', 'manual_address',
            'name', 'lastname', 'fathername', 'phone', 'comment',
            'cart', 'total_price', 'payment'
        ];
        
        foreach ($encryptable as $field) {
            if (isset($data[$field]) && $data[$field]) {
                $data[$field] = \Illuminate\Support\Facades\Crypt::encryptString($data[$field]);
            }
        }
        
        return $data;
    }
}
