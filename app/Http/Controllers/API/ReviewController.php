<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Resources\ReviewResource;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $auth_user = auth()->user();
        if ($auth_user->hasRole('doctor')) {
            $message = __('message.demo_permission_denied');
            return json_message_response($message, 403);
        }

        $validator = Validator::make($request->all(), [
            'health_expert_id' => 'required|numeric|exists:health_experts,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        // dd($request->all());
        $request['user_id'] = $auth_user->id;
        $review = Review::create($request->all());                

        $items = new ReviewResource($review);
        $message = __('message.save_form',['form' => __('message.review') ]);
        $response = [
            'message'   => $message,
            'data'      => $items
        ];
        
        return json_custom_response($response);
    }
    public function getList(Request $request)
    {
        $auth_user = auth()->user();
        $review = Review::GetReview();
                
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page))
            {
                $per_page = $request->per_page;
            }
            if($request->per_page == -1 ){
                $per_page = $review->count();
            }
        }

        $review = $review->orderBy('id', 'desc')->paginate($per_page);

        $items = ReviewResource::collection($review);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items
        ];
        
        return json_custom_response($response);
    }
}
