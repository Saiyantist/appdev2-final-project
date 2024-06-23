<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bowl_id',
        'address_id',
        'ship_by_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bowl()
    {
        return $this->hasOne(Bowl::class);
    }

    public function userAddress()
    {
        return $this->hasOneThrough(Address::class, User::class);
    }
}