<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class analyticmtfitemtags extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'mtftgID';

    protected $fillable = [
        "itmID",
        "tagID",
        "isActive",
    ];
}
