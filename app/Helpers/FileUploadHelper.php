<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class FileUploadHelper
{
    /**
     * Загружает файл на BunnyCDN и возвращает публичный URL.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder - папка для хранения, например 'products'
     * @return string|null - публичный URL или null при ошибке
     */
    public static function uploadToBunnyCDN($file, $folder = 'products')
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::random(20) . '.' . $extension;
        $destinationPath = $folder . '/' . $fileName;

        $fileContents = file_get_contents($file->getRealPath());

        $url = "https://storage.bunnycdn.com/" . env('BUNNY_STORAGE_NAME') . "/$destinationPath";

        $response = Http::withHeaders([
            'AccessKey' => env('BUNNY_STORAGE_PASSWORD'),
            'Content-Type' => 'application/octet-stream',
        ])->withBody($fileContents, 'application/octet-stream')
          ->put($url);

        if ($response->successful()) {
            return rtrim(env('BUNNY_CDN_URL'), '/') . '/' . $destinationPath;
        }

        return null;
    }
}
