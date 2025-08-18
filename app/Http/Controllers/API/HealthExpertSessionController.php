<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HealthExpertSessionRequest;
use App\Http\Resources\HealthExpertSessionResource;
use App\Http\Resources\UserHealthExpertSessionResource;
use App\Models\HealthExpert;
use App\Models\HealthExpertSession;
use App\Traits\EncryptionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HealthExpertSessionController extends Controller
{
    use EncryptionTrait;
    
    public function index(Request $request)
    {
        $days = [
            1 => __('message.monday'),
            2 => __('message.tuesday'),
            3 => __('message.wednesday'),
            4 => __('message.thursday'),
            5 => __('message.friday'),
            6 => __('message.saturday'),
            7 => __('message.sunday'),
        ];

        $days_data = [];
        foreach ($days as $key => $value) {
            $days_data[] = [
                'day' => $key,
                'day_name' => substr($value, 0, 3),
            ];
        }

        return response()->json(['responseData' => $this->encryptData(['data' => $days_data]) ]);
        // return json_custom_response($days_data); 
    }

    public function create(HealthExpertSessionRequest $request)
    {
        $auth_user = auth()->user();
        if ($auth_user->user_type == ['anonymous_user','app_user']) {
            $message = __('message.demo_permission_denied');
            return response()->json(['responseData' => $this->encryptData(['message' => $message]) ]);
            // return json_message_response($message, 403);
        }

        if(request()->is('api/*')){
            if(env('DATA_ENCRYPTION')){
                $decryptedData = json_decode($this->decryptData(request('requestData')), true);
                if (!is_array($decryptedData)) {
                    return response()->json(['responseData' => $this->encryptData(['error' => __('message.invalid_data')]) ]);
                }
            }else{
                $decryptedData = $request->all();
            }
            
            $validatedData = Validator::make($decryptedData, [
                'week_days' => 'array|required',
                'week_days.*' => 'required|numeric|in:1,2,3,4,5,6,7',
                // 'morning_start_time' => ['required', 'date_format:H:i', new MultipleOfFiveMinutes()],
                'morning_start_time' => 'required|date_format:H:i',
                'morning_end_time' => 'required|date_format:H:i|after:morning_start_time',
                'evening_start_time' => 'required|date_format:H:i',
                'evening_end_time' => 'required|date_format:H:i|after:evening_start_time',
            ]);
        
            if ($validatedData->fails()) {
                return response()->json(['responseData' => $this->encryptData(['errors' => $validatedData->errors()]) ]);
            }
        
            $input = $decryptedData;      
        }
        
        $health_expert_id = optional($auth_user->health_expert)->id ?? null;
        $health_expert_session = HealthExpertSession::where('health_expert_id',$health_expert_id)->first();

        if (isset($health_expert_session)) {
            $health_expert_session->update($input);
        }else{
            $input['health_expert_id'] = $health_expert_id;
            $health_expert_session = HealthExpertSession::create($input);
        }

        $message = __('message.save_form',['form' => __('message.health_expert_session') ]);
        $response = [
            'message' => $message,
            'data' => isset($health_expert_session) ? new HealthExpertSessionResource($health_expert_session) : [],
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);

        // return json_custom_response($response);
    }

    public function doctorSessionList(Request $request)
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
        if ($auth_user->hasRole('doctor')) {
            # code...
            $message = __('message.demo_permission_denied');
            return json_message_response($message, 403);
        }

        $health_expert_session = HealthExpert::Active();

        // $health_expert_session->when(request('doctor_id'), function ($q) {
        //     return $q->where('id', 'LIKE', '%' . request('doctor_id') . '%');
        // });
        if(!empty($input['id'])){
            $health_expert_session->when($input['doctor_id'], function ($q) use ($input) {
                return $q->where('id', 'LIKE', '%' . $input['doctor_id'] . '%');
            });
        };

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $health_expert_session->count();
            }
        }

        $health_expert_session = $health_expert_session->orderBy('id', 'asc')->paginate($per_page);

        $items = UserHealthExpertSessionResource::collection($health_expert_session);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }

    public function healthExpertSessionList(Request $request)
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

        $Health_expert_session = HealthExpertSession::GetHealthExpertSession();

        // $Health_expert_session->when(request('id'), function ($q) {
        //     return $q->where('id', 'LIKE', '%' . request('id') . '%');
        // });
        if(!empty($input['id'])){
            $Health_expert_session->when($input['id'], function ($q) use ($input) {
                return $q->where('id', 'LIKE', '%' . $input['id'] . '%');
            });
        }

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $Health_expert_session->count();
            }
        }

        $Health_expert_session = $Health_expert_session->orderBy('id', 'desc')->paginate($per_page);

        $items = HealthExpertSessionResource::collection($Health_expert_session);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        // dd($response);
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
    public function delete(Request $request)
    {
        $auth_user = auth()->user();
        if ($auth_user->user_type == ['anonymous_user','app_user']) {
            $message = __('message.demo_permission_denied');
            return response()->json(['responseData' => $this->encryptData(['status' => false,'message' => $message ]) ]);
        }
        
        $health_expert_id = optional($auth_user->health_expert)->id;
        $health_expert_session = HealthExpertSession::where('health_expert_id',$health_expert_id)->first();

        if (empty($health_expert_session)) {
            $message = __('message.not_found_entry',['name' =>__('message.health_expert_session')]);
            return response()->json(['responseData' => $this->encryptData(['status' => false,'message' => $message]) ]);
            // return json_message_response($message,400);
        }

        $message = __('message.delete_form', ['form' => __('message.health_expert_session')]);
        $health_expert_session->delete();
        return response()->json(['responseData' => $this->encryptData(['status' => true,'message' => $message]) ]);
        // return json_message_response( $message);
    }
}
