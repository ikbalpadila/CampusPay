<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    protected $fillable = [
        'tagihan_id',
        'mahasiswa_id',
        'nomor_va',
        'nominal',
        'status',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'nominal'    => 'decimal:2',
            'expired_at' => 'datetime',
        ];
    }

    // Cek apakah VA masih berlaku
    public function isAktif(): bool
    {
        return $this->status === 'aktif'
            && $this->expired_at->isFuture();
    }
}