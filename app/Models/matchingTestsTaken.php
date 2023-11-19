<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matchingTestsTaken extends Model
{
    use HasFactory;

    protected $primaryKey = 'mtttID';

    protected $fillable = [
        "mtID",
        "user_id",
    ];
}
