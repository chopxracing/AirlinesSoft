<?php

namespace App\Http\Controllers;

use App\Http\Filters\EmployeeFilter;
use App\Http\Filters\FlightFilter;
use App\Models\Aircraft;
use App\Models\AircraftStatus;
use App\Models\Clearance;
use App\Models\ClearanceArray;
use App\Models\Crew;
use App\Models\CrewStatus;
use App\Models\Flight;
use App\Models\FlightHistory;
use App\Models\FlightStatus;
use App\Models\MaintenanceStatus;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\User;

class FlightController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aircrafts = Aircraft::withCount('flights')->get();
        $flights = Flight::all();
        $crews = User::all();

        // Подготовка данных для графика полетов по самолетам
        $aircraftFlights = [
            'labels' => $aircrafts->pluck('name'),
            'data' => $aircrafts->pluck('flights_count')
        ];

        // Дополнительная статистика
        $totalFlights = $flights->count();
        $totalFlightHours = $flights->sum('flight_time');
        $activeFlights = $flights->where('flight_status_id', 3)->count();
        $avgFlightHours = $totalFlights > 0 ? round($totalFlightHours / $totalFlights, 1) : 0;

        return view('flightmanager.flightmodule', compact(
            'aircrafts',
            'flights',
            'crews',
            'aircraftFlights',
            'totalFlights',
            'totalFlightHours',
            'activeFlights',
            'avgFlightHours'
        ));
    }

    public function flightactive(Request $request)
    {
        // Основной запрос - только активные полеты
        $query = Flight::with([
            'aircraft',
            'flightStatus',
        ])->where('is_active', true);

        // ПРИМЕНЯЕМ ФИЛЬТР
        $query = $query->filter(new FlightFilter($request->all()));

        $flights = $query->paginate(15);

        // Для отладки - проверь первый полет
        if ($flights->count() > 0) {
            $firstFlight = $flights->first();
            \Log::info('First flight status:', [
                'flight_id' => $firstFlight->id,
                'status_id' => $firstFlight->flight_status_id,
                'status_relation' => $firstFlight->flightStatus,
                'status_name' => $firstFlight->flightStatus?->name
            ]);
        }

        // Данные для фильтров
        $aircrafts = Aircraft::all();
        $flightStatuses = FlightStatus::all();

        // Уникальные значения для выпадающих списков
        $departures = Flight::where('is_active', true)->distinct()->pluck('departure');
        $arrivals = Flight::where('is_active', true)->distinct()->pluck('arrival');

        return view('flightmanager.flightactive', compact(
            'flights',
            'aircrafts',
            'flightStatuses',
            'departures',
            'arrivals'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function showflightcreate()
    {
        $crewStatuses = CrewStatus::all();
        $positions = Position::all();
        $clearances = Clearance::all();
        $aircrafts = Aircraft::all();
        $flightStatuses = FlightStatus::all();

        $crews= User::with('Clearance', 'CrewStatus', 'Position' )->get();
        return view('flightmanager.flightcreate', compact('crews', 'crewStatuses', 'positions', 'clearances', 'aircrafts', 'flightStatuses'));
    }

    public function create()
    {


        $data = request()->validate([
            'flight_number' => 'required',
            'aircraft_id' => 'required',
            'arrival' => 'required',
            'departure' => 'required',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date',
            'airport' => 'required',
        ]);
        Flight::create($data);

        $aircraft = Aircraft::find($data['aircraft_id']);
        $aircraft->update([
            'aircraft_status_id' => 2,
        ]);
        return redirect('/flight/flightcreate');
    }

    public function history(Request $request)
    {
        // Основной запрос для истории полетов (неактивные или завершенные полеты)
        $query = Flight::with([
            'aircraft',
            'flightStatus',
        ])->where('is_active', false); // Или где статус "Завершен"

        // Применяем фильтр если нужно
        $query = $query->filter(new FlightFilter($request->all()));

        $flights = $query->orderBy('created_at', 'desc')->paginate(15);

        // Данные для фильтров
        $aircrafts = Aircraft::all();
        $flightStatuses = FlightStatus::all();

        // Уникальные значения для выпадающих списков
        $departures = Flight::where('is_active', false)->distinct()->pluck('departure');
        $arrivals = Flight::where('is_active', false)->distinct()->pluck('arrival');

        return view('flightmanager.flighthistory', compact(
            'flights',
            'aircrafts',
            'flightStatuses',
            'departures',
            'arrivals'
        ));
    }
    public function assignCrew(Request $request)
    {
        // Активные полеты для назначения
        $activeFlights = Flight::with(['aircraft', 'flightStatus'])
            ->where('is_active', true)
            ->whereIn('flight_status_id', [1, 2]) // Готовится или Запланирован
            ->get();

        // Доступные сотрудники (экипаж)
        $availableCrew = User::with(['position', 'clearance'])
            ->whereHas('position', function($query) {
                $query->whereIn('name', ['КВС', 'Второй пилот', 'Стюардесса', 'Бортпроводник']);
            })
            ->where('status_id', 1) // Активный статус
            ->get();

        // История назначений
        $flightHistories = FlightHistory::with(['user', 'flight'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('flightmanager.assigncrew', compact(
            'activeFlights',
            'availableCrew',
            'flightHistories'
        ));
    }

    public function storeCrewAssignment(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|max:100'
        ]);

        // Проверяем, не назначен ли уже сотрудник на этот полет
        $existingAssignment = FlightHistory::where('flight_id', $validated['flight_id'])
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($existingAssignment) {
            return redirect()->back()->with('error', 'Сотрудник уже назначен на этот полет!');
        }

        // Создаем назначение
        FlightHistory::create($validated);

        return redirect()->route('flight.assign.crew')->with('success', 'Сотрудник успешно назначен на полет!');
    }

    public function removeCrewAssignment($id)
    {
        $assignment = FlightHistory::findOrFail($id);
        $assignment->delete();

        return redirect()->route('flight.assign.crew')->with('success', 'Назначение удалено!');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function showaircraftcreate()
    {
        $maintenancestatuses = MaintenanceStatus::all();
        $aircraftstatuses = AircraftStatus::all();
        return view('flightmanager.aircraftcreate', compact('aircraftstatuses', 'maintenancestatuses'));
    }

    /**
     * Display the specified resource.
     */
    public function aircraftcreate()
    {
        $data = request()->validate([
            'name' => 'required|string|max:100',
            'passenger_capacity' => 'required|integer|min:1',
            'max_flight_kilometers' => 'required|integer|min:1',
            'registration_number' => 'required',
            'aircraft_status_id' => 'required|exists:aircraft_statuses,id',
            'maintenance_status_id' => 'required|exists:maintenance_statuses,id',
            'flight_hours' => 'required|integer|min:1',
        ]);
        Aircraft::create($data);
        return redirect('/flight/aircraftcreate');
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crew $crew)
    {
        $crew->delete();
        return redirect('/crew/employee');
    }
}
