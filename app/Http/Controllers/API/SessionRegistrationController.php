<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EducationalSession;
use App\Models\SessionRegistration;
use App\Traits\EncryptionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionRegistrationController extends Controller
{
    use EncryptionTrait;
    
    public function registerSession(Request $request)
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
        
        $educationalSession = EducationalSession::find($input['id']);
        
        if (!$educationalSession) {
            return response()->json(['responseData' => $this->encryptData(['message' => 'Educational session not found!']) ]);
            // return response()->json([
            //     'message' => 'Educational session not found!',
            // ], 404);
        }
        
       
        $userId = Auth::id(); 
        
        if ($educationalSession->practicement_user) {
            $practicementUsers = json_decode($educationalSession->practicement_user, true);
        } else {
          
            $practicementUsers = [];
        }
        
        if (!in_array($userId, $practicementUsers)) {
            $practicementUsers[] = $userId; 
        }
        
        $educationalSession->practicement_user = json_encode($practicementUsers);
        $educationalSession->save(); 
        
        $message = __('message.save_form', ['form' => __('message.educational_session')]);
        
        return response()->json(['responseData' => $this->encryptData([ 'message' => $message]) ]);
        // return json_custom_response($response);
    }
    
    public function cancelRegistration(Request $request)
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

        $registrationData = SessionRegistration::where('educational_session_id',$input['educational_session_id'])->first();
        $registrationData['is_cancelled'] = 1;
        $registrationData->save();

        $message = __('message.registration_cancel');
        $response = [
            'message' => $message,
            'data' => $registrationData
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
}
