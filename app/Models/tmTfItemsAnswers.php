<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmTfItemsAnswers extends Model
{
    use HasFactory;
    protected $primaryKey = 'tmtfiaID';

    protected $fillable = [
        "tmttID",
        "itmID",
        "tfStudentItemAnswer",
    ];
}
