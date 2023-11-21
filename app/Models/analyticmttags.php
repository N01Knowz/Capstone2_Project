<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class analyticmttags extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'mttgID';

    protected $fillable = [
        "mtID",
        "tagID",
        "isActive",
    ];
    public function mttests()
    {
        return $this->belongsTo(mttests::class)->onDelete('cascade');
    }
}
