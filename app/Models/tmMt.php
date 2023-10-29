<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tmMt extends Model
{
    use HasFactory;

    protected $primaryKey = 'tmmtID';

    protected $fillable = [
        "tmID",
        "mtID",
    ];
    public function mttests()
    {
        return $this->belongsTo(mttests::class)->onDelete('cascade');
    }
}
