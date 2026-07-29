<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required|string',
        ]);

        $user = User::where(function ($query) use ($request) {

            $query->where('email', $request->login)
                ->orWhere('nim', $request->login);

        })
        ->where('is_aktif', true)
        ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {

            return response()->json([
                'status' => 'error',
                'message' => 'Login atau password salah',
            ], 401);

        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60,
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'role' => $user->role,
                    'nim' => $user->nim,
                ],
            ],
        ]);
    }

    // Logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'status'  => 'success',
            'message' => 'Logout berhasil',
        ]);
    }

    // Data user yang sedang login
    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'    => $user->id,
                'nama'  => $user->nama,
                'email' => $user->email,
                'role'  => $user->role,
                'nim'   => $user->nim,
            ],
        ]);
    }

    // Refresh token
    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        return response()->json([
            'status' => 'success',
            'data'   => [
                'token'      => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60,
            ],
        ]);
    }

    public function registerMahasiswa(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:users,nim',
            'nama' => 'required',
        ]);

        $user = User::create([
            'nama'      => $request->nama,
            'nim'       => $request->nim,
            'email'     => $request->nim . '@mahasiswa.campuspay.local',
            'role'      => 'mahasiswa',
            'is_aktif'  => true,
            'password'  => bcrypt($request->nim),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Akun mahasiswa berhasil dibuat',
            'data' => $user,
        ]);
    }
}