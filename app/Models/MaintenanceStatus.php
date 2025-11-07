<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceStatus extends Model
{
    public function aircraft()
    {
        return $this->belongsTo(Aircraft::class);
    }
}
