<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class testMaker extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "testbank_id",
        "test_id",
        "question_id",
    ];
}
