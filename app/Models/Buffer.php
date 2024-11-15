<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buffer extends Model
{
    use HasFactory;

    protected $table = 'buffer';
    protected $guarded = ['id'];

    public function Stok(){
        return $this->hasMany(Stok::class);
    }
}
