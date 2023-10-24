<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmEssay extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'tmessID';

    protected $fillable = [
        "tmID",
        "essID",
    ];
}
