<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpp extends Model
{
    use HasFactory;

    protected $table = 'mpp';
    protected $guarded = ['id'];
}
