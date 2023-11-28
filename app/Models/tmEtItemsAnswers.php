<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmEtItemsAnswers extends Model
{
    use HasFactory;
    protected $primaryKey = 'tmetiaID';

    protected $fillable = [
        "tmttID",
        "etStudentItemAnswer",
        "etID",
    ];
}
