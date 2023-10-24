<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quizitems extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'itmID';

    protected $fillable = [
        "qzID",
        "choices_number",
        "itmQuestion",
        "itmOption1",
        "itmOption2",
        "itmOption3",
        "itmOption4",
        "itmOption5",
        "itmOption6",
        "itmOption7",
        "itmOption8",
        "itmOption9",
        "itmOption10",
        "itmAnswer",
        "itmPoints",
        "itmImage",
    ];
}
