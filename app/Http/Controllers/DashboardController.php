<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return redirect()->route('reports.dashboard');
    }

    public function runMigrations()
    {
        Artisan::call('migrate:fresh --seed');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return response()->json([
            'status' => 'success',
            'message' => 'Development commands (migrate:fresh --seed, cache clears) executed successfully!',
        ]);
    }
}
