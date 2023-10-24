<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class essays extends Model
{
    use HasFactory;

    protected $primaryKey = 'essID';
    
    protected $fillable = [
        "essTitle",
        "essQuestion",
        "essInstruction",
        "essCriteria1",
        "essScore1",
        "essCriteria2",
        "essScore2",
        "essCriteria3",
        "essScore3",
        "essCriteria4",
        "essScore4",
        "essCriteria5",
        "essScore5",
        "essImage",
        "essScoreTotal",
        "essIsPublic",
        "subjectID",
        "user_id",
    ];
}
