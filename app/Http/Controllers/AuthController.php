<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login (Request $request) {
        $request->validate([
            "email" => 'required|email',
            "password" => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !auth()->attempt($request->only('email', 'password'))) {
            return response()->json('email atau password salah', 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

  public function logout(Request $request)
{
    $user = $request->user();

    $user->tokens()->delete();

    // $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout berhasil'
    ]);
}

}
