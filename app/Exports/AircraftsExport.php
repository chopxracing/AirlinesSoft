<?php

namespace App\Exports;

use App\Models\Aircraft;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AircraftsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Aircraft::with(['aircraftstatus'])
        ->select(
            'id',
            'name',
            'passenger_capacity',
            'max_flight_kilometers',
            'aircraft_status_id',
            'registration_number',
            'maintenance_status_id',
            'flight_hours',
            'created_at'
        )->get()
            ->map(function ($aircraft) {
                return [
                $aircraft->id,
                $aircraft->name,
                $aircraft->passenger_capacity,
                $aircraft->max_flight_kilometers,
                $aircraft->aircraftstatus->name,
                    $aircraft->registration_number,
                    $aircraft->maintenancestatus->name,
                    $aircraft->flight_hours,
                $aircraft->created_at
                    ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Название',
            'Вместимость',
            'Дальность полета',
            'Статус',
            'Рег. номер',
            'Тех. состояние',
            'Налет часов',
            'Дата добавления'
        ];
    }
}
