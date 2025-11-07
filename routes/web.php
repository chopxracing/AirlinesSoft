<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('loginForm');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('home');
    Route::get('/crew', [CrewController::class, 'index'])->name('crew');
    Route::get('/crew/employee', [CrewController::class, 'employee'])->name('crew.employee');
    Route::delete('/crew/{crew}', [CrewController::class, 'destroy'])->name('crew.destroy');
    Route::get('/crew/createemployee', [CrewController::class, 'showcreate'])->name('crew.createemployeeForm');
    Route::post('/crew/employee', [CrewController::class, 'create'])->name('crew.createemployee');
    Route::post('crew/medicials', [CrewController::class, 'updatemedicials'])->name('crew.updatemedicials');
    Route::get('crew/medicials', [CrewController::class, 'updateform'])->name('crew.updatemedicialsForm');
    Route::get('/crew/license', [CrewController::class, 'updatelicenseform'])->name('crew.updatelicenseForm');
    Route::post('/crew/license', [CrewController::class, 'updatelicense'])->name('crew.updatelicense');
    Route::get('/flight', [FlightController::class, 'index'])->name('flight');
    Route::get('/flight/active', [FlightController::class, 'flightactive'])->name('flight.active');
    Route::get('/flight/flightcreate', [FlightController::class, 'showflightcreate'])->name('flight.createForm');
    Route::post('/flight/flightcreate', [FlightController::class, 'create'])->name('flight.create');
    Route::get('/flight/history', [FlightController::class, 'history'])->name('flight.history');
    // Назначение экипажа
    Route::get('/flight/assign-crew', [FlightController::class, 'assignCrew'])->name('flight.assign.crew');
    Route::post('/flight/assign-crew', [FlightController::class, 'storeCrewAssignment'])->name('flight.store.crew.assignment');
    Route::delete('/flight/assign-crew/{id}', [FlightController::class, 'removeCrewAssignment'])->name('flight.remove.crew.assignment');
    Route::get('/flight/aircraftcreate', [FlightController::class, 'showaircraftcreate'])->name('flight.aircraftcreateForm');
    Route::post('/flight/aircraftcreate', [FlightController::class, 'aircraftcreate'])->name('flight.aircraftcreate');

    Route::get('work', [WorkController::class, 'index'])->name('work');
    Route::get('/work/myflights', [WorkController::class, 'showmyflights'])->name('work.myflights');
    Route::patch('/work', [WorkController::class, 'crewstatusupdate'])->name('work.crewstatusupdate');
    Route::get('/work/work', [WorkController::class, 'workshow'])->name('work.work');
    Route::patch('/work/work', [WorkController::class, 'flightstatusupdate'])->name('work.flightstatusupdate');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/usersexport', [ReportController::class, 'usersexport'])->name('reports.usersexport');
    Route::get('/reports/flightsexport', [ReportController::class, 'flightsexport'])->name('reports.flightsexport');
    Route::get('/reports/aircraftsexport', [ReportController::class, 'aircraftsexport'])->name('reports.aircraftsexport');

});


// Временный маршрут для создания пользователя - удалите после использования!
Route::get('/create-user', function() {
    $user = new \App\Models\User();
    $user->username = 'admin'; // или любое другое имя пользователя
    $user->password = Hash::make('admin'); // хешируем пароль
    $user->email = '123@mail.ru';
    $user->phone = '89999999999';
    $user->name = 'Admin';
    $user->surname = 'Admin';
    $user->idoc_series = '1234';
    $user->idoc_number = '567890';
    $user->status_id = 1;
    $user->position_id = 1;
    $user->clearance_id = 1;
    $user->medicial_to = '2025-11-10';
    $user->license_to = '2025-11-10';

    $user->save();

    return 'Пользователь создан!';
});

require __DIR__.'/settings.php';
