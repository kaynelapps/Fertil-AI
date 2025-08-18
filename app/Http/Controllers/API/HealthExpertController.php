<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthExpert;
use App\Http\Resources\HealthExpertResource;
use App\Traits\EncryptionTrait;

class HealthExpertController extends Controller
{
    use EncryptionTrait;
    
    public function getList(Request $request)
    {
        $auth_user = auth()->user();
        if ($auth_user->hasRole('doctor')) {
            $message = __('message.demo_permission_denied');
            return json_message_response($message, 403);
        }

        $healthExpert = HealthExpert::Active();

        $healthExpert->when(request('type'), function ($q) {
            return $q->where('type', 'LIKE', '%' . request('type') . '%');
        });

        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page))
            {
                $per_page = $request->per_page;
            }
            if($request->per_page == -1 ){
                $per_page = $healthExpert->count();
            }
        }

        $healthExpert = $healthExpert->orderBy('id', 'desc')->paginate($per_page);
        $items = HealthExpertResource::collection($healthExpert);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];

        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
}
