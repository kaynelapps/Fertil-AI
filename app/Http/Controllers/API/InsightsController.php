<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InsightsDataResource;
use Illuminate\Http\Request;
use App\Http\Resources\InsightsResource;
use App\Models\BookmarkInsights;
use App\Models\Insights;
use App\Traits\EncryptionTrait;
use Illuminate\Support\Facades\Validator;

class InsightsController extends Controller
{
    use EncryptionTrait;
    
    public function getList(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;
        
        $insights = Insights::where('status',1);

        if(!empty($input['title'])){
            $insights->when($input['title'], function ($q) use ($input) {
                return $q->where('title', 'LIKE', '%' . $input['title'] . '%');
            });
        }

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $insights->count();
            }
        }

        $insights = $insights->orderBy('title', 'asc')->paginate($per_page);

        $items = InsightsResource::collection($insights);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }

    public function getDetail(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        
        $input = $decryptedData;
        $auth_user = auth()->user();
        $sub_symptoms_id = $input['sub_symptoms_id']?? [];

        $insights = Insights::where('goal_type',$auth_user->goal_type)->whereIn('sub_symptoms_id',$sub_symptoms_id)->where('status',1);

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $insights->count();
            }
        }

        $insights = $insights->orderBy('title', 'asc')->paginate($per_page);
        $items = InsightsResource::collection($insights);

        $response = [
            'pagination' => json_pagination_response($items),
            'data' => $items
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }

    public function getInsightsData(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;;

        $user_goal_type = auth()->user()->goal_type;
        $insights = Insights::where('goal_type',$user_goal_type)->where('status',1);

        if(!empty($input['title'])){
            $insights->when($input['title'], function ($q) use ($input) {
                return $q->where('title', 'LIKE', '%' . $input['title'] . '%');
            });
        }
        // $insights->when(request('title'), function ($q) {
        //     return $q->where('title', 'LIKE', '%' . request('title') . '%');
        // });

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $insights->count();
            }
        }
        $insights = $insights->orderBy('title', 'asc')->paginate($per_page);

        $items = InsightsDataResource::collection($insights);

        $response = [
            'pagination'    => json_pagination_response($items),
            'category_list'          => $items,
        ];

        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }

    public function bookmarkInsights(Request $request)
    {
        $auth_user = auth()->user();
        if ($auth_user->hasRole('doctor')) {
            $message = __('message.demo_permission_denied');
            return json_message_response($message, 403);
        }

        $insights = Insights::where('id',$request->insight_id)->first();
        if (empty($insights)) {
            # code...
            $message = __('message.not_found_entry',['name' => __('message.insights')]);
            return response()->json(['message' => $message], 400);

        }
        $validator = Validator::make($request->all(), [
            'is_bookmark' => 'required|numeric|in:0,1',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
        
        $auth_user = auth()->user();
        $request['user_id'] = $auth_user->id;
        $request['insight_id'] = $request->insight_id;
        $request['is_bookmark'] = $request->is_bookmark;

        $bookmarksInsights = BookmarkInsights::updateOrCreate(['insight_id' => $request->insight_id,'user_id' => $auth_user->id],$request->all());
        if ($request->is_bookmark == "1") {
            $message = __('message.bookmarks_insights');
        } else {
            $message = __('message.remove_bookmarks_insights');
        }
        $response = [
            'message' => $message,
            'data' => $bookmarksInsights,
        ];
        return json_custom_response($response);
    }

    public function getBookmarkList(Request $request)
    {
        $auth_user = auth()->user();

        if ($auth_user->hasRole('doctor')) {
            $message = __('message.demo_permission_denied');
            return json_message_response($message, 403);
        }

        $bookmark_insight = BookmarkInsights::where('user_id', $auth_user->id)->where('is_bookmark', 1);

        $bookmark_insight->when(request('id'), function ($q) {
            return $q->where('id', 'LIKE', '%' . request('id') . '%');
        });

        $bookmark_insight_id = $bookmark_insight->get()->pluck('insight_id');
        $insights = Insights::whereIn('id',$bookmark_insight_id)->where('status',1);

        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page))
            {
                $per_page = $request->per_page;
            }
            if($request->per_page == -1 ){
                $per_page = $insights->count();
            }
        }

        $insights = $insights->orderBy('title', 'asc')->paginate($per_page);

        $items = InsightsResource::collection($insights);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        
        return json_custom_response($response);
    }
}
