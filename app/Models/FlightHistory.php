<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightHistory extends Model
{
    protected $table = 'flight_histories';

    protected $fillable = [
        'user_id',
        'flight_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }
}
