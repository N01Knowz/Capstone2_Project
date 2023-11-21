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
    public function analyticessaytags()
    {
        return $this->hasMany(analyticessaytags::class)->onDelete('cascade');
    }
    
    public function tmEssay()
    {
        return $this->hasMany(tmEssay::class)->onDelete('cascade');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->onDelete('cascade');
    }
}
