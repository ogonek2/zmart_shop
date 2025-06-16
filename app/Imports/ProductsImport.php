<?php

namespace App\Imports;

use App\Models\Product;
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
    private $drawings = [];

    public function setDrawings($drawings)
    {
        $this->drawings = $drawings;
    }

    public function drawings()
    {
        return $this->drawings;
    }

    public function collection(Collection $rows)
    {
        // Это покажет все строки из таблицы
        dd($rows->toArray());
    }
}
