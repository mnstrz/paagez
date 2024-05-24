<?php

namespace Monsterz\Paagez\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $user = config('paagez.models.user')::where('email',$request->email)->first();
        if($user)
        {
            if(!$user->hasRole('user_api','api'))
            {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'Users role are not allowed',
                ], 403);
            }
        }
        $credentials = $request->only('email', 'password');
        $token = \Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Unauthorized',
            ], 401);
        }
        $user = \Auth::guard('api')->user();
        $expiration = JWTAuth::factory()->getTTL() * 60;
        $expired_date = \Carbon\Carbon::now()->addSeconds($expiration)->format('Y-m-d H:i:s');
        return response()->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'user' => $user->only(['name','email']),
                    'authorization' => [
                        'token' => $token,
                        'expired' => $expiration,
                        'expired_date' => $expired_date,
                        'type' => 'bearer',
                    ]
                ]);
    }

    public function profile(Request $request)
    {
        try {
            $user = \Auth::guard('api')->user();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'user' => $user->only(['name','email']),
            ]);
        } catch (\Exception $e) {
            response()->json([
                'status' => 'error',
                'status_code' => 422,
                'message' => $e->getMessage()
            ],422);
        }
    }
}
