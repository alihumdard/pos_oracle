<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;

class SupplierPaymentController extends Controller
{
    // Yeh payment form dikhaye ga
    public function create()
    {
        $suppliers = Supplier::orderBy('supplier', 'asc')->get();
        return view('pages.purchase.create_payment', compact('suppliers'));
    }

    // Yeh payment ko database mein store karega
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        SupplierPayment::create([
            'supplier_id' => $request->supplier_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'notes' => $request->notes,
        ]);

        // Wapis purchase list par bhej dein success message ke sath
        return redirect()->route('purchase.index')->with('success', 'Payment added successfully!');
    }
}