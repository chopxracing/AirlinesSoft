<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm(){

        return view('auth.login');
    }
    public function login(Request $request){
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if(Auth::attempt($data)){
            return redirect('/');
        }
        return back()->withErrors(['username' => 'Неверные данные']);
    }
    public function logout(){
        Auth::logout();
        return redirect('/login');
    }


}
