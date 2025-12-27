<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{

    public function transaction_show($id = null)
    {
        $data['transactions'] = Transaction::where('status', 'active')->get();
        $data['products'] = Product::all();
        $data['editTransactions'] = collect();
        $data['editTransaction'] = null;
        $data['sale'] = null;
        $data['customer'] = null;

        if ($id) {
            $sale = Sale::with('customers')->findOrFail($id);

            $transaction_id_from_sale = $sale->transaction_id;
            $transactionIds = [];

            if (is_array($transaction_id_from_sale)) {
                $transactionIds = $transaction_id_from_sale;
            } elseif (is_string($transaction_id_from_sale)) {
                $decoded_ids = json_decode($transaction_id_from_sale, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_ids)) {
                    $transactionIds = $decoded_ids;
                }
            }

            $data['transactions'] = Transaction::with('products')
                ->whereIn('id', $transactionIds)
                ->get();

            $data['sale'] = $sale;
            $data['customer'] = $sale->customers;

            $data['editTransaction'] = $data['transactions']->first();
        }

        return view('pages.sales.show', $data);
    }
     public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();

            return response()->json(['status' => 'success', 'message' => 'Transaction deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting transaction: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to delete transaction.'], 500);
        }
    }

    public function store_transaction(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->qty < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected quantity exceeds the available stock.'
            ], 400);
        }

        $product->qty -= $request->quantity;
        $product->save();

        $amount_product = $product->selling_price * $request->quantity;
        $serviceCharge = $request->service ?? 0;
        $discountTotal = ($request->discount ?? 0) * $request->quantity;
        $amount = $amount_product + $serviceCharge;
        $total_amount = $amount - $discountTotal;

        $transaction = Transaction::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'discount' => $discountTotal,
            'service_charges' => $serviceCharge,
            'amount' => $amount_product,
            'total_amount' => $total_amount,
        ]);

        return response()->json([
            'status' => 'success',
            'id' => $transaction->id,
            'product_id' => $transaction->product_id,
            'product_name' => $transaction->products->item_name,
            'quantity' => $transaction->quantity,
            'discount' => $transaction->discount,
            'service_charges' => $transaction->service_charges,
            'total_amount' => $transaction->total_amount,
            'amount' => $transaction->amount,
        ]);
    }

    public function update_transaction(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'service' => 'nullable|numeric|min:0',
        ]);

        $transaction = Transaction::findOrFail($id);
        $product = Product::findOrFail($request->product_id);

        
        $product->qty += $transaction->quantity; 
        if ($product->qty < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected quantity exceeds the available stock.'
            ], 400);
        }

        $product->qty -= $request->quantity;
        $product->save();

        $amount_product = $product->selling_price * $request->quantity;
        $amount = $amount_product + ($request->service ?? 0);
        $total_amount = $amount - (($request->discount ?? 0) * $request->quantity);

        $transaction->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'discount' => $request->discount * $request->quantity,
            'service_charges' => $request->service ?? 0,
            'amount' => $amount_product,
            'total_amount' => $total_amount,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction updated successfully.',
            'transaction' => [
                'id' => $transaction->id,
                'product_id' => $transaction->product_id,
                'quantity' => $transaction->quantity,
                'discount' => $transaction->discount,
                'service_charges' => $transaction->service_charges,
                'amount' => $transaction->amount,
                'total_amount' => $transaction->total_amount
            ]
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
        $query = Customer::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('mobile_number')) {
            $query->where('mobile_number', 'like', '%' . $request->mobile_number . '%');
        }

        $customers = $query->get();

        if ($customers->isEmpty()) {
            return response()->json(['status' => 'no_results']);
        }

        return response()->json([
            'status' => 'success',
            'data' => $customers,
        ]);
    }

    public function sale_store(Request $request)
    {
        if ($request->id && !empty($request->note)) {
            $sale = Sale::findOrFail($request->id);
            $sale->update(['note' => $request->note]);
            return response()->json(['status' => 'success', 'note' => $sale->note]);
        }

        $rules = [
            'cash' => 'required',
        ];
        // if (!empty($request->customer_id)) {
        //     $rules['cnic'] = 'unique:customers,cnic';
        // }
        $request->validate($rules);

        $transactionIds = $request->transaction_id ?? [];

        $sale = new Sale();
        $sale->transaction_id = json_encode($transactionIds);
        $sale->total_discount = $request->total_discount;
        $sale->total_amount = $request->total_amount;
        $sale->cash = $request->cash;

        
        if (isset($request->customer_id)) {
            $customer = Customer::findOrFail($request->customer_id);
        } else {
            $customer = new Customer();
        }

       
        $customer->cnic = $request->cnic ?? '';
        $customer->address = $request->address ?? '';
        $customer->name = $request->name ?? '';
        $customer->mobile_number = $request->mobile_number ?? '';

        $previous_credit = $customer->credit ?? 0;
        $previous_debit = $customer->debit ?? 0;

        
        if ($request->total_amount > $request->cash) {
            $customer->credit += $request->total_amount - $request->cash;
        } else {
            $customer->debit += $request->cash - $request->total_amount;
        }

        if ($customer->debit > 0) {
            if ($customer->credit >= $customer->debit) {
                $customer->credit -= $customer->debit;
                $customer->debit = 0;
            } else {
                $customer->debit -= $customer->credit;
                $customer->credit = 0;
            }
        }

        $customer->save();

        $sale->customer_id = $customer->id;
        $sale->save();

        foreach ($transactionIds as $transactionId) {
            $transaction = Transaction::find($transactionId);
            if ($transaction) {
                $transaction->update(['status' => 'inactive']);
            }
        }

        
       return response()->json([
            'status' => 'success',
            'redirect_url' => route('pages.customer.invoice', [
                'id' => $sale->id,
                'cash' => $request->cash,
                'credit' => $previous_credit,
                'debit' => $previous_debit
            ])
        ]);
    }

    public function invoice($id = null, $cash = null, $credit = null, $debit = null)
    {
        $data['sale'] = Sale::with('customers')->findOrFail($id);
        $data['cash'] = $cash;
        $data['credit'] = $credit;
        $data['debit'] = $debit;

        $transaction_id_from_sale = $data['sale']->transaction_id;
        $transaction_ids = [];

        if (is_array($transaction_id_from_sale)) {
            $transaction_ids = $transaction_id_from_sale;
        } elseif (is_string($transaction_id_from_sale)) {
            $decoded_ids = json_decode($transaction_id_from_sale, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_ids)) {
                $transaction_ids = $decoded_ids;
            }
        }

        $data['invoices'] = Transaction::with('products')
            ->whereIn('id', $transaction_ids)->get();

        return view('pages.customer.invoice', $data);
    }

    public function generatePDF($id)
    {
        $data['sale'] = Sale::with('customers')->findOrFail($id);

        $transaction_id_from_sale = $data['sale']->transaction_id;
        $transaction_ids = [];

        if (is_array($transaction_id_from_sale)) {
            $transaction_ids = $transaction_id_from_sale;
        } elseif (is_string($transaction_id_from_sale)) {
            $decoded_ids = json_decode($transaction_id_from_sale, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_ids)) {
                $transaction_ids = $decoded_ids;
            }
        }

        $data['invoices'] = Transaction::with('products')
            ->whereIn('id', $transaction_ids)->get();

        $pdf = Pdf::loadView('pages.customer.pdf_generate', $data);

        return $pdf->download('invoice.pdf');
    }

   public function update_sale(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
        $customerId = $request->customer_id;

        if (!$customerId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer ID is required for updating.'
            ], 422);
        }

        $customer = Customer::findOrFail($customerId);
        $transactionIds = $request->transaction_id ?? [];

        $rules = [
            'cash' => 'required|numeric|min:0',
        ];

        if ($request->cnic && $request->cnic !== $customer->cnic) {
            $rules['cnic'] = ['nullable', Rule::unique('customers', 'cnic')];
        }

        $request->validate($rules);

        $customer->name = $request->name ?? '';
        $customer->address = $request->address ?? '';
        $customer->mobile_number = $request->mobile_number ?? '';
        $customer->save();

        $previous_credit = $customer->credit ?? 0;
        $previous_debit = $customer->debit ?? 0;

        if ($sale->total_amount > $sale->cash) {
            $customer->credit -= $sale->total_amount - $sale->cash;
        } else {
            $customer->debit -= $sale->cash - $sale->total_amount;
        }

        $sale->transaction_id = json_encode($transactionIds);
        $sale->total_discount = $request->total_discount;
        $sale->total_amount = $request->total_amount;
        $sale->cash = $request->cash;
        $sale->customer_id = $customer->id;
        $sale->save();

        if ($request->total_amount > $request->cash) {
            $customer->credit += $request->total_amount - $request->cash;
        } else {
            $customer->debit += $request->cash - $request->total_amount;
        }

        if ($customer->debit > 0) {
            if ($customer->credit >= $customer->debit) {
                $customer->credit -= $customer->debit;
                $customer->debit = 0;
            } else {
                $customer->debit -= $customer->credit;
                $customer->credit = 0;
            }
        }

        $customer->save();

        foreach ($transactionIds as $transactionId) {
            $transaction = Transaction::find($transactionId);
            if ($transaction) {
                $transaction->update(['status' => 'inactive']);
            }
        }

        return response()->json([
            'status' => 'success',
            'redirect_url' => route('pages.customer.invoice', [
                'id' => $sale->id,
                'cash' => $request->cash,
                'credit' => $previous_credit,
                'debit' => $previous_debit
            ])
        ]);
    }


}
