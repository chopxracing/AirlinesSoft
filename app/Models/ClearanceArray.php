<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClearanceArray extends Model
{
    public function clearance()
    {
        return $this->belongsTo(Clearance::class, 'clearance_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
