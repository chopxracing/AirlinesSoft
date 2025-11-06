<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class EmployeeFilter extends AbstractFilter
{
    public const NAME = 'name';
    public const USERNAME = 'username';
    public const SURNAME = 'surname';

    public const STATUS_ID = 'status_id';

    public const POSITION_ID = 'position_id';

    public const CLEARANCE_ID = 'clearance_id';


    protected function getCallbacks(): array
    {
        return [
            self::NAME => [$this, 'name'],
            self::USERNAME => [$this, 'username'],
            self::SURNAME => [$this, 'surname'],
            self::STATUS_ID => [$this, 'status_id'],
            self::POSITION_ID => [$this, 'position_id'],
            self::CLEARANCE_ID => [$this, 'clearance_id'],


        ];
    }

    public function name(Builder $builder, $value)
    {
        $builder->where('name', 'like', "%{$value}%");
    }

    public function username(Builder $builder, $value)
    {
        $builder->where('username', 'like', "%{$value}%");
    }

    public function surname(Builder $builder, $value)
    {
        $builder->where('surname', 'like', "%{$value}%");
    }
    public function status_id(Builder $builder, $value)
    {
        $builder->where('status_id', $value);
    }
    public function position_id(Builder $builder, $value)
    {
        $builder->where('position_id', $value);
    }
    public function clearance_id(Builder $builder, $value)
    {
        $builder->where('clearance_id', $value);
    }
}
