<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fish extends Model
{
    use HasFactory;

    public function bowlItem()
    {
        return $this->belongsTo(bowlItem::class);
    }

    public function fishbacks()
    {
        return $this->hasManyThrough(Fishback::class, Order::class);
    }
}