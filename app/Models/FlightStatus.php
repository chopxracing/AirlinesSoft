<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightStatus extends Model
{
    protected $table = 'flight_statuses';

    protected $fillable = ['name'];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }
}
