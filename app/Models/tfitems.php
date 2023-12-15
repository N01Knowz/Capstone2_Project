<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tfitems extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'itmID';

    protected $fillable = [
        "tfID",
        "choices_number",
        "itmQuestion",
        "itmOption1",
        "itmOption2",
        "itmAnswer",
        "itmPoints",
        "itmImage",
        'inTM',
    ];
    public function analytictfitemtags()
    {
        return $this->hasMany(analytictfitemtags::class)->onDelete('cascade');
    }
    public function tmTfItems()
    {
        return $this->hasMany(tmTfItems::class)->onDelete('cascade');
    }
}
