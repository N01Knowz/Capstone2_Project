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
        'IsHidden',
        "etDescription",
        "etNumber",
        "etTotal",
        "etIsPublic",
        "subjectID",
        "user_id",
        'inTM',
    ];
    public function analyticettags()
    {
        return $this->hasMany(analyticettags::class)->onDelete('cascade');
    }
    
    public function tmEt()
    {
        return $this->hasMany(tmEt::class)->onDelete('cascade');
    }
    public function etItems()
    {
        return $this->hasMany(etitems::class, 'etID', 'etID');
    }
}
