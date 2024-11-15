<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;
    protected $table = 'stok';
    protected $guarded = ['id'];

    public function Buffer(){
        return $this->belongsTo(Buffer::class);
    }
}
