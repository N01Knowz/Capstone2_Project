<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmTestsTaken extends Model
{
    use HasFactory;

    protected $primaryKey = 'tmttID';

    protected $fillable = [
        "tmID",
        "user_id",
    ];
}
