<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

use App\Http\Resources\ReaderResource;

class LoginController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request){
        //dd($request->all());
        $credentials = $request->only('email', 'password');
        if (! $token = Auth::guard('reader')->attempt($credentials)) {
            return response()->json([
                'statusCode' => 401,
                'error' => 'Wrong Username or Password'
            ], 401);
        }
        //dd(Auth::guard('reader')->user());
        $user = new ReaderResource(Auth::guard('reader')->user());
        if ( Auth::guard('reader')->check()){
            dd('authenticated');
        }else{
            dd('not authenticated');
        }
        return response()->json([
            'statusCode' => 200,
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'token_expires_in' => Auth::guard('reader')->factory()->getTTL() * 60, 
                'user' => $user
            ]
        ], 200);
    }
}
