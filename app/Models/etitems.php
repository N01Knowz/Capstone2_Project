<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class etitems extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'itmID';

    protected $fillable = [
        "etID",
        "itmAnswer",
        "itmIsCaseSensitive",
    ];
}
