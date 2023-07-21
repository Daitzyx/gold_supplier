<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('email', 'password');

            $accessToken = Auth::attempt($credentials);
            $refreshToken = Auth::setTTL(20160)->attempt($credentials);

            if (!$accessToken) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = Auth::user();

            return response()->json([
                'user' => $user,
                'authorization' => [
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'type' => 'bearer',
                    ]
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], 400);
        }
    }

    public function register(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            // 'user' => Auth::user(),
            'authorization' => [
                'access_token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}
