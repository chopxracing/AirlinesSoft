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
    {   $user = auth()->user();
        $crews = User::all();
        $flights = Flight::where('is_active', 1)->get();
        $flight_statuses = FlightStatus::all();
        $flighthistories = FlightHistory::where('user_id', auth()->id())->get();
        if($user->position_id == 1) {
            return view('work.work', compact('crews', 'flights', 'flighthistories', 'flight_statuses'));
        } else {
            return redirect()->route('work');
        }
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

        // Находим полет и загружаем связанный самолет
        $flight = Flight::with('aircraft')->find($data['flight_id']);

        if (!$flight) {
            return redirect()->route('work.work')->with('error', 'Полет не найден!');
        }

        // Получаем самолет из отношения, а не отдельным поиском
        $aircraft = $flight->aircraft;

        // Обновляем статус полета
        $flight->update([
            'flight_status_id' => $data['flight_status_id']
        ]);

        // Обновляем статус пользователя в зависимости от статуса полета
        switch ($data['flight_status_id']) {
            case 2: // Запланирован
                $user->update([
                    'status_id' => 2,
                    'last_action_at' => now()
                ]);
                break;

            case 3: // В полете
                $user->update([
                    'status_id' => 3,
                    'last_action_at' => now()
                ]);
                break;

            case 4: // Завершен
            case 5: // Отменен
                $user->update([
                    'status_id' => 1,
                    'last_action_at' => now(),
                ]);

                // Только для завершенных полетов добавляем часы налета
                if ($data['flight_status_id'] == 4) {
                    $duration = $flight->arrival_date->diff($flight->departure_date);
                    $flightHours = $duration->h + ($duration->i / 60); // Учитываем минуты

                    // Обновляем налет пользователя
                    $user->update([
                        'time_in_air' => $user->time_in_air + $flightHours,
                    ]);

                    // Обновляем данные полета
                    $flight->update([
                        'is_active' => 0,
                        'flight_time' => $flightHours,
                    ]);

                    // Обновляем самолет, если он существует
                    if ($aircraft) {
                        $aircraft->update([
                            'flight_hours' => ($aircraft->flight_hours ?? 0) + $flightHours,
                            'aircraft_status_id' => 1, // Делаем самолет доступным
                        ]);
                    }
                } else {
                    // Для отмененных полетов просто деактивируем
                    $flight->update([
                        'is_active' => 0,
                    ]);

                    // Освобождаем самолет
                    if ($aircraft) {
                        $aircraft->update([
                            'aircraft_status_id' => 1,
                        ]);
                    }
                }
                break;
        }

        return redirect()->route('work.work')->with('success', 'Статус полета обновлен!');
    }
}
