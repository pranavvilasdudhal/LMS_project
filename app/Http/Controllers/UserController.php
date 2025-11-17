<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function profile()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('profile', compact('user'));
        } elseif ($user->role === 'student') {
            return view('student_profileview', compact('user'));
        } else {
            return redirect('/login')->withErrors(['role' => 'Invalid role']);
        }
    }

   
    public function edit()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('profile_edit', compact('user'));
        } elseif ($user->role === 'student') {
            return view('student_profile_edit', compact('user'));
        } else {
            return redirect('/login')->withErrors(['role' => 'Invalid role']);
        }
    }

 
    public function view()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('student_profileview', compact('user')); 
        } elseif ($user->role === 'student') {
            return view('student_profileview', compact('user')); 
        } else {
            return redirect('/login')->withErrors(['role' => 'Invalid role']);
        }
    }

  
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'nullable|in:admin,student',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        } 

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
