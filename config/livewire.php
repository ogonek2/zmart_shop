<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Uploads
    |--------------------------------------------------------------------------
    |
    | Configure the file upload settings for Livewire components.
    |
    */

    'temporary_file_upload' => [
        'disk' => env('LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK', 'local'),
        'rules' => null, // ['file', 'max:12288'], // 12MB Max
        'directory' => 'livewire-tmp',
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a', 'jpg', 'jpeg'
        ],
        'max_upload_time' => 5, // Max time (in minutes) before an upload is invalidated.
    ],

    /*
    |--------------------------------------------------------------------------
    | File Downloads
    |--------------------------------------------------------------------------
    |
    | Configure the file download settings for Livewire components.
    |
    */

    'file_downloads' => [
        'disk' => env('LIVEWIRE_FILE_DOWNLOADS_DISK', 'local'),
        'directory' => 'livewire-downloads',
    ],
];
