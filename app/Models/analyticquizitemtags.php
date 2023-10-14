<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class analyticquizitemtags extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'quiztgID';

    protected $fillable = [
        "itmID",
        "tagID",
        "isActive",
    ];
}
