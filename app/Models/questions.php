<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    use HasFactory;

    protected $fillable = [
        "testbank_id",
        "question_active",
        "item_question",
        "question_image",
        "choices_number",
        "question_answer",
        "question_point",
        "option_1",
        "option_2",
        "option_3",
        "option_4",
        "option_5",
        "option_6",
        "option_7",
        "option_8",
        "option_9",
        "option_10",
    ];
}
