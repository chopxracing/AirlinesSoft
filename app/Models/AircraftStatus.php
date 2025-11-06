<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AircraftStatus extends Model
{
    protected $table = 'aircraft_statuses'; // Явно укажите таблицу

    public function aircraft()
    {
        return $this->hasMany(Aircraft::class, 'aircraft_status_id'); // Исправлено на hasMany
    }
}
