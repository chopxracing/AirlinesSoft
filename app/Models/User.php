<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasApiTokens;
    use Filterable;


    protected $guarded = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
        'surname',
        'position_id',
        'status_id',
        'clearance_id',
        'idoc_series',
        'idoc_number',
        'phone',
        'time_in_air',
        'time_out_air',
        'medicial_number',
        'medicial_to',
        'license_number',
        'license_to',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function crewstatus()
    {
        return $this->belongsTo(CrewStatus::class, 'status_id');
    }

    public function clearance()
    {
        return $this->belongsTo(Clearance::class, 'clearance_id');
    }

    // Добавьте эти отношения для flight histories
    public function flightHistories()
    {
        return $this->hasMany(FlightHistory::class, 'user_id');
    }

    public function flights()
    {
        return $this->belongsToMany(Flight::class, 'flight_histories', 'user_id', 'flight_id')
            ->withPivot('flight_hours')
            ->withTimestamps();
    }

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}
