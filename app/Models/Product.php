<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'articule',
        'description',
        'url',
        'discount',
        'price',
        'image_path',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function catalog()
    {
        return $this->belongsToMany(Catalog::class);
    }

    protected static function booted()
    {
        static::deleting(function ($product) {
            $product->catalogs()->detach(); // Удаляем связи many-to-many
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            // Генерируем базовый URL, если пустой
            $baseUrl = self::generateHref($product->name);

            // Проверяем есть ли уже такой URL у других продуктов
            $exists = self::where('url', $baseUrl)
                ->when($product->id, fn($query) => $query->where('id', '!=', $product->id)) // исключаем текущий продукт при обновлении
                ->exists();

            if (!$exists) {
                $product->url = $baseUrl;
            } else {
                // Если такой URL уже есть, добавляем ID к URL
                // Для новых продуктов $product->id еще нет, поэтому можно сгенерировать уникальный вариант через временный суффикс
                if ($product->id) {
                    $product->url = $baseUrl . '-' . $product->id;
                } else {
                    // Если id еще нет (новый продукт), то генерируем уникальный суффикс, например, временную метку или случайное число
                    $product->url = $baseUrl . '-' . uniqid();
                }
            }
        });
    }

    // Метод для генерации href
    public static function generateHref($text)
    {
        $text = mb_strtolower($text);

        $text = str_replace(
            ['а', 'б', 'в', 'г', 'ґ', 'д', 'е', 'є', 'ж', 'з', 'и', 'і', 'ї', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я'],
            ['a', 'b', 'v', 'g', 'g', 'd', 'e', 'ye', 'zh', 'z', 'y', 'i', 'yi', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'kh', 'ts', 'ch', 'sh', 'shch', '', 'yu', 'ya'],
            $text
        );

        $text = preg_replace('/[^\w\-]+/', '-', $text);
        $text = trim($text, '-');

        return $text;
    }
}
