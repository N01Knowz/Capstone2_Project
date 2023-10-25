<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mtitems extends Model
{
    use HasFactory;

    protected $primaryKey = 'itmID';

    protected $fillable = [
        "mtID",
        "itmQuestion",
        "itmAnswer",
        "itmPoints",
    ];
}
