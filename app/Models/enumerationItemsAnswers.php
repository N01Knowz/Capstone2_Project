<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enumerationItemsAnswers extends Model
{
    use HasFactory;
    protected $primaryKey = 'etiaID';

    protected $fillable = [
        "etttID",
        "etStudentItemAnswer",
    ];
}
