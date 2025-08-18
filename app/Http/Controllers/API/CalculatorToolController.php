<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalculatorTool;
use App\Http\Resources\CalculatorToolResource;
use App\Traits\EncryptionTrait;

class CalculatorToolController extends Controller
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
        
        $calculator_tool = CalculatorTool::where('status',1);

        if(!empty($input['type'])){
            $calculator_tool->when($input['type'], function ($q) use ($input) {
                return $q->where('type', 'LIKE', '%' . $input['type'] . '%');
            });
        }

        $per_page = config('constant.PER_PAGE_LIMIT');
        if(!empty($input['per_page'])){
            if(is_numeric($input['per_page']))
            {
                $per_page = $input['per_page'];
            }
            if($input['per_page'] == -1 ){
                $per_page = $calculator_tool->count();
            }
        }

        $calculator_tool = $calculator_tool->orderBy('id', 'desc')->paginate($per_page);
        $items = CalculatorToolResource::collection($calculator_tool);

        $response = [
            'pagination'    => json_pagination_response($items),
            'data'          => $items,
        ];
        return response()->json(['responseData' => $this->encryptData($response) ]);
        // return json_custom_response($response);
    }
}
