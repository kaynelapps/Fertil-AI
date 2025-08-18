<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageSection;
use App\Http\Resources\ImageSectionResource;

class ImageSectionController extends Controller
{
    public function getList(Request $request)
    {
        $user_goal_type = auth()->user()->goal_type;
        $image_section = ImageSection::where('goal_type',$user_goal_type)->where('status',1);

        $image_section->when(request('title'), function ($q) {
            return $q->where('title', 'LIKE', '%' . request('title') . '%');
        });
                
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page))
            {
                $per_page = $request->per_page;
            }
            if($request->per_page == -1 ){
                $per_page = $image_section->count();
            }
        }

        $image_section = $image_section->orderBy('title', 'desc')->paginate($per_page);

        $items = ImageSectionResource::collection($image_section);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items
        ];
        
        return json_custom_response($response);
    }
}
