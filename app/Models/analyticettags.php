<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class analyticettags extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'ettgID';

    protected $fillable = [
        "etID",
        "tagID",
        "isActive",
    ];
}
