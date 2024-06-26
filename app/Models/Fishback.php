<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fishback extends Model
{
    use HasFactory;

    protected $fillable = [
        'fish_id',
        'order_id',
        'user_id',
        'rating',
        'review',
    ];

    public function fish()
    {
        return $this->belongsTo(Fish::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
