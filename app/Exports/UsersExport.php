<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with(['position', 'clearance'])
            ->select(
                'id',
                'name',
                'surname',
                'email',
                'position_id',
                'clearance_id',
                'phone',
                'username',
                'idoc_series',
                'idoc_number',
                'time_in_air',
            )->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'email' => $user->email,
                    'position_id' => $user->position->name,
                    'clearance_id' => $user->clearance->name,
                    'phone' => $user->phone,
                    'username' => $user->username,
                    'idoc_series' => $user->idoc_series,
                    'idoc_number' => $user->idoc_number,
                    'time_in_air' => $user->time_in_air,
                ];
            });
    }

        public function headings(): array
    {
        return [
            'ID',
            'Имя',
            'Фамилия',
            'Почта',
            'Должность',
            'Допуск',
            'Номер телефона',
            'Имя пользователя в системе',
            'Паспорт серия',
            'Паспорт номер',
            'Налет часов'
        ];
    }
}
