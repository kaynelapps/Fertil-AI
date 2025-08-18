<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Symptoms;
use App\Http\Resources\SymptomsResource;
use App\Traits\EncryptionTrait;

class SymptomsController extends Controller
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
        
        $symptoms = Symptoms::SymptomsList()->where('status',1);


        if(!empty($input['title'])){
            $symptoms->when($input['title'], function ($q) use ($input) {
                return $q->where('title', 'LIKE', '%' . $input['title'] . '%');
            });
        }

        $symptoms = $symptoms->orderBy('title', 'asc')->get();

        $items = SymptomsResource::collection($symptoms);

        $response = ['data' => $items];
        
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
        
        $id = $input['id'];

        $symptoms = Symptoms::where('id',$id)->first();
        if(empty($symptoms))
        {
            $message = __('message.not_found_entry',['name' =>__('message.symptoms')]);
            
            return response()->json([
                'responseData' => $this->encryptData(['message' => $message])
            ]);
            // return json_message_response($message,400);   
        }

        $symptoms_detail = new SymptomsResource($symptoms);
        return response()->json(['responseData' => $this->encryptData(['data' => $symptoms_detail]) ]);
        // return json_custom_response($response);
    }
}
