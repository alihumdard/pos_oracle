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
        if ($product->qty < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected quantity exceeds the available stock.'
            ], 400);
        }
        $product->qty -= $request->quantity;
        $product->save();
        $amount_product = $product->selling_price * $request->quantity;
        $amount         = $amount_product + $request->service ?? 0;
        $total_amount   = $amount - ($request->discount * $request->quantity) ?? 0;
        $transaction    = Transaction::create([
            'product_id'      => $request->product_id,
            'quantity'        => $request->quantity,
            'discount'        => $request->discount * $request->quantity ?? 0,
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
        $transaction   = Transaction::findOrFail($id);
        $product       = Product::find($transaction->product_id);
        $product->qty += $transaction->quantity;
        $product->save();

        $transaction->delete();
        return response()->json(['message' => ' deleted successfully!']);
    }
    public function search(Request $request)
    {
        // $customerName = $request->input('name');
        $customers = Customer::where('name', 'like', '%' . $request->name . '%')->get();

        if ($customers->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No customers found']);
        }

        return response()->json(['status' => 'success', 'data' => $customers]);
    }
    public function sale_store(Request $request)
    {
        // dd($request->all());
        if ($request->id && !empty($request->note)) {
            $sale_id = Sale::findorfail($request->id);
            $sale_id->update([
                'note' => $request->note,
            ]);
            return response()->json(
                ['status' => 'success', 'note' => $sale_id->note],
            );
        }
        $rules = [
            // 'name' => 'required',
            // 'mobile_number' => 'required',
            // 'address' => 'required',
            'cash' => 'required',
        ];
        if (empty($request->customer_id)) {
            $rules['cnic'] = 'unique:customers,cnic';
        }
        $request->validate($rules);
        $data['transactions'] = Transaction::where('status', 'active')->get();
        if (isset($request->customer_id)) {
            $transactionIds = [];
            $sale = new Sale();
            foreach ($request->transaction_id as $transactionId) {
                $transactionIds[] = $transactionId;
            }
            $sale->transaction_id = json_encode($transactionIds);
            $sale->customer_id = $request->customer_id ?? '';
            $sale->total_discount = $request->total_discount;
            $sale->cash = $request->cash;
            $sale->total_amount = $request->total_amount;
            $sale->save();
            foreach ($transactionIds as $transactionId) {
                $transaction = Transaction::find($transactionId);
                if ($transaction) {
                    $transaction->update([
                        'status' => 'inactive',
                    ]);
                }
            }
            $customer = Customer::findOrFail($request->customer_id);
            $customer->cnic = $request->cnic ?? '';
            $customer->address = $request->address ?? '';
            $customer->name = $request->name ?? '';
            $customer->mobile_number = $request->mobile_number ??'';
            $previous_credit = $customer->credit ?? '';
            $previous_debit = $customer->debit ?? '';
            if ($request->total_amount > $request->cash) {
                $customer->credit += $request->total_amount - $request->cash;
            } else {
                $customer->debit += $request->cash - $request->total_amount;
            }
            if ($customer->debit) {
                if ($customer->credit >= $customer->debit) {
                    $customer->credit -= $customer->debit;
                    $customer->debit = 0;
                } else {
                    $customer->debit -= $customer->credit;
                    $customer->credit = 0;
                }
            }
            $customer->save();
            return redirect()->route('pages.customer.invoice', ['id' => $sale->id, 'cash' => $request->cash, 'credit' => $previous_credit, 'debit' => $previous_debit]);
        } else {
            $transactionIds = [];
            $sale = new Sale();
            foreach ($request->transaction_id as $transactionId) {
                $transactionIds[] = $transactionId;
            }
            $sale->transaction_id = json_encode($transactionIds);
            $sale->total_discount = $request->total_discount;
            $sale->total_amount = $request->total_amount;
            $sale->cash = $request->cash;
            $customer = new Customer();
            foreach ($transactionIds as $transactionId) {
                $transaction = Transaction::find($transactionId);
                if ($transaction) {
                    $transaction->update([
                        'status' => 'inactive',
                    ]);
                }
            }
            $customer->cnic = $request->cnic ?? '';
            $customer->address = $request->address ?? '';
            $customer->name = $request->name ?? '';
            $customer->mobile_number = $request->mobile_number ?? '';
            $previous_credit = 0;
            $previous_debit = 0;
            if ($request->total_amount > $request->cash) {
                $customer->credit += $request->total_amount - $request->cash;
            } else {
                $customer->debit += $request->cash - $request->total_amount;
            }
            if ($customer->debit) {
                if ($customer->credit >= $customer->debit) {
                    $customer->credit -= $customer->debit;
                    $customer->debit = 0;
                } else {
                    $customer->debit -= $customer->credit;
                    $customer->credit = 0;
                }
            }
            $customer->save();
            $sale->customer_id = $customer->id ?? '';
            $sale->save();
            return redirect()->route('pages.customer.invoice', ['id' => $sale->id, 'cash' => $request->cash, 'credit' => $previous_credit, 'debit' => $previous_debit]);
        }
    }
    public function invoice($id = null, $cash = null, $credit = null, $debit = null)
    {
        $data['sale'] = Sale::with('customers')->findOrFail($id);
        // dd($data['sale']);
        $data['cash'] = $cash;
        $data['credit'] = $credit;
        $data['debit'] = $debit;
        $transaction_id = json_decode($data['sale']->transaction_id);
        $data['invoices'] = Transaction::with('products')
            ->whereIn('id', $transaction_id)->get();
        // $data['sale'];
        return view('pages.customer.invoice', $data);
    }
    public function generatePDF($id)
    {
        $data['sale'] = Sale::with('customers')->findOrFail($id);
        $transaction_id = json_decode($data['sale']->transaction_id);
        $data['invoices'] = Transaction::with('products')
            ->whereIn('id', $transaction_id)->get();


        $pdf = PDF::loadView('pages.customer.pdf_generate', $data);

        return $pdf->download('invoice.pdf');
    }
}
