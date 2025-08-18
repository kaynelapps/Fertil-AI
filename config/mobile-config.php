<?php

return [
    'FEATURE' => [
        'ASK_EXPERT' => '',
        'ADD_DUMMY_DATA' => ''
    ],

    'ONESIGNAL' => [
        'APP_ID' => env('ONESIGNAL_APP_ID'),
        'REST_API_KEY' => env('ONESIGNAL_REST_API_KEY'),
    ],

    'CHATGPT' => [
        'API_KEY' => '',
        'ENABLE/DISABLE' => '',
    ],

    'CRISP_CHAT_CONFIGURATION' => [
        'WEBSITE_ID' => '',
        'ENABLE/DISABLE' => '',
    ],

    'APPVERSION' => [
        // 'ANDROID_BUILD_NUMBER' => '',
        // 'IOS_BUILD_NUMBER' => '',
        'ANDROID_FORCE_UPDATE' => '',
        'ANDROID_VERSION_CODE' => '',
        'PLAYSTORE_URL' => '',
        'IOS_FORCE_UPDATE' => '',
        'IOS_VERSION' => '',
        'APPSTORE_URL' => '',
    ],
];
