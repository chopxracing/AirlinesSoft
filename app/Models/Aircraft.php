<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    protected $table = 'aircraft';

    protected $fillable = [
        'name',
        'passenger_capacity',
        'max_flight_kilometers',
        'aircraft_status_id' // Добавьте это поле
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class, 'aircraft_id');
    }

    public function aircraftStatus()
    {
        return $this->belongsTo(AircraftStatus::class, 'aircraft_status_id'); // Исправлено на belongsTo
    }
}
