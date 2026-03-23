<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function submitLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|max:150|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->get('remember'))) {
            return redirect()->route('home')->with('success', 'Login successful.');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    public function submitRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'email' => 'required|string|max:150|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return redirect()->route('login')->with('success', 'Registration successful.');
        }

        return redirect()->back()->with('error', 'Failed to register.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('questions.index')->with('success', 'Logout successful.');
    }
}
