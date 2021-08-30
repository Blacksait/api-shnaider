<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    public function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 28800,
            'success' => true
        ], 200);
    }

    public function respondWithTokenAndData($token, $data, $auth)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 28800,
            'userData' => $data,
            'auth' => $auth,
            'success' => true
        ], 200);
    }
}