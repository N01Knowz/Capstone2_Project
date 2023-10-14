<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ettests extends Model
{
    use HasFactory;

    protected $primaryKey = 'etID';

    protected $fillable = [
        "etTitle",
        "etDescription",
        "etNumber",
        "etTotal",
        "etIsPublic",
        "subjectID",
        "user_id",
    ];
}
