<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enumerationTestsTaken extends Model
{
    use HasFactory;

    protected $primaryKey = 'etttID';

    protected $fillable = [
        "etID",
        "user_id",
    ];
}
