<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Controllers\Controller;

use App\Http\Resources\NotificationResource;
use App\Traits\EncryptionTrait;

class NotificationController extends Controller
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

        $user = auth()->user();

        $user->last_notification_seen = now();
        $user->save();

        $type = isset($input['type']) ? $input['type'] : null;
        if($type == "markas_read"){
            if(count($user->unreadNotifications) > 0 ) {
                $user->unreadNotifications->markAsRead();
            }
        }
        
        $page = isset($input['page']) ? $input['page'] : 1;
        $limit = isset($input['limit']) ? $input['limit'] : config('constant.PER_PAGE_LIMIT');

        $notifications = $user->Notifications->sortByDesc('created_at')->forPage($page,$limit);

        $all_unread_count = isset($user->unreadNotifications) ? $user->unreadNotifications->count() : 0;

        $items = NotificationResource::collection($notifications);
        
        $response = [
            'notification_data' => $items,
            'all_unread_count' => $all_unread_count,
        ];

        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
     public function getNotificationDetail(Request $request)
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
        $notification = Notification::where('id', $id)->first();

        if(empty($notification)){
            $response = [
                'message' => __('message.not_found_entry',['name' =>__('message.notification')])
            ];

            return response()->json(['responseData' => $this->encryptData($response) ]);
            // return json_message_response($message,400);   
        }

        $notification_detail = new NotificationResource($notification);

        $user = auth()->user();
        if(count($user->unreadNotifications) > 0 ) {
            $user->unreadNotifications->where('id', $id)->markAsRead();
        }

        $all_unread_count = isset($user->unreadNotifications) ? $user->unreadNotifications->count() : 0;

        $response = [
            'data' => $notification_detail,
            'all_unread_count' => $all_unread_count,
        ];
        
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
}
