<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubSymptomsResource;
use App\Models\SubSymptoms;
use App\Models\Symptoms;
use App\Traits\EncryptionTrait;
use Illuminate\Http\Request;

class SubSymptomsController extends Controller
{
    use EncryptionTrait;
    
    public function getList(Request $request)
    {
        $symptoms = Symptoms::with('subSymptoms')->where('status', 1)->get();
        
        $items = SubSymptomsResource::collection($symptoms);
        
        return response()->json(['responseData' => $this->encryptData(['data' => $items]) ]);
        // return json_custom_response(['data' => $items]); 
    }
}
