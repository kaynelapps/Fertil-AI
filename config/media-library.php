<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Media Library Disk
    |--------------------------------------------------------------------------
    |
    | Here you can specify the disk on which to store all media files.
    | You can set it to 'public' or 's3' or any other disk that you've
    | configured in your filesystem.
    |
    */

    'disk' => env('MEDIA_LIBRARY_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Max File Size
    |--------------------------------------------------------------------------
    |
    | Here you can specify the maximum allowed file size for uploads.
    | In bytes, the default is 10 MB (10 * 1024 * 1024).
    |
    */

    'max_file_size' => 1024 * 1024 * 1024, // 1 GB

    /*
    |--------------------------------------------------------------------------
    | Media Collections
    |--------------------------------------------------------------------------
    |
    | Here you can specify your media collections for the different types
    | of media you upload to your application.
    |
    */

    'collections' => [
        'default' => [
            'disk' => 'public',
        ],
        'avatars' => [
            'disk' => 'public',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Conversion Defaults
    |--------------------------------------------------------------------------
    |
    | Here you can define default conversion settings for images.
    |
    */

    'image_conversions' => [
        'thumb' => [
            'width' => 150,
            'height' => 150,
            'quality' => 90,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Temporary Upload Path
    |--------------------------------------------------------------------------
    |
    | The temporary directory that media is stored in during the upload process.
    | If you're running a large upload, increasing this directory may help.
    |
    */

    'temp_upload_path' => storage_path('app/temp_uploads'),

    /*
    |--------------------------------------------------------------------------
    | Fallback URL
    |--------------------------------------------------------------------------
    |
    | This URL will be used for media items that do not exist or have not
    | been uploaded successfully. The default is a 404 page.
    |
    */

    'fallback_url' => env('APP_URL').'/storage',

    /*
    |--------------------------------------------------------------------------
    | Fallback Path
    |--------------------------------------------------------------------------
    |
    | The fallback path will be used when no disk is available for the media.
    |
    */

    'fallback_path' => public_path('storage'),

];
