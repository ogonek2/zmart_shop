<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ProductsImport implements ToCollection, WithHeadingRow
{
    protected $cache = [];

    public function collection(Collection $rows)
    {
        // Здесь обрабатываем небольшой кусок из $rows
        foreach ($rows as $row) {
            if (empty($row['title']))
                continue;

            $product = Product::create([
                'name' => $row['title'],
                'articule' => $row['articule'] ?? null,
                'price' => $row['price'] ?? 0,
                'description' => $row['description'] ?? '',
            ]);

            // Создаем или ищем категорию по имени
            $category = Category::firstOrCreate(['name' => $row['category'] ?? '']);

            // Привязываем категорию к продукту (без удаления уже существующих связей)
            $product->categories()->syncWithoutDetaching($category->id);
        }
    }

    public function chunkSize(): int
    {
        return 100; // обрабатываем по 100 строк за один раз
    }
}
//     "title" => "Весы торговые MATARIX MX-410B 50кг"
//     "articule" => 4100
//     "price" => 840.65
//     "image" => null
//     "description" => "Весы торговые MATARIX MX-410B 50кг"
//     "category" => "Ваги торгові"