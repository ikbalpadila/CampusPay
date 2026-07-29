<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('mahasiswa.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'password' => 'required',
        ]);

        try {

            // LOGIN KE AUTH SERVICE
            $response = Http::post(
                env('AUTH_SERVICE_URL')
                . '/api/auth/login',
                [
                    'login' => $request->nim,
                    'password' => $request->password,
                ]
            );

            if (! $response->successful()) {

                return back()->withErrors([
                    'nim' => 'NIM atau password salah'
                ]);
            }

            $data = $response->json('data');

            // PASTIKAN ROLE MAHASISWA
            if (
                ($data['user']['role'] ?? '') !== 'mahasiswa'
            ) {

                return back()->withErrors([
                    'nim' => 'Akun bukan mahasiswa'
                ]);
            }

            // AMBIL DATA MAHASISWA DARI STUDENT SERVICE
            $studentResponse = Http::get(
                env('STUDENT_SERVICE_URL')
                . '/api/students/nim/'
                . $data['user']['nim']
            );

            if (! $studentResponse->successful()) {

                return back()->withErrors([
                    'nim' => 'Data mahasiswa tidak ditemukan'
                ]);
            }

            $student = $studentResponse->json('data');

            // SIMPAN SESSION
            session([
                'jwt_token' => $data['token'],
                'mahasiswa' => [
                    'auth_id' => $data['user']['id'],
                    'id' => $student['id'],
                    'nim' => $student['nim'],
                    'nama' => $student['nama'],
                    'prodi' => $student['prodi'],
                    'fakultas' => $student['fakultas'],
                    'kelas' => $student['kelas'],
                    'semester_id' => $student['semester_id'],
                ]
            ]);

            return redirect()
                ->route('mahasiswa.dashboard');

        } catch (\Exception $e) {

            return back()->withErrors([
                'nim' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        Session::flush();

        return redirect()
            ->route('mahasiswa.login');
    }
}