<?php

use App\Models\Category;
use Illuminate\Support\Facades\Http;

function get_all_category() {
    return Category::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->orderBy('name', 'asc')
        ->get();
}

function uploadToBunnyCDN($localFilePath, $destinationPath)
{
    $storageName = env('BUNNY_STORAGE_NAME');
    $password = env('BUNNY_STORAGE_PASSWORD');
    $region = env('BUNNY_STORAGE_REGION', 'de');

    // Проверяем существование файла
    if (!file_exists($localFilePath)) {
        throw new \Exception("File not found: " . $localFilePath);
    }

    $url = "https://storage.bunnycdn.com/{$storageName}/{$destinationPath}";

    // Используем cURL для более надежной загрузки бинарных файлов
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'AccessKey: ' . $password,
        'Content-Type: application/octet-stream',
    ]);
    
    // Отключаем проверку SSL в среде разработки
    if (env('APP_ENV') !== 'production') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    
    // Открываем файл для чтения
    $fileHandle = fopen($localFilePath, 'rb');
    if (!$fileHandle) {
        curl_close($ch);
        throw new \Exception("Cannot open file: " . $localFilePath);
    }
    
    curl_setopt($ch, CURLOPT_INFILE, $fileHandle);
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localFilePath));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    fclose($fileHandle);
    curl_close($ch);
    
    if ($error) {
        throw new \Exception("cURL error: " . $error);
    }
    
    if ($httpCode >= 200 && $httpCode < 300) {
        return env('BUNNY_CDN_URL') . '/' . $destinationPath;
    } else {
        throw new \Exception("Upload failed with HTTP code: " . $httpCode . " Response: " . $response);
    }
}