<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class analyticessaytags extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'esstgID';

    protected $fillable = [
        "essID",
        "tagID",
        "isActive",
    ];

    public function essays()
    {
        return $this->belongsTo(essays::class)->onDelete('cascade');
    }
}
