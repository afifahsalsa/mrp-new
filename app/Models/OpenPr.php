<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenPr extends Model
{
    use HasFactory;
    protected $table = 'open_pr';
    protected $guarded = ['id'];
}
