<?php

namespace App\Http\Controllers;

use App\Models\Sale; 
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier; 

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:sales,id',
            'note' => 'required|string',
        ]);

        $sale = Sale::findOrFail($validated['id']);

        $clean_note = Purifier::clean($validated['note']);

        $sale->note = $clean_note;
        $sale->save();

        return response()->json([
            'status' => 'success',
            'note' => $clean_note, 
        ]);
    }
}