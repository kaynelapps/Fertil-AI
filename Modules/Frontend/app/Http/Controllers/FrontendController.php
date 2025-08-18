<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\CalculatorTool;
use App\Models\Rating;
use App\Models\Setting;
use App\Models\Tags;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Modules\Frontend\Models\FrontendData;
use Modules\Frontend\Http\Requests\WebSiteSettingRequest;
use Modules\Frontend\Http\Requests\FrontendSettingRequest;
use Modules\Frontend\Models\WebsiteSection;

class FrontendController extends Controller
{
    
    public function websiteSettingForm($type)
    {
        $data = config('frontend.constant.'.$type);
        $title = str_replace('-','_',$type);
        $pageTitle =  __('frontend::message.'.$title );

        if ( empty($data) || $data == null ) {
            return redirect()->back();
        }

        foreach ($data as $key => $val) {
            if ($type == 'section') {
                $frontend_data = FrontendData::where('id',request('frontend_id'));
                if( in_array( $key, ['image'])) {
                    $data[$key] = $frontend_data->first();
                } else {
                    $data[$key] = $frontend_data->pluck($key)->first();
                }
            }else{
                if( in_array( $key, ['image','logo_image','playstore_image','appstore_image'])) {
                    $data[$key] = Setting::where('type', $type)->where('key',$key)->first();
                } else {
                    $data[$key] = Setting::where('type', $type)->where('key',$key)->pluck('value')->first();
                }
            }
        }

        if (request()->ajax()) {
            $sub_type = request('sub_type');
            $frontend_id = request('frontend_id');
            $view = view('frontend::frontend.websitesection.section', compact('data', 'pageTitle', 'type','sub_type','frontend_id'))->render();
            return response()->json([ 'data' => $view, 'status' => true ]);
        }
        
        return view('frontend::frontend.websitesection.form', compact('data', 'pageTitle', 'type'));
    }

    public function websiteSettingUpdate(WebSiteSettingRequest $request, $type)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }
        $data = $request->all();

        foreach(config('frontend.constant.'.$type) as $key => $val){
            $input = [
                'type'  => $type,
                'key'   => $key,
                'value' => $data[$key] ?? null,
            ];
            $result = Setting::updateOrCreate(['key' => $key, 'type' => $type],$input);

            if( in_array($key, ['image','logo_image','playstore_image','appstore_image',] ) ) {
                uploadMediaFile($result,$request->$key, $key);
            }
        }
        $title = str_replace('-','_',$type);


        return redirect()->back()->withSuccess(__('message.save_form', ['form' => __('frontend::message.'.$title)]));
    }

    public function storeFrontendData(FrontendSettingRequest $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }
        $data = $request->all();
        $id = request('frontend_id');
        $result = FrontendData::updateOrCreate(['id' => $id],$data);

        storeMediaFile($result,$request->image, $request->type); 
        $title = str_replace('-','_',$request->type);
        $count_data = FrontendData::where('type',$result->type)->orderBy('id', 'desc')->count();
        $message = __('message.save_form', ['form' => __('frontend::message.'.$title)]);
        return response()->json(['status' => true, 'count_data' => $count_data, 'frontend_id' => $id, 'type' => $request->type, 'event' => 'norefresh', 'message' => $message]);
    }
    
    public function getFrontendDatatList(Request $request)
    {
        $type = request('type');
        $data = FrontendData::where('type',$type)->orderBy('id', 'desc')->get();
        $title = str_replace('-','_',$type);
        $count_data = FrontendData::where('type',$type)->orderBy('id', 'desc')->count();
        $view = view('frontend::frontend.websitesection.frontend-data-list',compact('type', 'data','title'))->render();
        return response()->json([ 'data' => $view, 'status' => true ,'count_data' => $count_data, 'type' => $type]);
    }

    public function frontendDataDestroy(Request $request)
    {
        if (auth()->user()->hasRole('super_admin')) {
            $message = __('message.demo_permission_denied');
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
            }
            return redirect()->back()->withErrors($message);
        }
        $frontend_data = FrontendData::find($request->id);

        $title = str_replace('-','_',$frontend_data->type);
        $status = 'errors';
        $message = __('message.not_found_entry', ['name' => __('frontend::message.'.$title)]);
        if($frontend_data != '') {
            $frontend_data->delete();
            $status = 'success';
            $message = __('message.delete_form', ['form' => __('frontend::message.'.$title)]);
        }

        if(request()->ajax()) {
            return response()->json(['status' => true, 'message' => $message, 'type' => $frontend_data->type, 'event' => 'norefresh']);
        }

        return redirect()->back()->with($status,$message);
    }

    public function index()
    {
        $data['dummy_title']  = DummyData('dummy_title');
        $data['dummy_description'] = DummyData('dummy_description');

        $data['article'] = Article::where('status','1')->where('type','free')->orderBy('id','desc')->paginate(10);
        $data['app-info'] = [
            'title'       => SettingData('app-info', 'title') ?? $data['dummy_title'],
            'description' => SettingData('app-info', 'description') ?? $data['dummy_description'],
            'image'       => getSingleMediaSettingImage(getSettingFirstData('app-info','image'),'image','app-info'),
            'playstore_url' => [
                'url' => SettingData('app-info', 'playstore_url') ?? 'javascript:void(0)',
                'target' => SettingData('app-info', 'playstore_url') ? 'target="_blank"' : ''
            ],
            'appstore_url' => [
                'url' => SettingData('app-info', 'appstore_url') ?? 'javascript:void(0)',
                'target' => SettingData('app-info', 'appstore_url') ? 'target="_blank"' : ''
            ],
            'video_url'   => SettingData('app-info', 'video_url') ?? null,
            'playstore_image' => getSingleMediaSettingImage(getSettingFirstData('app-info','playstore_image'),'playstore_image'),
            'appstore_image'  => getSingleMediaSettingImage(getSettingFirstData('app-info','appstore_image'),'appstore_image'),
        ];

        $data['newsletter'] = [
            'title'    => SettingData('newsletter', 'title') ?? $data['dummy_title'],
        ];

        $data['user-review'] = [
            'title'       => SettingData('user-review', 'title') ?? $data['dummy_title'],
            'subtitle'    => SettingData('user-review', 'subtitle') ?? $data['dummy_description'],
        ];
        
        $data['rating'] = [
            'title'       => SettingData('rating', 'title') ?? $data['dummy_title'],
            'playstore_review' => SettingData('rating', 'playstore_review') ?? 'xx',
            'appstore_review' => SettingData('rating', 'appstore_review') ?? 'xx',
            'rating_star' => SettingData('rating', 'rating_star') ?? 'xx',
        ];

        $data['review'] =  FrontendData::where('type','user-review')->orderBy('id','desc')->get();
        
        $data['sections'] = WebsiteSection::with('websitesectiontitles')->get();
        return view('frontend::frontend.index',compact('data'));
    }

    public function articleList(Request $request)
    {
        $articles = Article::where('status', '1')->where('type','free')->orderBy('id', 'desc')->paginate(8);
        
        if ($request->ajax()) {
            return view('frontend::frontend.article-items', compact('articles'))->render();
        }

        return view('frontend::frontend.article-list', compact('articles'));
    }


    public function articleDetail(Request $request, $slug)
    {
        $data['article'] = Article::where('slug', $request->slug)->firstOrFail();
        $data['related_articles'] = Article::where('status', '1')->where('type','free')->where('id', '!=', $data['article']->id)->inRandomOrder()->take(8)->get();
        $selected_tags = [];
        if (isset($data['article']->tags_id)) {
            $selected_tags = Tags::whereIn('id', $data['article']->tags_id)->get();
        }
        return view('frontend::frontend.article-detail',compact('data','selected_tags'));
    }

    public function articlesByTag(Request $request, $slug)
    {
        $tag = Tags::where('slug', $slug)->firstOrFail();

        $articles = Article::where('status', '1')->where('type', 'free')->whereJsonContains('tags_id', (string) $tag->id)->orderBy('id', 'desc')->paginate(8);

        if ($request->ajax()) {
            return view('frontend::frontend.article-items', compact('articles'))->render();
        }

        return view('frontend::frontend.article-list', compact('articles', 'tag'));
    }

    public function calculator(Request $request,$slug)
    {
       
        $result = null;
        $data['calculator'] = CalculatorTool::where('slug',$slug)->where('status','1')->first();

        if ($data['calculator']) {
            $article = Article::find($data['calculator']->blog_link);

            $selected_tags = [];
            if ($article && isset($article->tags_id)) {
                $selected_tags = Tags::whereIn('id', $article->tags_id)->get();
            }
        
        }
        if ($request->isMethod('post')) {
            switch ($slug) {
                case 'pregnancy-test-calculator':

                    $lastPeriod = Carbon::parse($request->last_period);
                    $cycleLength = (int) $request->cycle_length;

                    $ovulationDayOffset = $cycleLength - 14;
                    $testDate = $lastPeriod->copy()->addDays($cycleLength);
                    $result = [
                        'testDate' => $testDate,
                    ];
                break;
                
    
                case 'ovulation-calculator':
                    $lastPeriod = Carbon::parse($request->last_period);
                    $cycleLength = $request->cycle_length;

                    $ovulationDate = $lastPeriod->copy()->addDays($cycleLength - 14);
                    $fertileStart = $ovulationDate->copy()->subDays(5);
                    $fertileEnd = $ovulationDate->copy()->addDay();

                    $result = [
                        'lastPeriod' => $lastPeriod,
                        'cycleLength' => $cycleLength,
                        'ovulationDate' => $ovulationDate,
                        'fertileStart' => $fertileStart,
                        'fertileEnd' => $fertileEnd,
                    ];
                break;

                case 'period-calculator':
                    $lastPeriod = Carbon::parse($request->input('last_period'));
                    $cycleLength = (int) $request->input('cycle_length');
                    $periodDuration = (int) $request->input('period_duration');
        
                    $nextPeriodStart = $lastPeriod->copy()->addDays($cycleLength);
                    $nextPeriodEnd = $nextPeriodStart->copy()->addDays($periodDuration);

                    $result = [
                        'next_period_start' => $nextPeriodStart,
                        'next_period_end' => $nextPeriodEnd,
                    ];
                    
                break;

                case 'implantation-calculator':  
                    $lastPeriod = Carbon::parse($request->input('last_period'));
                    $cycleLength = (int) $request->input('cycle_length');
                
                    $ovulationDate = $lastPeriod->copy()->addDays($cycleLength - 14);
                
                    $implantStart = $ovulationDate->copy()->addDays(6);
                    $implantEnd = $ovulationDate->copy()->addDays(10);
                
                    $result = [
                        'implant_start' => $implantStart,
                        'implant_end' => $implantEnd,
                    ];
                break;

                case 'pregnancy-due-date-calculator':
                    $request->validate([
                        'last_period' => 'required|date',
                    ]);
                
                    $lastPeriod = Carbon::parse($request->input('last_period'));
                    $dueDate = $lastPeriod->copy()->addDays(280);
                
                    $result = [
                        'due_date' => $dueDate,
                    ];
                break;

            }
        }
        return view('frontend::frontend.calculator.calculator', compact('result','data','selected_tags'));
    }
}
