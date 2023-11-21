<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tfTestsTaken extends Model
{
    use HasFactory;

    protected $primaryKey = 'tfttID';

    protected $fillable = [
        "tfID",
        "user_id",
    ];
}
