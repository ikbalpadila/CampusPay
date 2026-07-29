<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LamportClock extends Model
{
    protected $table      = 'lamport_clock';
    public    $timestamps = false;
    protected $fillable   = ['id', 'value'];
}