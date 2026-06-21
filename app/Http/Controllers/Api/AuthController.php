<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password) // Enkripsi password
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil!',
            'user' => $user
        ]);

    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil!',
                'token' => $token,
                'user' => $user
            ]);
        }

        
        return response()->json([
            'status' => 'error',
            'message' => 'Email atau password salah'
        ], 401);
    }

    public function logout(Request $request)
{
    // Jika menggunakan Laravel Sanctum untuk autentikasi token:
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout berhasil'
    ]);
}
}