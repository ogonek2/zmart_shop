<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_service',
        'city',
        'warehouse',
        'manual_address',
        'name',
        'lastname',
        'fathername',
        'phone',
        'comment',
        'cart',
        'total_price',
        'payment',
    ];

    protected $encryptable = [
        'delivery_service',
        'city',
        'warehouse',
        'manual_address',
        'name',
        'lastname',
        'fathername',
        'phone',
        'comment',
        'cart',
        'total_price',
        'payment',
    ];

    // Automatically encrypt attributes before saving
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = Crypt::encryptString($value);
        }

        return parent::setAttribute($key, $value);
    }

    // Automatically decrypt attributes when accessing
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable) && !is_null($value)) {
            try {
                $value = Crypt::decryptString($value);
            } catch (\Exception $e) {
                // Если не удалось расшифровать, возвращаем исходное значение
                return $value;
            }
        }

        return $value;
    }

    // Вспомогательные методы для работы с данными
    public function getFormattedTotalPriceAttribute()
    {
        $totalPrice = $this->total_price;
        if (is_numeric($totalPrice)) {
            return number_format((float)$totalPrice, 2, ',', ' ') . ' ₴';
        }
        return '0,00 ₴';
    }

    public function getNumericTotalPriceAttribute()
    {
        $totalPrice = $this->total_price;
        return is_numeric($totalPrice) ? (float)$totalPrice : 0;
    }

    public function getFullNameAttribute()
    {
        $name = $this->name ?? '';
        $lastname = $this->lastname ?? '';
        return trim($name . ' ' . $lastname);
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d.m.Y H:i') : 'Не указано';
    }

    // Метод для получения товаров из корзины (JSON)
    public function getCartItemsAttribute()
    {
        $cart = $this->cart;
        
        if (is_string($cart)) {
            // Попробуем сначала декодировать как JSON
            $decoded = json_decode($cart, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            
            // Если не получилось, попробуем расшифровать
            try {
                $decrypted = Crypt::decryptString($cart);
                $decoded = json_decode($decrypted, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
                
                // Если расшифровка дала строку, попробуем расшифровать еще раз
                if (is_string($decrypted)) {
                    $doubleDecrypted = Crypt::decryptString($decrypted);
                    $decoded = json_decode($doubleDecrypted, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        return $decoded;
                    }
                }
            } catch (\Exception $e) {
                // Если расшифровка не удалась, возвращаем пустой массив
            }
        }
        
        return [];
    }
}
