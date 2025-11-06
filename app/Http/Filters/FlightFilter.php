<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class FlightFilter extends AbstractFilter
{
    public const FLIGHTNUMBER = 'flight_number';

    public const DEPARTURE = 'departure';

    public const ARRIVAL = 'arrival';

    public const FLIGHT_STATUS_ID = 'flight_status_id';

    public const AIRCRAFT_ID = 'aircraft_id';



    protected function getCallbacks(): array
    {
        return [
            self::FLIGHTNUMBER => [$this, 'flight_number'],
            self::DEPARTURE => [$this, 'departure'],
            self::ARRIVAL => [$this, 'arrival'],
            self::FLIGHT_STATUS_ID => [$this, 'flight_status_id'],
            self::AIRCRAFT_ID => [$this, 'aircraft_id'],
        ];
    }

    public function flight_number(Builder $builder, $value)
    {
        $builder->where('flight_number', 'like', "%{$value}%");
    }

    public function departure(Builder $builder, $value)
    {
        $builder->where('departure', 'like', "%{$value}%");
    }
    public function arrival(Builder $builder, $value)
    {
        $builder->where('arrival', 'like', "%{$value}%");
    }
    public function flight_status_id(Builder $builder, $value)
    {
        $builder->where('flight_status_id', 'like', "%{$value}%");
    }

    public function aircraft_id(Builder $builder, $value)
    {
        $builder->where('aircraft_id', 'like', "%{$value}%");
    }
}
