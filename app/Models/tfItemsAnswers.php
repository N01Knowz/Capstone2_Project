<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tfItemsAnswers extends Model
{
    use HasFactory;
    protected $primaryKey = 'tfiaID';

    protected $fillable = [
        "tfttID",
        "itmID",
        "tfStudentItemAnswer",
    ];
}
