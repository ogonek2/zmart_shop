<?php

use App\Models\Category;
use Illuminate\Support\Facades\Http;

function get_all_category() {
    return Category::all();
}

function uploadToBunnyCDN($localFilePath, $destinationPath)
{
    $storageName = env('BUNNY_STORAGE_NAME');
    $password = env('BUNNY_STORAGE_PASSWORD');
    $region = env('BUNNY_STORAGE_REGION', 'de');

    $fileContents = file_get_contents($localFilePath);

    $url = "https://storage.bunnycdn.com/{$storageName}/{$destinationPath}";

    $response = Http::withHeaders([
        'AccessKey' => $password,
        'Content-Type' => 'application/octet-stream',
    ])->put($url, $fileContents);

    if ($response->successful()) {
        return env('BUNNY_CDN_URL') . '/' . $destinationPath;
    } else {
        throw new \Exception("Upload failed: " . $response->body());
    }
}