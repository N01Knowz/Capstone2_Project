<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quizzes extends Model
{
    use HasFactory;

    protected $primaryKey = 'qzID';

    protected $fillable = [
        "qzTitle",
        'IsHidden',
        "qzDescription",
        "qzTotal",
        "qzIsPublic",
        "subjectID",
        "user_id",
    ];
    public function quizItems()
    {
        return $this->hasMany(quizitems::class, 'qzID', 'qzID');
    }
}
