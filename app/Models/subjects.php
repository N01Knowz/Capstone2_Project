<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subjects extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'subjectID';
    
    protected $fillable = [
        "subjectName",
        "user_id",
    ];
}
