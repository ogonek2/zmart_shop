<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ProductFeedService;

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
        'is_wholesale',
        'wholesale_price',
        'wholesale_min_quantity',
        'image_path',
        'complectation',
        'brand',
        'condition_item',
        'availability',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'characteristics',
        'modifications',
        'additional_fields',
    ];

    protected $casts = [
        'characteristics' => 'array',
        'modifications' => 'array',
        'additional_fields' => 'array',
        'is_wholesale' => 'boolean',
        'wholesale_price' => 'decimal:2',
        'wholesale_min_quantity' => 'integer',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class);
    }
    
    public function packages()
    {
        return $this->belongsToMany(\App\Models\package::class);
    }

    public function relations()
    {
        return $this->hasMany(ProductCategoryCatalogRelation::class);
    }
    
    public function images()
    {
        return $this->hasMany(\App\Models\productImage::class, 'product_id');
    }

    // Получить шаблон из связанной категории
    public function getCategoryTemplate()
    {
        $category = $this->categories()->with('template')->first();
        return $category?->template;
    }

    // Получить шаблон из связанного каталога
    public function getCatalogTemplate()
    {
        $catalog = $this->catalogs()->with('template')->first();
        return $catalog?->template;
    }

    // Получить активный шаблон (приоритет: каталог > категория)
    public function getActiveTemplate()
    {
        return $this->getCatalogTemplate() ?? $this->getCategoryTemplate();
    }

    // Получить характеристики из шаблона
    public function getTemplateCharacteristics()
    {
        $template = $this->getActiveTemplate();
        return $template?->characteristics ?? [];
    }

    // Получить модификации из шаблона
    public function getTemplateModifications()
    {
        $template = $this->getActiveTemplate();
        return $template?->modifications ?? [];
    }

    // Получить дополнительные поля из шаблона
    public function getTemplateAdditionalFields()
    {
        $template = $this->getActiveTemplate();
        return $template?->additional_fields ?? [];
    }

    // Получить путь к изображению (с поддержкой CDN)
    public function getImagePath()
    {
        if (!$this->image_path) {
            return asset('dist/img/no-image.png'); // Fallback изображение
        }

        // Если путь уже содержит полный URL (например, из CDN)
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        // Проверяем, есть ли изображение в локальном хранилище
        $localPath = storage_path('app/public/' . $this->image_path);
        if (file_exists($localPath)) {
            return asset('storage/' . $this->image_path);
        }

        // Если изображение на CDN (BunnyCDN)
        if (config('app.cdn_url')) {
            return config('app.cdn_url') . '/' . $this->image_path;
        }

        // Fallback на storage
        return asset('storage/' . $this->image_path);
    }

    protected static function booted()
    {
        static::deleting(function ($product) {
            // Удаляем связи many-to-many
            $product->catalogs()->detach();
            $product->categories()->detach();
            
            // Удаляем характеристики товара
            $product->packages()->delete();
            
            // Удаляем связанные изображения
            $productImages = \App\Models\productImage::where('product_id', $product->id)->get();
            foreach ($productImages as $image) {
                // Удаляем файл с CDN
                if (class_exists('\App\Helpers\FileUploadHelper')) {
                    \App\Helpers\FileUploadHelper::deleteFromBunnyCDN($image->src);
                }
                // Удаляем запись из базы
                $image->delete();
            }
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

        static::saved(function ($product) {
            ProductFeedService::generate();
        });

        static::deleted(function ($product) {
            ProductFeedService::generate();
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
