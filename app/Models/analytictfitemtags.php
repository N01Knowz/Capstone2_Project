<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class analytictfitemtags extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'tftgID';

    protected $fillable = [
        "itmID",
        "tagID",
        "isActive",
    ];

    public function tfitems()
    {
        return $this->belongsTo(tfitems::class)->onDelete('cascade');
    }
}
