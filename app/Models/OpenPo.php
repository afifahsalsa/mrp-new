<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenPo extends Model
{
    use HasFactory;

    protected $table = 'open_po';
    protected $guarded = ['id'];
}
