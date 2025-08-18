<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use App\DataTables\ActivityDataTable;
use Spatie\Activitylog\Models\Activity;


class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function settings(Request $request)
    {
        $auth_user = auth()->user();
        $assets = ['phone'];
        $pageTitle = __('message.setting');
        $page = $request->page;

        if($page == ''){
            if($auth_user->hasAnyRole(['admin', 'super_admin'])){
                $page = 'general-setting';
            }else{
                $page = 'profile_form';
            }
        }

        return view('setting.index',compact('page', 'pageTitle' ,'auth_user', 'assets'));
    }

    public function layoutPage(Request $request)
    {
        $page = $request->page;
        $auth_user = auth()->user();
        $user_id = $auth_user->id;
        $settings = AppSetting::first();
        $user_data = User::find($user_id);
        $envSettting = $envSettting_value = [];
               
        if(count($envSettting) > 0 ){
            $envSettting_value = Setting::whereIn('key',array_keys($envSettting))->get();
        }
        if($settings == null){
            $settings = new AppSetting;
        }elseif($user_data == null){
            $user_data = new User;
        }
        switch ($page) {
            case 'password_form':
                $data  = view('setting.'.$page, compact('settings','user_data','page'))->render();
                break;
            case 'profile_form':
                $assets = ['phone'];
                $health_expert_data = null;
                if ($auth_user->hasRole('doctor')) {
                    # code...
                    $health_expert_data = optional($auth_user->health_expert);
                }
                $data  = view('setting.'.$page, compact('auth_user','settings','user_data','page', 'assets','health_expert_data'))->render();
                break;
            case 'mail-setting':
                $data  = view('setting.'.$page, compact('settings','page'))->render();
                break;
            case 'mobile-config':
                $setting = Config::get('mobile-config');
                $getSetting = [];
                foreach($setting as $k=>$s){
                    foreach ($s as $sk => $ss){
                        $getSetting[]=$k.'_'.$sk;
                    }
                }
                
                $setting_value = Setting::whereIn('key',$getSetting)->get();
                $forceUpdate = Setting::where('key','APPVERSION_FORCE_UPDATE')->first();
                $image = Setting::where('key','ICON_IMAGE')->where('type','CRISP_CHAT_CONFIGURATION')->first();
              
                $data  = view('setting.'.$page, compact('setting', 'setting_value', 'page','forceUpdate','image'))->render();
                
                 break;
            
            case 'notification-setting':
                $notification_setting = config('constant.notification');
                $page = 'notification-setting';
                $notification_setting_data = AppSetting::first();
               
                $data  = view('setting.'.$page, compact('notification_setting', 'page', 'notification_setting_data'))->render();
            break;
            case 'database-backup':
                $setting_value = AppSetting::get();
                $pageTitle = __('message.database_backup');
                $data  = view('setting.' . $page, compact('page', 'pageTitle', 'setting_value', 'settings'))->render();
                break;
            case 'appversion':
               $setting = Config::get('mobile-config');
                $getSetting = [];
                foreach($setting as $k=>$s){
                    foreach ($s as $sk => $ss){
                        $getSetting[]=$k.'_'.$sk;
                    }
                }
                
                $setting_value = Setting::whereIn('key',$getSetting)->get();
                $forceUpdate = Setting::where('key','APPVERSION_FORCE_UPDATE')->first();
                $image = Setting::where('key','ICON_IMAGE')->where('type','CRISP_CHAT_CONFIGURATION')->first();
              
                $data  = view('setting.'.$page, compact('setting', 'setting_value', 'page','forceUpdate','image'))->render();
                break;
            case 'ads':
                  $settingsData = Setting::where('type', 'adsconfig')->pluck('value', 'key')->toArray();
                  $settingsAds = Setting::where('type','adsaccess')->get();
                   $settingsDataAds = [];
                foreach ($settingsAds as $settingsFeature) {
                    $settingsDataAds[$settingsFeature->key] = $settingsFeature->value;
                }
                
                $data  = view('setting.'.$page, compact('settingsData','settingsDataAds'))->render();
            break;
            default:
                $data  = view('setting.'.$page, compact('settings','page','envSettting'))->render();
                break;
        }
        return response()->json($data);
    }

    public function settingUpdate(Request $request)
    { 
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }

        $data = $request->all();
        foreach($data['key'] as $key => $val){
            $value = ( $data['value'][$key] != null) ? $data['value'][$key] : null;
            $input = [
                'type' => $data['type'][$key],
                'key' => $data['key'][$key],
                'value' => ( $data['value'][$key] != null) ? $data['value'][$key] : null,
            ];
            Setting::updateOrCreate(['key'=>$input['key']],$input);
            envChanges($data['key'][$key],$value);
        }
        if ($request->hasFile('subscription_image')) {
            $setting = Setting::firstOrCreate(['key' => 'subscription_image'], ['type' => 'subscription', 'value' => null]);
        
            foreach ($request->file('subscription_image') as $image) {
                $setting->addMedia($image)->toMediaCollection('subscription_image');
            }
        }
        if ($request->hasFile('icon_image')) {
            $setting = Setting::firstOrCreate(
                ['key' => 'ICON_IMAGE'],
                ['type' => 'CRISP_CHAT_CONFIGURATION', 'value' => 'icon_image']
            );
            $setting->clearMediaCollection('icon_image');
            
            $setting->addMedia($request->file('icon_image'))->toMediaCollection('icon_image');
        }
        
        if(isset($data['appversion'])){
            return redirect()->route('setting.index', ['page' => 'appversion'])->withSuccess(__('message.updated'));
        }elseif(isset($data['adsconfig'])){
            return redirect()->route('setting.index', ['page' => 'ads'])->withSuccess(__('message.updated'));
        }
        else{
            return redirect()->route('setting.index', ['page' => 'mobile-config'])->withSuccess( __('message.updated'));
        }
    }

    public function settingsUpdates(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }
        $page = $request->page;
        if($request->page == 'database-backup'){
            $res = AppSetting::updateOrCreate([ 'id' => $request->id ], $request->all());
        }else{

            $language_option = $request->language_option;
            if(!is_array($language_option)){
                $language_option=(array)$language_option;
            }

            array_push($language_option, $request->env['DEFAULT_LANGUAGE']);

            $request->merge(['language_option' => $language_option]);

            $request->merge(['site_name' => str_replace("'", "", str_replace('"', '', $request->site_name))]);

            $res = AppSetting::updateOrCreate([ 'id' => $request->id ], $request->all());

            $type = 'APP_NAME';
            $env = $request->env;

            $env['APP_NAME'] = $res->site_name;
            foreach ($env as $key => $value){
                envChanges($key, $value);
            }

            $message = '';
            
            App::setLocale($env['DEFAULT_LANGUAGE']);
            session()->put('locale', $env['DEFAULT_LANGUAGE']);

            if($request->timezone != '') {
                $user = auth()->user();            
                $user->timezone = $request->timezone;
                $user->save();
            }
            uploadMediaFile($res,$request->site_logo, 'site_logo');
            uploadMediaFile($res,$request->site_dark_logo, 'site_dark_logo');
            uploadMediaFile($res,$request->site_favicon, 'site_favicon');
            
            appSettingData('set');

            createLangFile($env['DEFAULT_LANGUAGE']);
        }

        return redirect()->route('setting.index', ['page' => $page])->withSuccess( __('message.updated'));
    }
    
    public function envChanges(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }
        $page = $request->page;

        if(!auth()->user()->hasRole('admin')) {
            abort(403, __('message.action_is_unauthorized'));
        }
        $env = $request->ENV;
        $envtype = $request->type;

        foreach ($env as $key => $value){
            envChanges($key, str_replace('#','',$value));
        }
        Artisan::call('cache:clear');
        return redirect()->route('setting.index', ['page' => $page])->withSuccess(ucfirst($envtype).' '.__('message.updated'));
    }

    public function updateProfile(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        unset($request['email']);
        $user = Auth::user();
        $page = $request->page;

        $user->fill($request->all())->update();
        uploadMediaFile($user,$request->profile_image, 'profile_image');

        return redirect()->route('setting.index', ['page' => 'profile_form'])->withSuccess( __('message.profile').' '.__('message.updated'));
    }

    public function updateHealthExpertProfile(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'tag_line' => 'required',
            'short_description' => 'required',
        ]);
        
        if ($validator->fails()) {
            if(request()->ajax()) {
                return response()->json(['status' => false, 'message' => $validator->errors() ]);
            }
            return redirect()->back()->with('errors', $validator->errors());
        }

        $user = Auth::user();
        $page = $request->page;

        $user->fill($request->all())->update();

        $expert_data = optional($user->health_expert);

        $user->fill([
            'first_name' => $request->name,
            'display_name' => $request->name,
        ])->update();

        // Health Expert data...
        unset($request['name']);
        unset($request['email']);

        $expert_data->fill($request->all())->update();

        if (isset($request->health_experts_image) && $request->health_experts_image != null) {
            $expert_data->clearMediaCollection('health_experts_image');
            $expert_data->addMediaFromRequest('health_experts_image')->toMediaCollection('health_experts_image');
        }

        return redirect()->route('setting.index', ['page' => 'profile_form'])->withSuccess( __('message.profile').' '.__('message.updated'));
    }

    public function changePassword(Request $request)
    {

        $user = User::where('id',Auth::user()->id)->first();

        if($user == "") {
            $message = __('message.not_found_entry',[ 'name' => __('message.user') ]);
            return json_message_response($message,400);   
        }
        
        $validator= Validator::make($request->all(), [
            'old' => 'required|min:8|max:255',
            'password' => 'required|min:8|confirmed|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('setting.index', ['page' => 'password_form'])->with('errors',$validator->errors());
        }
           
        $hashedPassword = $user->password;

        $match = Hash::check($request->old, $hashedPassword);

        $same_exits = Hash::check($request->password, $hashedPassword);
        if ($match)
        {
            if($same_exits){
                $message = __('message.old_new_pass_same');
                return redirect()->route('setting.index', ['page' => 'password_form'])->with('error',$message);
            }

			$user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            Auth::logout();
            $message = __('message.password_change');
            return redirect()->route('setting.index', ['page' => 'password_form'])->withSuccess($message);
        }
        else
        {
            $message = __('message.valid_password');
            return redirect()->route('setting.index', ['page' => 'password_form'])->with('error',$message);
        }
    }
    
    public function termAndCondition(Request $request)
    {
        $setting_data = Setting::where('type','terms_condition')->where('key','terms_condition')->first();
        $pageTitle = __('message.terms_condition');
        $assets = ['textarea'];
        return view('setting.term_condition_form',compact('setting_data', 'pageTitle', 'assets'));
    }

    public function saveTermAndCondition(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('term-condition')->withErrors($message);
        }

        if(!auth()->user()->hasRole('admin')) {
            abort(403, __('message.action_is_unauthorized'));
        }
        $setting_data = [
                        'type'  => 'terms_condition',
                        'key'   =>  'terms_condition',
                        'value' =>  $request->value 
                    ];
        $result = Setting::updateOrCreate(['id' => $request->id],$setting_data);
        if($result->wasRecentlyCreated)
        {
            $message = __('message.save_form',['form' => __('message.terms_condition')]);
        }else{
            $message = __('message.update_form',['form' => __('message.terms_condition')]);
        }

        return redirect()->route('term-condition')->withsuccess($message);
    }

    public function privacyPolicy(Request $request)
    {
        $setting_data = Setting::where('type','privacy_policy')->where('key','privacy_policy')->first();
        $pageTitle = __('message.privacy_policy');
        $assets = ['textarea'];

        return view('setting.privacy_policy_form',compact('setting_data', 'pageTitle', 'assets'));
    }

    public function savePrivacyPolicy(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->route('privacy-policy')->withErrors($message);
        }

        if(!auth()->user()->hasRole('admin')) {
            abort(403, __('message.action_is_unauthorized'));
        }
        $setting_data = [
                        'type'   => 'privacy_policy',
                        'key'   =>  'privacy_policy',
                        'value' =>  $request->value 
                    ];
        $result = Setting::updateOrCreate(['id' => $request->id],$setting_data);
        if($result->wasRecentlyCreated)
        {
            $message = __('message.save_form',['form' => __('message.privacy_policy')]);
        }else{
            $message = __('message.update_form',['form' => __('message.privacy_policy')]);
        }

        return redirect()->route('privacy-policy')->withsuccess($message);
    }

    public function activity(ActivityDataTable $dataTable)
    {
        $pageTitle = 'History';
        $auth_user = authSession();
        $assets = ['datatable'];
        $button = '';
        return $dataTable->render('global.datatable', compact('pageTitle','button','auth_user'));
    }

    public function viewChanges($id)
    {
        $activity = Activity::with('causer')->findOrFail($id);

        $title = __('Changes made by :user', ['user' => $activity->causer->display_name ?? 'System']);

        $changes = [
            'old' => $activity->properties['old'] ?? [],
            'new' => $activity->properties['attributes'] ?? [],
        ];

        return view('activity.view-changes', compact('title', 'changes'));
    }

}
 