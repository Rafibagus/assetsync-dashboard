<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required', // Contoh dari Flutter: 'iPhone 14' atau 'Samsung S23'
        ]);

        $user = User::where('email', $request->email)->first();

        // Verifikasi User dan Password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial salah atau tidak ditemukan.'
            ], 401);
        }

        // Hapus token lama jika kamu ingin batas 1 device per akun (opsional)
        // $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'user'  => [
                    'name'  => $user->name,
                    'email' => $user->email,
                ]
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Cabut token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }
}