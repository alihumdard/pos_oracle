<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        $user=session()->has('login');
        if($user)
        {
            return redirect()->route('dashboard');
        }
        else
        {
            return view('login'); 
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $existingRecord = User::where('email', $request->email)->where('password', $request->password)->first();
        if ($existingRecord) {
            $request->session()->put('login', $existingRecord->id);
            return redirect()->route('dashboard'); 
        }
        else{
            return redirect()->back()->with('message','Invalid Email or Password ');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }
            public function logout()
        {
            session()->flush();
            return redirect()->route('login');
        }

}
