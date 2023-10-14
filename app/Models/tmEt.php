<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmEt extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'tmetID';

    protected $fillable = [
        "tmID",
        "etID",
    ];
}
