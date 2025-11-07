<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    protected $table = 'aircraft';

    protected $fillable = [
        'name',
        'passenger_capacity',
        'registration_number',
        'max_flight_kilometers',
        'aircraft_status_id',
        'flight_hours',
        'maintenance_status_id',
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class, 'aircraft_id');
    }

    public function aircraftStatus()
    {
        return $this->belongsTo(AircraftStatus::class, 'aircraft_status_id'); // Исправлено на belongsTo
    }
    public function maintenancestatus()
    {
        return $this->belongsTo(MaintenanceStatus::class, 'aircraft_status_id');
    }
}
