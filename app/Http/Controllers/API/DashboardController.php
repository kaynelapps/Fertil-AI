<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\AskexpertResource;
use App\Http\Resources\CaycleDaysResource;
use App\Http\Resources\CycleDateDataResource;
use App\Http\Resources\DailyInsightsResource;
use App\Http\Resources\DefaultLogCategoryResource;
use App\Http\Resources\HealthExpertResource;
use App\Http\Resources\InsightsResource;
use App\Http\Resources\PersonalisedInsightsResource;
use App\Http\Resources\PregnancyDateResource;
use App\Http\Resources\PregnancyWeekResource;
use App\Http\Resources\SubSymptomsResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\AppSetting;
use App\Models\Article;
use App\Models\AskExperts;
use App\Models\CycleDateData;
use App\Models\CycleDates;
use App\Models\DailyInsights;
use App\Models\DefaultLogCategory;
use App\Models\HealthExpert;
use App\Models\Insights;
use App\Models\PersonalisedInsights;
use App\Models\PregnancyDate;
use App\Models\PregnancyWeek;
use App\Models\subscriptions;
use App\Models\Symptoms;
use App\Models\User;
use App\Traits\EncryptionTrait;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use EncryptionTrait;

    public function appsetting(Request $request)
    {
        $data['app_setting'] = AppSetting::first();

        $data['terms_condition'] = Setting::where('type', 'terms_condition')->where('key', 'terms_condition')->first();
        $data['privacy_policy'] = Setting::where('type', 'privacy_policy')->where('key', 'privacy_policy')->first();

        $currency_code = SettingData('CURRENCY', 'CURRENCY_CODE') ?? 'USD';
        $currency = currencyArray($currency_code);
        $data['subscription'] = SettingData('subscription', 'subscription_system') ?? '0';

        $data['currency_setting'] = [
            'name' => $currency['name'] ?? 'United States (US) dollar',
            'symbol' => $currency['symbol'] ?? '$',
            'code' => strtolower($currency['code']) ?? 'usd',
            'position' => SettingData('CURRENCY', 'CURRENCY_POSITION') ?? 'left',
        ];
        return response()->json([
            'responseData' => $this->encryptData($data)
        ]);
        // return json_custom_response($data);
    }

    public function commonDashboard($request)
    {
        $data = AppSetting::first();

        $data['terms_condition'] = Setting::where('type', 'terms_condition')->where('key', 'terms_condition')->first();
        $data['privacy_policy'] = Setting::where('type', 'privacy_policy')->where('key', 'privacy_policy')->first();

        $currency_code = SettingData('CURRENCY', 'CURRENCY_CODE') ?? 'USD';
        $currency = currencyArray($currency_code);
        $data['subscription'] = SettingData('subscription', 'subscription_system') ?? '0';
        $data['currency_setting'] = [
            'name' => $currency['name'] ?? 'United States (US) dollar',
            'symbol' => $currency['symbol'] ?? '$',
            'code' => strtolower($currency['code']) ?? 'usd',
            'position' => SettingData('CURRENCY', 'CURRENCY_POSITION') ?? 'left',
        ];
        return $data;
    }

    public function dashboard(Request $request)
    {
        $now = now()->tz('Asia/Kolkata');
        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json([
                    'responseData' => $this->encryptData(['error' => __('message.invalid_data')])
                ]);
            }
        } else {
            $decryptedData = $request->all();
        }
        
        $input = $decryptedData;

        $user = auth()->user();
        if (isset($input['app_version'])) {
            $user->app_version = $input['app_version'];
        }
        if (isset($input['app_source'])) {
            $user->app_source = $input['app_source'];
        }
        // if (isset($input['last_actived_at'])) {
        $user->last_actived_at = $now->format('Y-m-d H:i:s');
        // }
        if (isset($input['region'])) {
            $user->region = $input['region'];
        }
        if (isset($input['country_name'])) {
            $user->country_name = $input['country_name'];
        }
        if (isset($input['country_code'])) {
            $user->country_code = $input['country_code'];
        }
        if (isset($input['city'])) {
            $user->city = $input['city'];
        }

        if($user->is_backup == 'on' && ($input['encrypted_user_data'] != null)){
            if (isset($input['encrypted_user_data'])) {
                $user->encrypted_user_data  = $input['encrypted_user_data'];
                $user->last_sync_date       = $now->format('Y-m-d H:i:s');
            }
        }
        $user->save();

        $cycleDay = $input['cycle_day'];

        $encryptedData = $input['encData'];
        $key = substr(hash('sha256', env('SECRET_KEY')), 0, 32);
        $iv = substr(hash('sha256', env('VIKEY')), 0, 16);

        $decryptedData = openssl_decrypt(
            base64_decode($encryptedData),
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        $symptomIds = json_decode($decryptedData, true);
        
        if ($user->goal_type == 0) {
            if ($cycleDay >= 1 && $cycleDay <= 5) {
                $articles = Article::where('article_type', '1')->where('goal_type', 0)->orderBy('id', 'desc')->take(10)->get();
            } elseif ($cycleDay >= 6 && $cycleDay <= 13) {
                $articles = Article::where('article_type', '2')->where('goal_type', 0)->orderBy('id', 'desc')->take(10)->get();
            } elseif ($cycleDay >= 14 && $cycleDay <= 15) {
                $articles = Article::where('article_type', '3')->where('goal_type', 0)->orderBy('id', 'desc')->take(10)->get();
            } elseif ($cycleDay >= 16 && $cycleDay <= 28) {
                $articles = Article::where('article_type', '4')->where('goal_type', 0)->orderBy('id', 'desc')->take(10)->get();
            } elseif ($cycleDay > 28) {
                $articles = Article::whereIn('article_type', ['5', '6'])->orderBy('id', 'desc')->take(10)->get();
            } else {
                $articles = collect();
            }
        } else {
            $week = $input['week'] ?? null;
            $articles = Article::where('goal_type', 1)
                ->when($week !== null, function ($query) use ($week) {
                    $query->where('weeks', $week);
                })
                ->where(function ($query) use ($symptomIds) {
                    foreach ($symptomIds as $id) {
                        $query->orWhereRaw("JSON_CONTAINS(sub_symptoms_id, '\"{$id}\"')");
                    }
                })->orderBy('id', 'desc')->take(10)->get();
        }

        if ($user->goal_type == 1) {
            $week = $input['week'] ?? null;
            $pregnancyWeek = PregnancyWeek::where('weeks', $week)->get();
            $pregnancyWeekList = PregnancyWeekResource::collection($pregnancyWeek);

            $weekpregnancy = PregnancyDate::where('pregnancy_date', $week)->first();

            $weekpregnancyimage = getSingleMedia($weekpregnancy, 'pregnancy_date_image', null);
        }

        if ($cycleDay) {
            if ($cycleDay >= 1 && $cycleDay <= 5) {
                $symptomsList = Insights::whereIn('sub_symptoms_id', $symptomIds)
                    ->where('status', 1)->where('goal_type', $user->goal_type)->where('insights_type', '1')->orderBy('id', 'desc')->get();
            } elseif ($cycleDay >= 6 && $cycleDay <= 11) {
                $symptomsList = Insights::whereIn('sub_symptoms_id', $symptomIds)
                    ->where('status', 1)->where('goal_type', $user->goal_type)->where('insights_type', '2')->orderBy('id', 'desc')->get();
            } elseif ($cycleDay >= 12 && $cycleDay <= 16) {
                $symptomsList = Insights::whereIn('sub_symptoms_id', $symptomIds)
                    ->where('status', 1)->where('goal_type', $user->goal_type)->where('insights_type', '3')->orderBy('id', 'desc')->get();
            } elseif ($cycleDay >= 17 && $cycleDay <= 28) {
                $symptomsList = Insights::whereIn('sub_symptoms_id', $symptomIds)
                    ->where('status', 1)->where('goal_type', $user->goal_type)->where('insights_type', '4')->orderBy('id', 'desc')->get();
            } elseif ($cycleDay > 28) {
                $symptomsList = Insights::whereIn('sub_symptoms_id', $symptomIds)
                    ->where('status', 1)->where('goal_type', $user->goal_type)->whereIn('insights_type', ['5', '6'])->orderBy('id', 'desc')->get();
            }
        } else {
            $symptomsList = Insights::whereIn('sub_symptoms_id', $symptomIds)
                ->where('status', 1)
                ->where('goal_type', $user->goal_type)
                ->orderBy('id', 'desc')
                ->get();
        }

        // Daily Insights Logic:
        $dailyinsights = collect();
        if($symptomIds != []){
            $dailyinsightsQuery = DailyInsights::where(function ($query) use ($symptomIds) {
                foreach ($symptomIds as $id) {
                    $query->orWhereRaw("JSON_CONTAINS(sub_symptoms_id, ?)", ['"' . $id . '"']);
                }
            })->where('status', 1)
            ->where('goal_type', $user->goal_type);
            
            if ($cycleDay) {
                if ($cycleDay >= 1 && $cycleDay <= 5) {
                    $dailyinsightsQuery->where('phase', '1');
                } elseif ($cycleDay >= 6 && $cycleDay <= 11) {
                    $dailyinsightsQuery->where('phase', '2');
                } elseif ($cycleDay >= 12 && $cycleDay <= 16) {
                    $dailyinsightsQuery->where('phase', '3');
                } elseif ($cycleDay >= 17 && $cycleDay <= 28) {
                    $dailyinsightsQuery->where('phase', '4');
                } elseif ($cycleDay > 28) {
                    $dailyinsightsQuery->whereIn('phase', ['5', '6']);
                }
            }
            $dailyinsights = $dailyinsightsQuery->orderBy('id', 'desc')->get();
        }
        if($symptomIds != []){
            $symptomsArticle = Article::where('goal_type', $user->goal_type)
               ->where('article_type', 0)
               ->where(function($query) use ($symptomIds) {
                   foreach ($symptomIds as $id) {
                       $query->orWhereRaw("JSON_CONTAINS(sub_symptoms_id, '\"{$id}\"')");
                   }
               })->orderBy('id', 'desc')->get();
        }else{
            $symptomsArticle = collect();
        }


      $allArticles = $articles->merge($symptomsArticle)->unique('id')->take(10);

            $subscriptionAds = SettingData('adsconfig', 'adsconfig_access') ? true : false;

            $adsFeatureKeys = [
                'save_daily_logs',
                'edit_period_data',
                'download_image_data',
                'download_pdf_data',
                'download_doctor_report',
                'use_calculator_tools',
                'view_paid_article_ads'
            ];

            $subscriptionAdsFeatures = array_fill_keys($adsFeatureKeys, false);

            if ($subscriptionAds) {
                foreach ($adsFeatureKeys as $key) {
                    $subscriptionAdsFeatures[$key] = SettingData('adsaccess', $key) ? true : false;
                }
            }

        $adconfigation = [
            'facebookconfig_access' => SettingData('adsconfig', 'adsconfig_access') ? true : false,
            'android_rewarded_video' => SettingData('adsconfig', 'android_rewarded_video'),
            'ios_rewarded_video' => SettingData('adsconfig', 'ios_rewarded_video'),
        ];

        $cripChatId = SettingData('CRISP_CHAT_CONFIGURATION', 'CRISP_CHAT_CONFIGURATION_WEBSITE_ID');
        $cripChatEnabled = SettingData('CRISP_CHAT_CONFIGURATION', 'CRISP_CHAT_CONFIGURATION_ENABLE/DISABLE') ? true : false;
        $image = Setting::where('key', 'ICON_IMAGE')->where('type', 'CRISP_CHAT_CONFIGURATION')->first();
        $scripIcon = $image && $image->hasMedia('icon_image') ? $image->getFirstMediaUrl('icon_image') : null;
        $chatGpt = SettingData('CHATGPT', 'CHATGPT_API_KEY');
        $chatEnavled = SettingData('CHATGPT', 'CHATGPT_ENABLE/DISABLE') ? true : false;

        $personalisedInsights = collect();

        $query = PersonalisedInsights::query()->where('status', 1)->where('goal_type', $user->goal_type);

            $hasCycleDayFilter = false;
            if ($cycleDay >= 1 && $cycleDay <= 5) {
                $query->where('insights_type', '1');
                $hasCycleDayFilter = true;
            } elseif ($cycleDay >= 6 && $cycleDay <= 11) {
                $query->where('insights_type', '2');
                $hasCycleDayFilter = true;
            } elseif ($cycleDay >= 12 && $cycleDay <= 16) {
                $query->where('insights_type', '3');
                $hasCycleDayFilter = true;
            } elseif ($cycleDay >= 17 && $cycleDay <= 28) {
                $query->where('insights_type', '4');
                $hasCycleDayFilter = true;
            } elseif ($cycleDay > 28) {
                $query->whereIn('insights_type', ['5', '6']);
                $hasCycleDayFilter = true;
            }

            if ($hasCycleDayFilter) {
                if ($user->user_type === 'app_user') {
                    $query->where(function($q) use ($user) {
                        $q->where('view_all_users', 1)
                        ->orWhereJsonContains('users', (string)$user->id);
                    });
                } elseif ($user->user_type === 'anonymous_user') {
                    $query->where(function($q) use ($user) {
                        $q->where('view_all_anonymous_users', 1)
                        ->orWhereJsonContains('anonymous_user', (string)$user->id);
                    });
                }
            }

            if ($hasCycleDayFilter) {
                $personalisedInsights = $query->orderBy('id', 'desc')->get();
            }

            if ($personalisedInsights->isNotEmpty()) {
                $personalisedinsightsList = PersonalisedInsightsResource::collection($personalisedInsights);
            }

        $day = $cycleDay ?? 0;
        $cycles = CycleDates::where('day', $day)->where('status', 1)->get(); 
        $CycleDateDataResource = CaycleDaysResource::collection($cycles);

        $cycle_image = CycleDates::where('day', $day)->first();

        $cycledatesimage = getSingleMedia($cycle_image, 'thumbnail_image', null);

        if ($user->goal_type == 1) {
            $pregnancy_date = PregnancyDate::where('status', 1);
            $pregnancy_date = $pregnancy_date->orderBy('id', 'asc')->get();
            $items = PregnancyDateResource::collection($pregnancy_date);
        }

        $userTimezone = User::where('user_type', 'admin')->first()->timezone;
        $currentTime = Carbon::now()->setTimezone($userTimezone)->format('H:i:s');

        // Ask Expert
        $askexpert = AskExperts::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $askexpertList = AskexpertResource::collection($askexpert);

        $future_ask_expert = SettingData('FEATURE', 'FEATURE_ASK_EXPERT') ? true : false;
        $future_dummy_data = SettingData('FEATURE', 'FEATURE_ADD_DUMMY_DATA') ? true : false;
        
        $insights = InsightsResource::collection($symptomsList);
        $dailyinsights = $dailyinsights->unique('title');
        $dailyInsights = DailyInsightsResource::collection($dailyinsights);

        $response = [
            'user' => isset($user) ? new UserResource($user) : [],
            'insights' => $insights ?? null,
            'daily_insights' => $dailyInsights ?? null, 
            'article' => isset($allArticles) ? ArticleResource::collection($allArticles) : [],
            'cycleDay_image' => $cycledatesimage,
            'pregnancyweek_image' => $weekpregnancyimage ?? null,
            'cycleDateDay' => $CycleDateDataResource,
            'personalised_insights' => $personalisedinsightsList ?? [],
            'insights_pregnancy_week' => $pregnancyWeekList ?? [],
            'pregnancy_date' => $items ?? [],
            'ask_expert_list' => $askexpertList ?? [],
            'crisp_chat_website_id' => $cripChatId ?? null,
            'is_crisp_chat_enabled' => $cripChatEnabled,
            'crisp_chat_icon' => $scripIcon ? $scripIcon : null,
            'chat_gpt_key' => $chatGpt,
            'is_chat_gpt_enabled' => $chatEnavled ?? null,
            'future_ask_expert' => $future_ask_expert ?? null,
            'future_dummy_data' => $future_dummy_data ?? null,
            'facebookconfig' => $adconfigation ?? null,
            'subscriptionAdsFeatures' => $subscriptionAdsFeatures ?? null,
        ];

        return response()->json([
            'responseData' => $this->encryptData($response)
        ]);
        // return json_custom_response($response);
    }

    public function getSetting()
    {
        if (env('DATA_ENCRYPTION')) {
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json([
                    'responseData' => $this->encryptData(['error' => __('message.invalid_data')])
                ]);
            }
        } else {
            $decryptedData = request();
        }
        $input = $decryptedData;

        $setting = Setting::query();

        if (!empty($input['type'])) {
            $setting->when($input['type'], function ($q) use ($input) {
                return $q->where('type', 'LIKE', '%' . $input['type'] . '%');
            });
        }

        $setting = $setting->get();
        $response = [
            'data' => $setting,
        ];
        $response['subscription'] = SettingData('subscription', 'subscription_system') ?? '0';
        $currency_code = SettingData('CURRENCY', 'CURRENCY_CODE') ?? 'USD';
        $currency = currencyArray($currency_code);
        $response['currency_setting'] = [
            'name' => $currency['name'] ?? 'United States (US) dollar',
            'symbol' => $currency['symbol'] ?? '$',
            'code' => strtolower($currency['code']) ?? 'usd',
            'position' => SettingData('CURRENCY', 'CURRENCY_POSITION') ?? 'left',
        ];

        return response()->json([
            'responseData' => $this->encryptData($response)
        ]);
        // return json_custom_response($response);
    }

    public function basedInsight(Request $request)
    {
        $cycleDay = $request->cycle_day;
        $cycleDateData = CycleDateData::where('cycle_date_id', $cycleDay)->orderBy('id', 'desc')->get();
        $symptoms = Symptoms::where('status', 1)->has('subSymptoms', '>', 0)->has('subSymptoms.insights', '>', 0)->orderBy('id', 'desc')->get();
        $response = [
            'symptoms' => SubSymptomsResource::collection($symptoms),
            'cycyledatedata' => CycleDateDataResource::collection($cycleDateData),
        ];
        return json_custom_response($response);
    }

    public function doctorDashboard()
    {
        $auth_user = auth()->user();
        $health_expert = HealthExpert::where('user_id', $auth_user->id)->first();
        if (in_array($auth_user->user_type, ['anonymous_user', 'app_user'])) {
            $message = __('message.demo_permission_denied');

            return response()->json([
                'responseData' => $this->encryptData(['message' => $message])
            ]);
        }

        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::today()->addDay()->toDateString();
        //counts
        $healthexpert = HealthExpert::where('user_id', $auth_user->id)->first();

        $askexpertCount = AskExperts::where('status', 0)->count();
        $askexpertassignCount = AskExperts::where('expert_id', $auth_user->id)->count();

        // Expert
        $askexpertList = AskExperts::where('status', 0)->orderBy('created_at', 'desc')->take(3)->get();
        $askexpert = AskexpertResource::collection($askexpertList);

        //Ai
        $cripChatId = SettingData('CRISP_CHAT_CONFIGURATION', 'CRISP_CHAT_CONFIGURATION_WEBSITE_ID');
        $cripChatEnabled = SettingData('CRISP_CHAT_CONFIGURATION', 'CRISP_CHAT_CONFIGURATION_ENABLE/DISABLE') ? true : false;
        $chatGpt = SettingData('CHATGPT', 'CHATGPT_API_KEY');
        $image = Setting::where('key', 'ICON_IMAGE')->where('type', 'CRISP_CHAT_CONFIGURATION')->first();
        $scripIcon = $image && $image->hasMedia('icon_image') ? $image->getFirstMediaUrl('icon_image') : null;
        $chatEnavled = SettingData('CHATGPT', 'CHATGPT_ENABLE/DISABLE') ? true : false;

        $response = [
            'health_expert' => isset($health_expert) ? new HealthExpertResource($health_expert) : [],
            'new_qution' => $askexpertCount,
            'my_answers' => $askexpertassignCount,
            'askexpert_list' => $askexpert,
            'crisp_chat_website_id' => $cripChatId ?? null,
            'is_crisp_chat_enabled' => $cripChatEnabled,
            'crisp_chat_icon' => $scripIcon ? $scripIcon : null,
            'chat_gpt_key' => $chatGpt,
            'is_chat_gpt_enabled' => $chatEnavled ?? null,
        ];

        return response()->json([
            'responseData' => $this->encryptData($response)
        ]);
    }

}
