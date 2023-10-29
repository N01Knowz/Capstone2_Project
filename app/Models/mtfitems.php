<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mtfitems extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'itmID';

    protected $fillable = [
        "mtfID",
        "choices_number",
        "itmQuestion",
        "itmOption1",
        "itmOption2",
        "itmAnswer",
        "itmPoints1",
        "itmPoints2",
        "itmPointsTotal",
        "itmImage",
    ];
    
    public function analyticmtfitemtags()
    {
        return $this->hasMany(analyticmtfitemtags::class)->onDelete('cascade');
    }
    public function tmMtfItems()
    {
        return $this->hasMany(tmMtfItems::class)->onDelete('cascade');
    }
}
