<?php

namespace App\Http\Controllers;

use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\User;
use App\Models\AircraftStatus;
use App\Models\Position;
use App\Models\FlightStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        // Основная статистика
        $stats = [
            'totalAircrafts' => Aircraft::count(),
            'totalFlights' => Flight::count(),
            'totalUsers' => User::count(),
            'activeFlights' => Flight::where('is_active', true)->count(),
            'todayFlights' => Flight::whereDate('departure_date', today())->count(),
            'totalFlightHours' => Aircraft::sum('flight_hours'),
        ];

        // Статусы самолетов
        $aircraftStatuses = AircraftStatus::withCount('aircraft')
            ->get()
            ->map(function ($status) use ($stats) {
                $status->percentage = $stats['totalAircrafts'] > 0
                    ? round(($status->aircraft_count / $stats['totalAircrafts']) * 100, 1)
                    : 0;
                return $status;
            });

        // Самолеты на обслуживании
        $stats['maintenanceAircrafts'] = Aircraft::whereHas('maintenanceStatus', function ($query) {
            $query->where('name', 'like', '%обслуживание%');
        })->count();

        // Пилоты с допуском
        $stats['clearedPilots'] = User::whereHas('clearance', function ($query) {
            $query->where('name', 'like', '%пилот%');
        })->count();

        // Статистика по должностям
        $positionStats = Position::withCount('user')->get();

        // Последние рейсы
        $recentFlights = Flight::with(['aircraft', 'flightStatus'])
            ->orderBy('departure_date', 'desc')
            ->limit(5)
            ->get();

        return view('home', array_merge($stats, [
            'aircraftStatuses' => $aircraftStatuses,
            'positionStats' => $positionStats,
            'recentFlights' => $recentFlights,
        ]));
    }
}
