<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'mahasiswa_nim',
        'mahasiswa_nama',
        'payment_type_id',
        'semester_id',
        'semester_nama',
        'nominal',
        'jatuh_tempo',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jatuh_tempo' => 'date',
            'nominal'     => 'decimal:2',
        ];
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }
}