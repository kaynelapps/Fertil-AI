<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tags;
use App\Http\Resources\TagsResource;
use App\Traits\EncryptionTrait;

class TagsController extends Controller
{
    use EncryptionTrait;
    
    public function getList(Request $request)
    {
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => 'Invalid decrypted data']) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;
        
        $tags = Tags::query();

        if(!empty($input['name'])){
            $tags->when($input['name'], function ($q) use ($input) {
                return $q->where('name', 'LIKE', '%' . $input['name'] . '%');
            });
        }
             
        $per_page = config('constant.PER_PAGE_LIMIT');
       
            if(!empty($input['per_page'])){
                if(is_numeric($input['per_page']))
                {
                    $per_page = $input['per_page'];
                }
                if($input['per_page'] == -1 ){
                    $per_page = $tags->count();
                }
            }

        $tags = $tags->orderBy('id', 'asc')->paginate($per_page);
        $items = TagsResource::collection($tags);

        $response = [
            'status'        => true,
            'pagination'    => json_pagination_response($items),
            'data'          => $items
        ];
        

        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
}
