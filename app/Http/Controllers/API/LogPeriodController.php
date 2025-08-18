<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogPeriodResource;
use App\Models\LogPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\EncryptionTrait;

class LogPeriodController extends Controller
{
    use EncryptionTrait;

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
        $request['user_id'] = $auth_user->id;
        $request['period_date'] = $input['period_date'] ? $input['period_date'] : [];
        foreach ($input['period_date'] as $value) {
             $date = Carbon::createFromFormat('Y-m-d', $value);
             if ($date->format('Y-m-d') == $value) {    
                 $column_array = ['period_date' => $value,'user_id' => $input['user_id']];
                 LogPeriod::updateOrCreate($column_array,[$column_array]);
             }else {
                return json_custom_response(['message' => 'The '.$value.' date is invalid.'], 400);
             }
        }
        $log_period_data = LogPeriod::whereIn('period_date',$input['period_date'])->get();
        $message = __('message.update_form',['form' => __('message.log_period') ]);
        $response = [
            'message' => $message,
            'data' => LogPeriodResource::collection($log_period_data),
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
        
        $log_period = LogPeriod::where('user_id',auth()->id());

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $log_period->count();
            }
        }

        $log_period = $log_period->orderBy('id', 'desc')->paginate($per_page);
        $items = LogPeriodResource::collection($log_period);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
}
