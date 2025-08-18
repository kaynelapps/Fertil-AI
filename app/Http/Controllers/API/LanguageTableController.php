<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LanguageVersionDetail;
use App\Http\Resources\LanguageTableResource;
use App\Models\AppSetting;
use App\Models\LanguageList;
use App\Traits\EncryptionTrait;

class LanguageTableController extends Controller
{
    use EncryptionTrait;
    
    public function getList(Request $request)
    {
        $version_data = LanguageVersionDetail::where('version_no',request('version_no'))->first();

         $data['app_setting'] = AppSetting::first();

        $app_version = [
            'android_force_update'  => (bool)SettingData('APPVERSION', 'APPVERSION_ANDROID_FORCE_UPDATE'),
            'android_version_code'  => (int)SettingData('APPVERSION', 'APPVERSION_ANDROID_VERSION_CODE'),
            'appstore_url'          => SettingData('APPVERSION', 'APPVERSION_APPSTORE_URL'),
            'ios_force_update'      => (bool)SettingData('APPVERSION', 'APPVERSION_IOS_FORCE_UPDATE'),
            'ios_version'           => (int)SettingData('APPVERSION', 'APPVERSION_IOS_VERSION'),
        ];


        if (isset($version_data) && !empty($version_data)) {
            return response()->json([
                'responseData' => $this->encryptData([ 
                    'status' => false,
                    'data' => [],
                    'app_version'   => $app_version, 
                    'app_setting' => $data ?? null,
                    ])
            ]);
        }

        $themeColor = appSettingData();
        $language_content = LanguageList::query()->where('status','1')->orderBy('id', 'asc')->get();
        $language_version = LanguageVersionDetail::find(1);
        $items = LanguageTableResource::collection($language_content);

       

        $response = [
            'status' => true,
            'version_code' => $language_version->version_no,
            'default_language_id' => $language_version->default_language_id,
            'data' => $items,
            'theme_color' => $themeColor->color,
            'app_version'   => $app_version, 
            'app_setting' => $data ?? null,
        ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
    }
}
