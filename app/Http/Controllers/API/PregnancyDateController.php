<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PregnancyDate;
use App\Http\Resources\PregnancyDateResource;
use App\Http\Resources\PregnancyWeekResource;
use App\Models\PregnancyWeek;
use App\Traits\EncryptionTrait;

class PregnancyDateController extends Controller
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
        
        $pregnancy_date = PregnancyDate::where('status',1);

        if(!empty($input['title'])){
            $pregnancy_date->when($input['title'], function ($q) use ($input) {
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
                $per_page = $pregnancy_date->count();
            }
        }

        $pregnancy_date = $pregnancy_date->orderBy('id', 'asc')->get();
        $items = PregnancyDateResource::collection($pregnancy_date);

        $response = ['data' => $items ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }

    public function getPregnancy(Request $request)
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
        $pregnancyWeek = PregnancyWeek::where('weeks',$input['week'])->get();
        $pregnancyWeekList = PregnancyWeekResource::collection($pregnancyWeek); 

        return response()->json(['responseData' => $this->encryptData($pregnancyWeekList) ]);
    }
}
