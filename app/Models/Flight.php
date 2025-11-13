<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{

    use Filterable;

    protected $dates = [
        'departure_date',
        'arrival_date',
        'created_at',
        'updated_at'
    ];

    // Или используем $casts для Laravel 7+
    protected $casts = [
        'departure_date' => 'datetime',
        'arrival_date' => 'datetime',
    ];

    // Аксессор для вычисления длительности полета
    public function getFlightDurationAttribute()
    {
        if ($this->departure_date && $this->arrival_date) {
            return $this->departure_date->diffInHours($this->arrival_date);
        }
        return 0;
    }

    // Аксессор для форматированной длительности
    public function getFormattedDurationAttribute()
    {
        if ($this->departure_date && $this->arrival_date) {
            $diff = $this->departure_date->diff($this->arrival_date);
            return $diff->format('%h ч. %i мин.');
        }
        return 'Н/Д';
    }

    protected $fillable = [
        'arrival',
        'departure',
        'flight_number',
        'aircraft_id',
        'flight_time',
        'flight_status_id',
        'is_active',
        'departure_date',
        'arrival_date',
        'airport',
    ];

    public function aircraft()
    {
        return $this->belongsTo(Aircraft::class, 'aircraft_id');
    }

    public function flightHistories()
    {
        return $this->hasMany(FlightHistory::class, 'flight_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'flight_histories', 'flight_id', 'user_id')
            ->withPivot('flight_hours')
            ->withTimestamps();
    }

    public function flightStatus()
    {
        return $this->belongsTo(FlightStatus::class, 'flight_status_id');
    }
    public function crewMembers()
    {
        return $this->hasMany(FlightHistory::class, 'flight_id')->with('user');
    }

    public function assignedUsers()
    {
        return $this->hasManyThrough(
            User::class,
            FlightHistory::class,
            'flight_id', // Внешний ключ в FlightHistory
            'id',        // Локальный ключ в User
            'id',        // Локальный ключ в Flight
            'user_id'    // Внешний ключ в FlightHistory
        );
    }
}
