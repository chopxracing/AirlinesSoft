<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;






class TokenController extends Controller
{
    public function getToken(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json(['message' => 'wrong username or password'], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'username' => $user->username,
            'token' => $user->createToken("token of user {{$user->id}}")->plainTextToken
        ]);
    }
}
