<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DefaultLogCategory;
use App\Http\Resources\DefaultLogCategoryResource;

class DefaultLogCategoryController extends Controller
{
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
        
        $default_log_category = DefaultLogCategory::where('status',1);

        if(!empty($input['type'])){
            $default_log_category->when($input['type'], function ($q) use ($input) {
                return $q->where('type', 'LIKE', '%' . $input['type'] . '%');
            });
        };

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $default_log_category->count();
            }
        }

        $default_log_category = $default_log_category->orderBy('id', 'desc')->paginate($per_page);
        $items = DefaultLogCategoryResource::collection($default_log_category);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
}
