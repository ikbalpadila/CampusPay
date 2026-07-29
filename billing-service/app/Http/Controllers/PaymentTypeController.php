<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $types = PaymentType::where('is_aktif', true)->get();

        return response()->json([
            'status' => 'success',
            'data'   => $types,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $type = PaymentType::create($request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Jenis pembayaran berhasil dibuat',
            'data'    => $type,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $type = PaymentType::findOrFail($id);
        $type->update($request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Jenis pembayaran berhasil diupdate',
            'data'    => $type,
        ]);
    }
}