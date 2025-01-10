<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingNonManual extends Model
{
    use HasFactory;

    protected $table = 'incoming_non_manual';
    protected $guarded = ['id'];
}
