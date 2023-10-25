<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mttests extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'mtID';

    protected $fillable = [
        "mtTitle",
        "mtDescription",
        "mtTotal",
        "mtIsPublic",
        "subjectID",
        "user_id",
    ];
}
