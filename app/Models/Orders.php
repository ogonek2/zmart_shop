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
                // if already plain or invalid — leave it as is
            }
        }

        return $value;
    }
}
