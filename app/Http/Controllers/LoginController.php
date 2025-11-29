<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,

        ];

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput();
        }
    }
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
