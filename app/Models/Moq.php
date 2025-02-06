<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moq extends Model
{
    use HasFactory;
    protected $table = 'moq_mpq';
    protected $guarded = ['id'];
}
