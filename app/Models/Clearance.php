<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'clearance_id');
    }

    public function clearancearray()
    {
        return $this->hasMany(ClearanceArray::class, 'clearance_id');
    }
}
