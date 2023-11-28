<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmQuizItemsAnswers extends Model
{
    use HasFactory;
    protected $primaryKey = 'tmqziaID';

    protected $fillable = [
        "tmttID",
        "itmID",
        "qzStudentItemAnswer",
    ];
}
