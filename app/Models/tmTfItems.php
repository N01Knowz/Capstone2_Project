<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmTfItems extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'tmtfID';

    protected $fillable = [
        "tmID",
        "itmID",
    ];
}
