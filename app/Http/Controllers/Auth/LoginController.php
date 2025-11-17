<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }


    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();

        if ($user->role === 'admin') {
            
            return redirect()->intended('/'); 
        } 
        elseif ($user->role === 'student') {
            if ($user->is_active == 1) {
                
                return redirect()->intended('/studentdashabord'); 
            } 
            else {
               Auth::logout();
             return redirect()->intended('/login')->with('success', 'admin not accecept your request');
            }
        } else {
            Auth::logout();
            return back()->withErrors(['role' => 'Invalid role.']);
        }
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ]);
}

}