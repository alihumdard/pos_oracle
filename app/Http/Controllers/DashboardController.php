<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    { 
      if (!Auth::check()) {
        return redirect()->back();
    }
            return view('pages.dashboard'); 
        
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
            'message' => 'All commands executed successfully!',
            'migrate_output' => Artisan::output(),
            'cache_clear_output' => Artisan::output(),
            'route_clear_output' => Artisan::output(),
            'config_clear_output' => Artisan::output(),
            'view_clear_output' => Artisan::output(),
        ]);
    }
}
