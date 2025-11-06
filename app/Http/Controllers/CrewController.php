<?php

namespace App\Http\Controllers;

use App\Http\Filters\EmployeeFilter;
use App\Models\Aircraft;
use App\Models\Clearance;
use App\Models\ClearanceArray;
use App\Models\Crew;
use App\Models\CrewStatus;
use App\Models\Flight;
use App\Models\FlightHistory;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\User;

class CrewController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $crews = User::with('Clearance', 'CrewStatus', 'Position' )->get();
        return view('crewmodule.crewmodule', compact('crews'));

    }

    public function employee(Request $request)
    {
        $query = User::with(['Clearance', 'CrewStatus', 'Position', 'flightHistories' => function($query) {
            $query->where('created_at', '>=', now()->subHours(24));
        }]);

        $filter = app()->make(EmployeeFilter::class, ['queryParams' => array_filter($request->all())]);
        $crews = $query->filter($filter)->paginate(15);

        $crewStatuses = CrewStatus::all();
        $positions = Position::all();
        $clearances = Clearance::all();
        $aircrafts = Aircraft::all();
        $flights = Flight::all();
        $clearance_array = ClearanceArray::all();

        return view('crewmodule.crewemployee', compact('crews', 'crewStatuses', 'positions', 'clearances', 'aircrafts', 'clearance_array', 'flights'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function showcreate()
    {
        $crewStatuses = CrewStatus::all();
        $positions = Position::all();
        $clearances = Clearance::all();

        $crews= User::with('Clearance', 'CrewStatus', 'Position' )->get();
        return view('crewmodule.createemployee', compact('crews', 'crewStatuses', 'positions', 'clearances'));
    }

    public function create()
    {
        $data = request()->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'username' => 'required',
            'password' => 'required',
            'position_id' => 'required',
            'clearance_id' => 'required',
            'idoc_series' => 'required',
            'idoc_number' => 'required',
        ]);
        User::create($data);
        return redirect('/crew/createemployee');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Crew $crew)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */


    public function updateform()
    {
        $users = User::all();
        // Получаем недавно обновленные справки (за последние 7 дней)
        $recentMedicials = User::whereNotNull('medicial_number')
            ->where('medicial_to', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('crewmodule.medicials', compact('users', 'recentMedicials'));
    }

    public function updatemedicials(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'medicial_number' => 'required|string|max:255|unique:users,medicial_number,' . $request->user_id,
            'medicial_to' => 'required|date|after:today',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->update([
            'medicial_number' => $validated['medicial_number'],
            'medicial_to' => $validated['medicial_to'],
            // Если у вас есть поле для примечаний, добавьте его здесь
        ]);

        return redirect()->route('crew.employee')->with('success', 'Медицинская справка успешно добавлена!');
    }


    public function updatelicenseform()
    {
        $users = User::all();
        // Получаем недавно обновленные лицензии
        $recentLicenses = User::whereNotNull('license_number')
            ->where('license_to', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('crewmodule.license', compact('users', 'recentLicenses'));
    }

    public function updatelicense(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'license_number' => 'required|string|max:255|unique:users,license_number,' . $request->user_id,
            'license_to' => 'required|date|after:today',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->update([
            'license_number' => $validated['license_number'],
            'license_to' => $validated['license_to'],
            // Если у вас есть поле для примечаний к лицензии
        ]);

        return redirect()->route('crew.employee')->with('success', 'Лицензия успешно добавлена!');
    }

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
