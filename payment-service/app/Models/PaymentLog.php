<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'transaction_id',
        'tagihan_id',
        'event_type',
        'payload',
        'processed_by',
    ];

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }
}