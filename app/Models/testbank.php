<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class testbank extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "test_type",
        "test_title",
        "test_question",
        "test_instruction",
        "test_image",
        "test_total_points",
        "test_visible",
        "test_active",
    ];

    protected $attribute = [
        'test_active' => 1,
        'test_visible' => 0,
    ];
}
