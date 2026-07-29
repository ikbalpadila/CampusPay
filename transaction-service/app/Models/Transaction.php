<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'tagihan_id',
        'mahasiswa_id',
        'nomor_va',
        'nominal',
        'metode',
        'lamport_clock',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }
}