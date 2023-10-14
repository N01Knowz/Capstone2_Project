<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmMtfItems extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'tmmtfID';

    protected $fillable = [
        "tmID",
        "itmID",
    ];
}
