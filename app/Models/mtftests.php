<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mtftests extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'mtfID';

    protected $fillable = [
        "mtfTitle",
        "mtfDescription",
        "itmPoints1",
        "itmPoints2",
        "mtfTotal",
        "mtfIsPublic",
        "subjectID",
        "user_id",
    ];
}
