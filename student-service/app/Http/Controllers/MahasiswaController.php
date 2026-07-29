<?php

namespace App\Http\Controllers;

use App\Imports\MahasiswaImport;
use App\Models\Mahasiswa;
use App\Models\Semester;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;

class MahasiswaController extends Controller
{
    // GET semua mahasiswa
    public function index(Request $request)
    {
        $query = Mahasiswa::with('semester');

        if ($request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }

        if ($request->prodi) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->status_aktif !== null) {
            $query->where('status_aktif', $request->status_aktif);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nim', 'like', "%{$request->search}%")
                  ->orWhere('nama', 'like', "%{$request->search}%");
            });
        }

        $mahasiswas = $query->orderBy('nama')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data'   => $mahasiswas,
        ]);
    }

    // GET detail mahasiswa
    public function show($id)
    {
        $mahasiswa = Mahasiswa::with('semester')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $mahasiswa,
        ]);
    }

    // GET mahasiswa by NIM
    public function showByNim($nim)
    {
        $mahasiswa = Mahasiswa::with('semester')
                              ->where('nim', $nim)
                              ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data'   => $mahasiswa,
        ]);
    }

    // POST buat mahasiswa baru
    public function store(Request $request)
    {
        $request->validate([
            'nim'         => 'required|string|max:20|unique:mahasiswas',
            'nama'        => 'required|string|max:150',
            'prodi'       => 'required|string|max:100',
            'fakultas'    => 'required|string|max:100',
            'semester_id' => 'required|exists:semesters,id',
            'kelas'       => 'required|string|max:10',
        ]);

        $mahasiswa = Mahasiswa::create($request->all());

        Http::post(
            env('AUTH_SERVICE_URL')
            . '/api/register-mahasiswa',
            [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Mahasiswa berhasil ditambahkan',
            'data' => $mahasiswa->load('semester'),
        ], 201);

    }

    // PUT update mahasiswa
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nim'         => 'string|max:20|unique:mahasiswas,nim,'.$id,
            'nama'        => 'string|max:150',
            'prodi'       => 'string|max:100',
            'fakultas'    => 'string|max:100',
            'semester_id' => 'exists:semesters,id',
            'kelas'       => 'string|max:10',
            'status_aktif'=> 'boolean',
        ]);

        $mahasiswa->update($request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Data mahasiswa berhasil diupdate',
            'data'    => $mahasiswa->load('semester'),
        ]);
    }

    // POST import Excel
    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        Excel::import(new MahasiswaImport, $request->file('file'));

        return response()->json([
            'status'  => 'success',
            'message' => 'Data mahasiswa berhasil diimport',
        ]);
    }

    // GET mahasiswa aktif per semester (dipakai Billing Service)
    public function aktifBySemester($semester_id)
    {
        $mahasiswas = Mahasiswa::where('semester_id', $semester_id)
                               ->where('status_aktif', true)
                               ->get(['id', 'nim', 'nama', 'prodi', 'kelas']);

        return response()->json([
            'status' => 'success',
            'data'   => $mahasiswas,
        ]);
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $mahasiswa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Mahasiswa berhasil dihapus',
        ]);
    }
}