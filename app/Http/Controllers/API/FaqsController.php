<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Http\Resources\FaqResource;
use App\Traits\EncryptionTrait;

class FaqsController extends Controller
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
        
        $items = Faq::getFaq()->where('status',1);

        $items->when(request('question'), function ($q) {
            return $q->where('question', 'LIKE', '%' . request('question') . '%');
        });

        $items->when(request('goal_type'), function ($q) {
            return $q->where('goal_type', request('goal_type'));
        });

        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page))
            {
                $per_page = $request->per_page;
            }
            if($request->per_page == -1 ){
                $per_page = $items->count();
            }
        }

        $items = $items->orderBy('id', 'desc')->paginate($per_page);
        $items = FaqResource::collection($items);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        // dd($response);
        return response()->json(['responseData' => $this->encryptData($response) ]);
    }
}
