<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempKebMaterial extends Model
{
    use HasFactory;

    protected $table = 'temp_keb_material';
    protected $guarded = ['id'];
}
