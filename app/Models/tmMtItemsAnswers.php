<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmMtItemsAnswers extends Model
{
    use HasFactory;
    protected $primaryKey = 'tmmtiaID';

    protected $fillable = [
        "tmttID",
        "itmID",
        "mtStudentItemAnswer",
    ];
}
