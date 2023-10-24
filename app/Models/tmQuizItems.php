<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmQuizItems extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'tmquizID';

    protected $fillable = [
        "tmID",
        "itmID",
    ];
}
