<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class HealthController extends Controller
{
    public function check()
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now(),
            'environment' => config('app.env')
        ], Response::HTTP_OK);
    }
}
