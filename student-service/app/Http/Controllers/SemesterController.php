<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderBy('id', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $semesters,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:50',
            'tahun_ajaran' => 'required|string|max:20',
            'is_aktif'     => 'boolean',
        ]);

        // Jika set aktif, nonaktifkan semester lain dulu
        if ($request->is_aktif) {
            Semester::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        $semester = Semester::create($request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Semester berhasil dibuat',
            'data'    => $semester,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);

        $request->validate([
            'nama'         => 'required|string|max:50',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $semester->update([
            'nama'         => $request->nama,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Semester berhasil diperbarui',
            'data'    => $semester,
        ]);
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);

        if ($semester->is_aktif) {

            return response()->json([
                'status' => 'error',
                'message' => 'Semester aktif tidak boleh dihapus'
            ], 422);

        }

        $semester->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Semester berhasil dihapus'
        ]);
    }

    public function setAktif($id)
    {
        Semester::where('is_aktif', true)->update(['is_aktif' => false]);
        $semester = Semester::findOrFail($id);
        $semester->update(['is_aktif' => true]);

        return response()->json([
            'status'  => 'success',
            'message' => "Semester {$semester->nama} sekarang aktif",
            'data'    => $semester,
        ]);
    }
}