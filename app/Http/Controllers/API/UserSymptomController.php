<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserSymptomResource;
use App\Models\UserSymptom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserSymptomController extends Controller
{
    public function create(Request $request)
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
        $data = $input;

        $data['user_id'] = $auth_user->id;
        $current_date = $input['current_date'];
        $data['current_date'] =  $current_date ? $current_date : now()->toDateString();
        $data['user_data'] = $input['user_data'];

        $user_symptom = UserSymptom::create($data);
        $message = __('message.save_form',['form' => __('message.user_symptom') ]);
        $response = [
            'message' => $message,
            'data' => isset($user_symptom) ? new UserSymptomResource($user_symptom) : [],
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }

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
        
        $user_symptom = UserSymptom::where('user_id',auth()->id());

        // $user_symptom->when(request('current_date'), function ($q) {
        //     return $q->where('current_date', 'LIKE', '%' . request('current_date') . '%');
        // });
    
        if(!empty($input['current_date'])){
            $user_symptom->when($input['current_date'], function ($q) use ($input) {
                return $q->where('current_date', 'LIKE', '%' . $input['current_date'] . '%');
            });
        }
        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $user_symptom->count();
            }
        }

        $user_symptom = $user_symptom->orderBy('id', 'desc')->paginate($per_page);
        $items = UserSymptomResource::collection($user_symptom);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
}
