<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matchingItemsAnswers extends Model
{
    use HasFactory;
    protected $primaryKey = 'mtiaID';

    protected $fillable = [
        "mtttID",
        "itmID",
        "mtStudentItemAnswer",
    ];
}
