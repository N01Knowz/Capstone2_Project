<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmtests extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'tmID';

    protected $fillable = [
        "tmTitle",
        "tmDescription",
        "tmTotal",
        "tmIsPublic",
        "user_id",
        'IsHidden',
    ];
}
