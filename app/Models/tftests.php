<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tftests extends Model
{
    use HasFactory;

    protected $primaryKey = 'tfID';

    protected $fillable = [
        "tfTitle",
        "tfDescription",
        "tfTotal",
        "tfIsPublic",
        "subjectID",
        "user_id",
    ];
    public function tfItems()
    {
        return $this->hasMany(tfitems::class, 'tfID', 'tfID');
    }
}
