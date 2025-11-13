<?php

namespace App\Http\Controllers;

use App\Exports\AircraftsExport;
use App\Exports\FlightsExport;
use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('reports.report', compact('users'));
    }

    public function usersexport()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function flightsexport()
    {
        return Excel::download(new FlightsExport, 'flights.xlsx');
    }
    public function aircraftsexport()
    {
        return Excel::download(new AircraftsExport, 'aircrafts.xlsx');
    }

}
