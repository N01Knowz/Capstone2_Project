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
        'IsHidden',
        "mtDescription",
        "mtTotal",
        "mtIsPublic",
        "subjectID",
        "user_id",
        'inTM',
    ];
    public function analyticmttags()
    {
        return $this->hasMany(analyticmttags::class)->onDelete('cascade');
    }
    
    public function tmMt()
    {
        return $this->hasMany(tmMt::class)->onDelete('cascade');
    }
    public function mtItems()
    {
        return $this->hasMany(mtitems::class, 'mtID', 'mtID');
    }
}
