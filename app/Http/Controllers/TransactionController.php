<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;

class TransactionController extends Controller
{
    public function transaction_show()
    {

        $data['transactions'] = Transaction::where('status', 'active')->get();

        $data['products'] = Product::all();
        return view('pages.sales.show', $data);
    }
    public function store_transaction(Request $request)
    {

        $total_amount = 0;
        $product = Product::find($request->product_id);
        $amount_product = $product->selling_price * $request->quantity;
        $amount         = $amount_product + $request->service ?? 0;
        $total_amount   = $amount - $request->discount ?? 0;
        $transaction    = Transaction::create([
            'product_id'      => $request->product_id,
            'quantity'        => $request->quantity,
            'discount'        => $request->discount ?? 0,
            'service_charges' => $request->service ?? 0,
            'amount'          => $amount_product,
            'total_amount'    => $total_amount,
        ]);
        return response()->json([
            'id' => $transaction->id,
            'product_name' => $transaction->products->item_name,
            'quantity' => $transaction->quantity,
            'discount' => $transaction->discount,
            'service_charges' => $transaction->service_charges,
            'total_amount' => $transaction->total_amount,
            'amount' => $transaction->amount,
        ]);
    }
    public function transaction_delete($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json(['message' => ' deleted successfully!']);
    }
    public function search(Request $request)
    {
        $customerName = $request->input('name');
        $customers = Customer::where('name', 'like', '%' . $request->name . '%')->get();

        if ($customers->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No customers found']);
        }

        return response()->json(['status' => 'success', 'data' => $customers]);
    }
    public function sale_store(Request $request)
    {
        // dd($request->all());
        $data['transactions'] = Transaction::where('status', 'active')->get();

        $transactionIds = [];
        $sale = new Sale();
        foreach ($request->transaction_id as $transactionId) {
            $transactionIds[] = $transactionId;
        }
        $sale->transaction_id = json_encode($transactionIds);
        $sale->customer_id = json_encode($request->customer_id);
        $sale->total_discount = $request->total_discount;
        $sale->total_amount = $request->total_amount;
        $sale->save();
        if (isset($request->customer_id)) {
            foreach ($transactionIds as $transactionId) {
                $transaction = Transaction::find($transactionId);
                if ($transaction) {
                    $transaction->update([
                        'status' => 'inactive',
                    ]);
                }
            }
            $customer = Customer::findOrFail($request->customer_id);
            $customer->cnic = $request->cnic;
            $customer->address = $request->address;
            $customer->name = $request->name;
            $customer->mobile_number = $request->mobile_number;
            if ($request->total_amount > $request->cash) {
                $customer->credit = $request->total_amount - $request->cash;
                $customer->debit = 0;
            } else {
                $customer->debit = $request->cash - $request->total_amount;
                $customer->credit = 0;
            }
            $customer->save();
            return redirect()->route('pages.customer.invoice', ['id' => $sale->id]);
        } else {
            $customer = new Customer();
            foreach ($transactionIds as $transactionId) {
                $transaction = Transaction::find($transactionId);

                if ($transaction) {
                    $transaction->update([
                        'status' => 'inactive',
                    ]);
                }
            }
            $customer->cnic = $request->cnic;
            $customer->address = $request->address;
            $customer->name = $request->name;
            $customer->mobile_number = $request->mobile_number;
            $customer->save();
            $sale->customer_id = $customer->id;
            $sale->save();
            return redirect()->route('pages.customer.invoice', ['id' => $sale->id]);
        }
    }
    public function invoice($id)
    {
        $data['sale'] = Sale::findOrFail($id);
        $transaction_id = json_decode($data['sale']->transaction_id);
        $data['invoices'] = Transaction::with('products')
            ->whereIn('id', $transaction_id)->get();
        // dd($data['invoices']);
        return view('pages.customer.invoice', $data);
    }
    public function generatePDF($id)
    {
        $data['sale'] = Sale::findOrFail($id);
        $transaction_id = json_decode($data['sale']->transaction_id);
        $data['invoices'] = Transaction::with('products')
            ->whereIn('id', $transaction_id)->get();
        // dd($data['invoices']);

        $pdf = PDF::loadView('pages.customer.pdf_generate', $data);

        return $pdf->download('invoice.pdf');
    }
}
