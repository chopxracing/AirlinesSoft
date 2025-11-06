<?php

namespace App\Http\Controllers;

use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\FlightHistory;
use App\Models\FlightStatus;
use App\Models\User;

class WorkController extends Controller
{
    public function index()
    {
        $flights = Flight::all();
        $flighthistories = FlightHistory::where('user_id', auth()->id())->get(); // Добавлен get()
        $crews = User::all();
        $aircrafts = Aircraft::all();
        return view('work.workmodule', compact('flights', 'crews', 'flighthistories', 'aircrafts'));
    }
    public function myflights()
    {
        $flights = Flight::all();
        $crews = User::all();
        $aircrafts = Aircraft::all();
        $flighthistories = FlightHistory::where('user_id', auth()->id())->get();
        return view('work.myflights', compact('flights', 'crews', 'aircrafts', 'flighthistories'));
    }

    public function workshow()
    {
        $crews = User::all();
        $flights = Flight::all();
        $flight_statuses = FlightStatus::all();
        $flighthistories = FlightHistory::where('user_id', auth()->id())->get();
        return view('work.work', compact('crews', 'flights', 'flighthistories', 'flight_statuses'));
    }

    public function crewstatusupdate()
    {
        $user = auth()->user(); // Получаем текущего авторизованного пользователя

        $data = request()->validate([
            'status_id' => 'required',
        ]);

        $user->update($data);

        return redirect()->route('work.myflights')->with('success', 'Статус обновлен!');
    }
    public function flightstatusupdate()
    {
        $user = auth()->user();

        // Валидация данных
        $data = request()->validate([
            'flight_id' => 'required|exists:flights,id',
            'flight_status_id' => 'required|exists:flight_statuses,id',
        ]);

        // Проверяем, что пользователь назначен на этот полет
        $isAssigned = FlightHistory::where('flight_id', $data['flight_id'])
            ->where('user_id', $user->id)
            ->exists();

        if (!$isAssigned) {
            return redirect()->route('work.work')->with('error', 'Вы не назначены на этот полет!');
        }

        // Находим полет и обновляем статус
        $flight = Flight::find($data['flight_id']);

        if (!$flight) {
            return redirect()->route('work.work')->with('error', 'Полет не найден!');
        }

        // Обновляем flight_status_id (правильное название поля)
        $flight->flight_status_id = $data['flight_status_id'];
        $flight->save();

        return redirect()->route('work.work')->with('success', 'Статус полета обновлен!');
    }
}
