<?php

namespace App\Exports;

use App\Models\Flight;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FlightsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Flight::with(['aircraft', 'flightStatus'])
            ->select(
                'id',
                'flight_number',
                'departure',
                'arrival',
                'departure_date',
                'arrival_date',
                'flight_status_id',
                'aircraft_id',
                'created_at'
            )->get()
            ->map(function($flight) {
                return [
                    'id' => $flight->id,
                    'flight_number' => $flight->flight_number,
                    'departure' => $flight->departure,
                    'arrival' => $flight->arrival,
                    'departure_date' => $flight->departure_date->format('d.m.Y H:i'),
                    'arrival_date' => $flight->arrival_date->format('d.m.Y H:i'),
                    'flight_status_id' => $flight->flightStatus->name,
                    'aircraft_id' => $flight->aircraft->name,
                    'created_at' => $flight->created_at->format('d.m.Y H:i')
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Номер рейса',
            'Вылет из',
            'Прилет в',
            'Дата вылета',
            'Дата прилета',
            'Статус',
            'Самолет',
            'Дата создания'
        ];
    }
}
