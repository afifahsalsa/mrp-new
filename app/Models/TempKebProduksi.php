<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempKebProduksi extends Model
{
    use HasFactory;

    protected $table = 'temp_keb_produksi';
    protected $guarded = ['id'];
}
