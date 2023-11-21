<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quizTestsTaken extends Model
{
    use HasFactory;

    protected $primaryKey = 'qzttID';

    protected $fillable = [
        "qzID",
        "user_id",
    ];
}
