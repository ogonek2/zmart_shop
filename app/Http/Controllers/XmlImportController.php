<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\XmlGenerator;
use Maatwebsite\Excel\Facades\Excel;

class XmlImportController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // макс 5 МБ
        ]);

        // Сохраняем файл во временную папку
        $path = $request->file('excel_file')->store('imports');

        Excel::import(new XmlGenerator, $path);

        return response()->json(['message' => 'XML успешно сгенерирован и сохранён.']);
    }
}
