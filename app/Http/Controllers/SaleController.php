<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
     public function index()
    {
        $sales = Sale::latest()->paginate(15); // Example: Get latest sales, paginated
        return view('sales.index', ['sales' => $sales]); // Pass sales to a view named 'sales.index'
    }
}
