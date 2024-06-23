<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BowlItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'fish_id',
        'bowl_id',
        'quantity',
        'sub_total',
    ];

    public function bowl()
    {
        return $this->belongsTo(Bowl::class);
    }
    
    public function fish()
    {
        return $this->hasOne(Fish::class);
    }
}
