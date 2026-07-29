<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualPaymentSubmission extends Model
{
    protected $fillable = [
        'tagihan_id',
        'mahasiswa_id',
        'mahasiswa_nim',
        'mahasiswa_nama',
        'nominal',
        'bukti_transfer',
        'status',
        'catatan_admin',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'nominal'     => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }
}