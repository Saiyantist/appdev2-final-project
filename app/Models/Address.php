<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'house_num',
        'street_name',
        'town',
        'city',
        'zip_code'
    ];
    
    public function user()
    {
        $this->belongsTo(User::class);
    }

}
