<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AskexpertResource;
use Illuminate\Http\Request;
use App\Models\AskExperts;
use App\Traits\EncryptionTrait;

class AskExpertsController extends Controller
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
        
        $askespert = AskExperts::getAskexpert();

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $askespert->count();
            }
        }

        $askespert = $askespert->orderBy('id', 'desc')->paginate($per_page);
        $items = AskexpertResource::collection($askespert);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
    }
    public function doctorAssignList(Request $request)
    {
        $auth = auth()->user();
        if(env('DATA_ENCRYPTION')){
            $decryptedData = json_decode($this->decryptData(request('requestData')), true);
            if (!is_array($decryptedData)) {
                return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
            }
        }else{
            $decryptedData = $request->all();
        }
        $input = $decryptedData;
        
        $askespert = AskExperts::where('expert_id',$auth->id);

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $askespert->count();
            }
        }

        $askespert = $askespert->orderBy('id', 'desc')->paginate($per_page);
        $items = AskexpertResource::collection($askespert);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
    }
}
