<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Support\Facades\App;
use App\Models\{Role,Category,Article, AskExperts, CalculatorTool, CommonQuestions, CustomTopic, CycleDateData, CycleDates, DailyInsights, DefaultKeyword, DefaultLogCategory, EducationalSession, Faq, HealthExpert, HealthExpertSession, ImageSection, Insights, LanguageDefaultList, LanguageList, PersonalisedInsights, PregnancyDate, PregnancyWeek, PushNotification, Screen, SectionData, SectionDataMain, Sections, Setting, subscriptions, SubSymptoms, Symptoms, Tags, VideosUpload};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Carbon\Carbon;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Nwidart\Modules\Facades\Module;
use Modules\Frontend\Models\WebsiteSection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /*
     * Dashboard Pages Routs
     */

     public function termofservice()
    {
        if( Module::has('Frontend') && Module::isEnabled('Frontend') ) {
            return view('frontend::frontend.pages.terms-condition');
        } else {
            return view('pages.termofservice');
        }
    }

    public function privacypolicySetting()
    {
        if( Module::has('Frontend') && Module::isEnabled('Frontend') ) {
            return view('frontend::frontend.pages.privacy-policy');
        } else {
            return view('pages.privacy_policy');
        }
    }

    public function index(Request $request)
    {
        $auth_user = auth()->user();
        $assets = ['dashboard'];

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;


        $userQuery = User::query();
        if ($start && $end) {
            $userQuery->whereBetween('created_at', [$start, $end]);
        }
        $users = $userQuery->get();
        
        $totalActiveUsers = $users->where('status', 'active')->count();
        $totalAppUsers = $users->where('user_type', 'app_user')->count();
        $totalAnonymousUsers = $users->where('user_type', 'anonymous_user')->count();
        $totalHealthExperts = $users->where('user_type', 'doctor')->count();
        $totalUsersCount = $users->count();
        $convertedUsersCount = $users->whereNotNull('conversion_date')->count();
        $userConversionRatio = $totalUsersCount > 0
        ? round(($convertedUsersCount / $totalUsersCount) * 100, 2)
        : 0;

        $data['dashboard'] = [
            'total_articles' => Article::count(),
            'user_convertion_ratio' => $userConversionRatio."%",
            'total_active_users' => $totalActiveUsers,
            'total_app_users' => $totalAppUsers,
            'total_anonymous_users' => $totalAnonymousUsers,
            'total_health_experts' => $totalHealthExperts,
        ];

        $latestappUsers = User::where(['status' => 'active', 'user_type' => 'app_user']);
        $latestannonymousUsers = User::where(['status' => 'active', 'user_type' => 'anonymous_user']);
        if ($start && $end) {
            $latestappUsers->whereBetween('created_at', [$start, $end]);
            $latestannonymousUsers->whereBetween('created_at', [$start, $end]);
        }
        $data['app_users'] = $latestappUsers->latest()->take(5)->get();
        $data['annonymous_users'] = $latestannonymousUsers->latest()->take(5)->get();

        if( $auth_user->hasRole('doctor') ) {
            Auth::logout();
            return redirect()->route('auth.login')->withErrors(['email' => __('message.invalid_user')]);
        }
        if($request->ajax()){
            return response()->json(['data' => $data]);
        }
        return view('dashboards.admin-dashboard',compact('data', 'auth_user', 'assets'));
    }

   public function dashboardCharts(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        $assets = ['dashboard'];
        $data = [];
        $userTypes = ['app_user', 'anonymous_user'];

        // Goal Type Chart
        $data['goal_type_chart'] = [];
        foreach ($userTypes as $type) {
            $goalQuery = User::query()->where('user_type', $type);
            if ($start && $end) {
                $goalQuery->whereBetween('created_at', [$start, $end]);
            }

            $goalTypeCounts = $goalQuery->selectRaw('goal_type, COUNT(*) as count')
                ->groupBy('goal_type')->pluck('count', 'goal_type')->toArray();

            $goalType0 = $goalTypeCounts[0] ?? 0;
            $goalType1 = $goalTypeCounts[1] ?? 0;
            $goalTotal = $goalType0 + $goalType1;

            $data['goal_type_chart'][$type] = [
                'labels' => [__("message.track_cycle"), __("message.track_pragnancy")],
                'data' => [
                    $goalTotal ? round(($goalType0 / $goalTotal) * 100, 2) : 0,
                    $goalTotal ? round(($goalType1 / $goalTotal) * 100, 2) : 0
                ]
            ];
        }

        // Device Platform Chart
        $platformQuery = User::query();
        if ($start && $end) {
            $platformQuery->whereBetween('created_at', [$start, $end]);
        }
        $platformCounts = $platformQuery->selectRaw('app_source, COUNT(*) as count')->groupBy('app_source')->pluck('count', 'app_source')->toArray();

        $playStore = $platformCounts['Play Store'] ?? 0;
        $appStore = $platformCounts['App Store'] ?? 0;
        $platformTotal = $playStore + $appStore;

        $data['device_platform_chart'] = [
            'labels' => ['Play Store', 'App Store'],
            'data' => [
                $platformTotal ? round(($playStore / $platformTotal) * 100, 2) : 0,
                $platformTotal ? round(($appStore / $platformTotal) * 100, 2) : 0
            ]
        ];

        // User Type Chart
        $typeQuery = User::query();
        if ($start && $end) {
            $typeQuery->whereBetween('created_at', [$start, $end]);
        }
        $typeCounts = $typeQuery->selectRaw('user_type, COUNT(*) as count')->groupBy('user_type')->pluck('count', 'user_type')->toArray();

        $appUser = $typeCounts['app_user'] ?? 0;
        $anonymousUser = $typeCounts['anonymous_user'] ?? 0;
        $doctorUser = $typeCounts['doctor'] ?? 0;
        $userTotal = $appUser + $anonymousUser + $doctorUser;

        $data['user_type_chart'] = [
            'labels' => ['App User', 'Anonymous User', 'Doctor'],
            'data' => [
                $userTotal ? round(($appUser / $userTotal) * 100, 2) : 0,
                $userTotal ? round(($anonymousUser / $userTotal) * 100, 2) : 0,
                $userTotal ? round(($doctorUser / $userTotal) * 100, 2) : 0
            ]
        ];

        // Region Chart
        $data['user_region_chart'] = ['country' => [], 'city' => []];
        foreach (['country_name' => 'country', 'city' => 'city'] as $field => $type) {
            $labels = [];
            $seriesData = [];

            foreach ($userTypes as $userType) {
                $regionQuery = User::query()->where('user_type', $userType);
                if ($start && $end) {
                    $regionQuery->whereBetween('created_at', [$start, $end]);
                }

                $regionQuery = $regionQuery->selectRaw("$field, COUNT(*) as count")
                    ->whereNotNull($field)
                    ->where($field, '!=', '')
                    ->groupBy($field)
                    ->orderByDesc('count')
                    ->get();

                $regionList = $regionQuery->pluck($field)->toArray();
                $labels = array_unique(array_merge($labels, $regionList));
                $seriesData[$userType] = $regionQuery->pluck('count', $field)->toArray();
            }

            // Normalize data: fill missing with 0
            $finalSeries = [];
            foreach ($userTypes as $userType) {
                $finalSeries[$userType] = [];
                foreach ($labels as $label) {
                    $finalSeries[$userType][] = $seriesData[$userType][$label] ?? 0;
                }
            }

            $data['user_region_chart'][$type] = [
                'labels' => array_values($labels),
                'series' => $finalSeries
            ];
        }

        // Ask Expert Chart
        $askQuery = AskExperts::query();
        if ($start && $end) {
            $askQuery->whereBetween('created_at', [$start, $end]);
        }

        $expertStatusCounts = $askQuery->selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status')->toArray();

        $data['ask_expert_chart'] = [
            'labels' => ['Pending', 'Assigned', 'Closed'],
            'data' => [
                $expertStatusCounts[0] ?? 0,
                $expertStatusCounts[1] ?? 0,
                $expertStatusCounts[2] ?? 0
            ]
        ];

        // Age Chart
        $ageRanges = [
            '18-24' => [18, 24],
            '25-34' => [25, 34],
            '35-44' => [35, 44],
            '45-54' => [45, 54],
            '55+'   => [55, 150],
        ];

        $ageCounts = [];
        foreach ($ageRanges as $label => [$minAge, $maxAge]) {
            $ageQuery = User::query()->whereBetween('age', [$minAge, $maxAge]);

            if ($start && $end) {
                $ageQuery->whereBetween('created_at', [$start, $end]);
            }

            $ageCounts[$label] = $ageQuery->count();
        }

        $data['age_chart'] = [
            'labels' => array_keys($ageRanges),
            'data' => array_values($ageCounts)
        ];

        return response()->json(['data' => $data, 'assets' => $assets]);
    }

    public function changeLanguage($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }

    /*
     * Auth pages Routs
     */

    function authLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $otp_verification_status = config('constant.MAIL_SETTING.EMAIL_OTP_VERIFICATION');

            if ($user && $otp_verification_status == 'enable') {
                $otp = rand(100000, 999999);
                $user->otp = $otp;
                $user->save();

                Mail::to($user->email)->send(new SendOtpMail($otp));

                Auth::logout();

                return redirect()->route('auth.otp-form')->with('email', $user->email);
            }


            return redirect()->route('home');
        }

        return back()->withErrors(['email' => __('auth.failed')]);
    }

    public function showOtpForm(Request $request)
    {
        $email = session('email') ?? $request->get('email');
        if (!$email) {
            return redirect()->route('auth.login')->withErrors(['email' => __('message.session_expired')]);
        }

        return view('auth.otp_form', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|numeric'
        ]);

        $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

        if ($user) {
            $user->otp = null;
            $user->save();

            Auth::login($user);

            return redirect()->route('home');
        }

        return redirect()->route('auth.otp-form', ['email' => $request->email])->withErrors(['otp' => __('auth.otp')]);
    }
    function authRegister()
    {
        $assets = ['phone'];
        return view('auth.register',compact('assets'));
    }

    function authRecoverPassword()
    {
        return view('auth.forgot-password');
    }

    function authConfirmEmail()
    {
        return view('auth.verify-email');
    }

    function authLockScreen()
    {
        return view();
    }

    public function changeStatus(Request $request)
    {
        $type = $request->type;
        $message_form = "";
        $message = __('message.update_form',['form' => __('message.status')]);
        switch ($type) {
            case 'role':
                    $role = \App\Models\Role::find($request->id);
                    $role->status = $request->status;
                    $role->save();
            break;
            case 'user':
                $customer = User::find($request->id);
                $status = $request->status == 0 ? 'inactive' : 'active';
                $customer->status = $status;
                $customer->save();
            break;
            case 'health_experts':
                $health_experts = HealthExpert::find($request->id);
                $health_experts->is_access = 0;
                $health_experts->save();
                $message = __('message.access_disabled');
            break;
            default:
                    $message = 'error';
                break;
        }

        if($message_form != null){
            $message =  __('message.added_form',['form' => $message_form ]);
            if($request->status == 0){
                $message = __('message.remove_form',['form' => $message_form ]);
            }
        }

        return json_custom_response(['status' => true, 'message' => $message]);
    }

    public function getAjaxList(Request $request)
    {
        $items = array();
        $value = $request->q;
        $auth_user = authSession();
        switch ($request->type) {
            case 'permission':
                $items = \App\Models\Permission::select('id','name as text')->whereNull('parent_id');
                if($value != ''){
                    $items->where('name', 'LIKE', $value.'%');
                }
                $items = $items->get();
                break;

            case 'timezone':
                $items = timeZoneList();
                foreach ($items as $k => $v) {
                    if($value !=''){
                        if (strpos($v, $value) !== false) {

                        } else {
                            unset($items[$k]);
                        }
                    }
                }
                $data = [];
                $i = 0;
                foreach ($items as $key => $row) {
                    $data[$i] = [
                        'id'    => $key,
                        'text'  => $row,
                    ];
                    $i++;
                }
                $items = $data ;
                break;
            case 'roles':
                $items = Role::where('status',1)->whereNotIn('name', ['admin']);
                    if($value != ''){
                        $items->where('name', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items;
                    break;
            case 'get_category':
                $items = Category::select('id','name as text')->where('status',1);
                    if($value != ''){
                        $items->where('name', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'get_category_by_goal_type':
                $category_data = Category::where('goal_type', request('goal_type'))->where('status',1)->get();
                $items = $category_data->map(function($item){
                    return [
                        'id'    => $item->id,
                        'text'  => $item->name,
                    ];
                });
                break;
            case 'symptoms_category':
                $items = Symptoms::select('id','title as text')->where('status',1);
                    if($value != ''){
                        $items->where('title', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'sub_symptoms_category':
                $items = SubSymptoms::select('id','title as text')->where('status',1);
                    if($value != ''){
                        $items->where('title', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items->get();
                    break;

            case 'get_symptoms_category':
                $sub_symptoms = SubSymptoms::where('symptoms_id',request('symptoms_id'))->get();
                $items = $sub_symptoms->map(function($item){
                    return [
                        'id'    => $item->id,
                        'text'  => $item->title,
                    ];
                });
                break;
            case 'video_data':
                $items = VideosUpload::select('id','title as text')->where('status',1);
                if($value != ''){
                    $items->where('title', 'LIKE', '%'.$value.'%');
                }
                $items = $items->get();
                break;

            case 'get_article':
                    $items = Article::select('id','name as text')->where('status',1);
                        if($value != ''){
                            $items->where('name', 'LIKE', '%'.$value.'%');
                        }
                        $items = $items->get();
                        break;
            case 'get_health_expert':
                $query = HealthExpert::with('users')
                    ->whereHas('users', function ($query) use ($value) {
                        $query->where('status', 'active');
                        if (!empty($value)) {
                            $query->where('display_name', 'LIKE', '%' . $value . '%');
                        }
                    });

                    if ($request->has('width')) {
                        # code...
                        if (request('width') == 'expert_session') {
                            $expert_session_ids = HealthExpertSession::get()->pluck('health_expert_id');
                            $query = $query->whereNotIn('id',$expert_session_ids);
                        }
                    }

                $items = $query->get()->map(function ($healthExpert) {
                    return [
                        'id' => $healthExpert->id,
                        'text' => optional($healthExpert->users)->display_name,
                    ];
                });
                break;
            case 'tags':
                $items = Tags::select('id','name as text');
                    if($value != ''){
                        $items->where('name', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'get_users':
                $items = User::where('goal_type',request('goal_type'))->whereNotNull('goal_type')->whereNotIn('user_type',['admin'])->select('id','display_name as text');
                    if($value != ''){
                        $items->where('display_name', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items->get();
                    break;
            case 'get_subscription_users':
                $items = subscriptions::with('users')->select('user_id')->groupBy('user_id')->get()
                    ->map(function ($item) {
                        return [
                            'id'   => $item->user_id,
                            'text' => optional($item->users)->display_name ?? '-'
                        ];
                    })
                ->values();
                  $items = $items;
                break;
            case 'get_currencies':
                $items = Subscriptions::whereNotNull('currency')->distinct()->pluck('currency')
                    ->map(fn($c) => ['id' => $c, 'text' => $c])
                    ->values();
                break;
            case 'get_store':
                $items = Subscriptions::whereNotNull('store')->distinct()->pluck('store')
                    ->map(fn($c) => ['id' => $c, 'text' => $c])
                    ->values();
                break;

            case 'screen':
                    $items = Screen::select('screen_id', 'screen_name as text');
                    if($value != ''){
                        $items->where('screenName', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items->get()->map(function ($screen_id) {
                        return ['id' => $screen_id->screen_id, 'text' => $screen_id->text];
                    });
                    $items = $items;
                    break;
            case 'language-list-data':
                    $languageId = $request->id;
                    $items = LanguageDefaultList::where('id', $languageId);
                    $items = $items->first();
                    break;
            case 'languagelist':
                    $data = LanguageList::pluck('language_id')->toArray();
                    $items = LanguageDefaultList::whereNotIn('id',$data)->select('id','languageName as text');
                        if($value != ''){
                            $items->where('languageName', 'LIKE', '%'.$value.'%');
                        }
                        $items = $items->get();
                    break;
            case 'defaultkeyword':
                    $items = DefaultKeyword::select('id','keyword_name as text');
                        if($value != ''){
                            $items->where('keyword_name', 'LIKE', '%'.$value.'%');
                        }
                    $items = $items->get();
                    break;
            case 'languagetable':
                    $items = LanguageList::select('id','language_name as text')->where('status', 1);
                        if($value != ''){
                            $items->where('language_name', 'LIKE', '%'.$value.'%');
                        }
                    $items = $items->get();
                    break;
            case 'pregnancy_week':
                    $data = [];
                    for($i=1; $i <= 50 ; $i++) {
                        $data[] = [
                            'id' => $i,
                            'text' => $i.' '.__('message.week'),
                        ];
                    }
                    $items = $data;
                    break;
            case 'cycle_days':
                    $data = [];
                    for($i=0; $i <= 50 ; $i++) {
                        $data[] = [
                            'id' => $i,
                            'text' => $i,
                        ];
                    }
                    $items = $data;
                    break;
            case 'pregnancy_date':
                $pregnancyDates = PregnancyDate::pluck('pregnancy_date')->toArray();
                $data = [];
                for ($i = 1; $i <= 42; $i++) {
                    if (!in_array($i, $pregnancyDates)) {
                        $data[] = [
                            'id' => $i,
                            'text' => $i . ' ' . __('message.week'),
                        ];
                    }
                }
                $items = $data;
                break;
            case 'experince_health_expert':
                $data = [];
                for ($i = 1; $i <= 50; $i++) {
                    $data[] = [
                        'id' => $i,
                        'text' =>(string) $i,
                    ];
                }

                $items = $data;
                break;


            case 'get_category_section_main_section_data':
                $category_id = request('secation_data_main_category_id');
                $category = Category::find($category_id);
                $items = Category::where('goal_type',$category->goal_type)->select('id','name as text')->where('status',1);
                    if($value != ''){
                        $items->where('name', 'LIKE', '%'.$value.'%');
                    }
                    $items = $items->get();
                    break;
            default :
                break;
        }
        return response()->json(['status' => true, 'results' => $items]);
    }

    public function removeFile(Request $request)
    {
        $type = $request->type;
        $data = null;

        switch ($type) {
            case 'story_image':
                $data = Media::find($request->id);
                $data->delete();
                $data = null;
                $type = $request->id;
                $message = __('message.msg_removed',[ 'name' => __('message.story_image')]);
                break;
            case 'category_thumbnail_image':
                $data = Category::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.category')]);
                break;

            case 'section_image':
                $data = WebsiteSection::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('frontend::message.section')]);
                break;

            case 'header_image':
                $data = Category::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.header_image')]);
                break;

            case 'article_image':
                $data = Article::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.article')]);
                break;

            case 'cycle_dates_thumbnail_image':
                $data = CycleDates::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.thumbnail_image')]);
                break;

            case 'cycle_date_data_text_message_image':
                $data = CycleDateData::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.cycle_date_data_text_message_image')]);
                break;

            case 'cycle_date_data_que_ans_image_' . $request->id:
                $data = CycleDateData::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.cycle_date_data_que_ans_image_')]);
                break;

            case 'health_experts_image':
                $data = HealthExpert::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.health_experts_image')]);
                break;

            case 'image_section_thumbnail_image':
                $data = ImageSection::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.image_section') .' '. __('message.thumbnail_image')]);
                break;

            case 'thumbnail_image':
                $data = Insights::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.insights_thumbnail_image')]);
                break;

            case 'story_image':
                $data = Insights::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.story_image')]);
                break;

            case 'insights_video':
                $data = Insights::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.insights_video')]);
                break;

            case 'info_section_image':
                $data = Sections::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.info_section_image')]);
                break;

            case 'section_data_image':
                $data = SectionData::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.section_data_image')]);
                break;

            case 'section_data_story_image':
                $data = SectionData::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.section_data_story_image')]);
                break;

            case 'section_data_video':
                $data = SectionData::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.section_data_video')]);
                break;

            case 'section_data_podcast':
                $data = SectionData::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.section_data_podcast')]);
                break;

            case 'sub_symptom_icon':
                $data = SubSymptoms::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.sub_symptom_icon')]);
                break;

            case 'pregnancy_date_image':
                $data = PregnancyDate::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.pregnancy_date')]);
                break;
            case 'image_section_thumbnail_image':
                $data = ImageSection::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.image_section')]);
                break;
            case 'videos_upload':
                $data = VideosUpload::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.upload_videos')]);
                break;
            case 'calculator_thumbnail_image':
                $data = CalculatorTool::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.calculator_tool')]);
                break;
            case 'log_category_image':
                $data = DefaultLogCategory::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.default_log_category')]);
                break;
            case 'video_image_video':
                $data = Insights::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.video')]);
                break;
            case 'image_video_image':
                $data = Insights::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.image')]);
                break;
            case 'upload_video_thumbnail_image':
                $data = VideosUpload::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.image')]);
                break;
            case 'icon_image':
                $data = Media::find($request->id);
                $data->delete();
                $data = null;
                $type = $request->id;
                $message = __('message.msg_removed',[ 'name' => __('message.icon_image')]);
                break;
            case 'subscription_image':
                $data = Media::find($request->id);
                $data->delete();
                $data = null;
                $type = $request->id;
                $message = __('message.msg_removed',[ 'name' => __('message.subscription_image')]);
                break;

            default:
                $data = AppSetting::find($request->id);
                $message = __('message.msg_removed',[ 'name' => __('message.image')]);
            break;
        }
        if($data != null){
            $data->clearMediaCollection($type);
        }
        $response = [
                'status' => true,
                'id' => $request->id,
                'image' => getSingleMedia($data,$type),
                'preview' => $type."_preview",
                'message' => $message
        ];
        return json_custom_response($response);
    }
    public function destroySelected(Request $request) {
            $checked_ids = $request->datatable_checked_ids;
            $types = $request->datatable_button_title;
            $data = null;
            $status = true;

            switch ($types) {
                case 'users-checked':
                    foreach ($checked_ids as $id) {
                        $user = user::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.app_user')]);
                    }
                    break;
                case 'anonymous-checked':
                    foreach ($checked_ids as $id) {
                        $user = user::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.anonymous_user')]);
                    }
                    break;
                case 'customtopic-checked':
                    foreach ($checked_ids as $id) {
                        $user = CustomTopic::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.custom_topic')]);
                    }
                    break;
                case 'dailyinsight-checked':
                    foreach ($checked_ids as $id) {
                        $user = DailyInsights::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.daily_insight')]);
                    }
                    break;
                case 'pushnotification-checked':
                    PushNotification::whereIn('id', $checked_ids)->delete();
                    $message = __('message.delete_form', ['form' => __('message.pushnotification')]);
                    break;
                case 'subadmin-checked':
                    foreach ($checked_ids as $id) {
                        $user = user::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.sub_admin')]);
                    }
                    break;
                case 'category-checked':
                    foreach ($checked_ids as $id) {
                        $user = Category::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.category')]);
                    }
                    break;
                case 'askexpert-checked':
                    foreach ($checked_ids as $id) {
                        $user = AskExperts::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.askexpert')]);
                    }
                    break;
                case 'article-checked':
                    foreach ($checked_ids as $id) {
                        $user = Article::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.article')]);
                    }
                    break;
                case 'tags-checked':
                    foreach ($checked_ids as $id) {
                        $user = Tags::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.tags')]);
                    }
                case 'pregnancy-date-checked':
                    foreach ($checked_ids as $id) {
                        $user = PregnancyDate::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.pregnancy_date')]);
                    }
                    break;
                case 'pregnancy-week-checked':
                    foreach ($checked_ids as $id) {
                        $user = PregnancyWeek::withTrashed()->where('id', $id)->first();
                        if ($user) {
                           if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.pregnancy_date')]);
                    }
                    break;
                case 'image-section-checked':
                    foreach ($checked_ids as $id) {
                        $user = ImageSection::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.image_section')]);
                    }
                    break;
                case 'common-que-checked':
                    foreach ($checked_ids as $id) {
                        $user = CommonQuestions::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.common_que_ans')]);
                    }
                    break;
                case 'cycle-day-checked':
                    foreach ($checked_ids as $id) {
                        $user = CycleDates::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.cycle_dates')]);
                    }
                    break;
                case 'health-experts-checked':
                    foreach ($checked_ids as $id) {
                        $user = HealthExpert::withTrashed()->where('id', $id)->first();
                        if ($user) {
                           if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.health_experts')]);
                    }
                    break;
                case 'insights-checked':
                    foreach ($checked_ids as $id) {
                        $user = Insights::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.insights')]);
                    }
                    break;
                case 'personalinsights-checked':
                    foreach ($checked_ids as $id) {
                        $user = PersonalisedInsights::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.personalinsights')]);
                    }
                    break;
                case 'sections-data-checked':
                    foreach ($checked_ids as $id) {
                        $sectionData = SectionData::withTrashed()->where('id', $id)->first();
                        if ($sectionData) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($sectionData->deleted_at != null) {
                                $sectionData->forceDelete();
                            } else {
                                $sectionData->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.section_data')]);
                    }
                    break;
                case 'sections-data-main-checked':
                    foreach ($checked_ids as $id) {
                        $user = SectionDataMain::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.section_data_main')]);
                    }
                    break;
                case 'sections-checked':
                    foreach ($checked_ids as $id) {
                        $user = Sections::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.sections')]);
                    }
                    break;
                case 'sub-symptoms-checked':
                    foreach ($checked_ids as $id) {
                        $user = SubSymptoms::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.sub_symptoms')]);
                    }
                    break;
                case 'symptoms-checked':
                    foreach ($checked_ids as $id) {
                        $user = Symptoms::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.symptoms')]);
                    }
                    break;
                case 'video-upload-checked':
                    foreach ($checked_ids as $id) {
                        $user = VideosUpload::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.upload_videos')]);
                    }
                    break;
                case 'faq-checked':
                    foreach ($checked_ids as $id) {
                        $user = Faq::withTrashed()->where('id', $id)->first();
                        if ($user) {
                            if (auth()->user()->hasRole('super_admin')) {
                                $message = __('message.demo_permission_denied');
                                if (request()->ajax()) {
                                    return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                                }
                            }
                            if ($user->deleted_at != null) {
                                $user->forceDelete();
                            } else {
                                $user->delete();
                            }
                        }
                        $message = __('message.delete_form', ['form' => __('message.faq')]);
                    }
                    break;

                default:
                    $status  =  false;
                    $message =  false;
                break;
            }

            $response = [ 'success' => $status, 'message' => $message ];
            return json_custom_response($response);
    }
}
