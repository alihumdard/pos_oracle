<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ManualPayment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Foundation\Console\MailMakeCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function customer_show()
    {
        $data['customers'] = Customer::orderBy('created_at', 'desc')->get();
        return view('pages.customer.show', $data);
    }
    public function view($id)
    {
        $data['manual_customers'] = Customer::with(['manualPayments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        $data['customer'] = Customer::with('sales')->findOrFail($id);
        $data['sales'] = Sale::where('customer_id', $id)->orderBy('created_at', 'desc')->get();
        return view('pages.customer.view', $data);
    }
    public function detail($id)
    {
        $sale = Sale::findOrFail($id);
        $transactionIds = json_decode($sale->transaction_id, true);
        $data['transactions'] = Transaction::with('products')->whereIn('id', $transactionIds)->get();

        return view('pages.sales.detail', $data);
    }
    public function customer_add(Request $request)
    {
        if ($request->action != 'youGot' && $request->action != 'youGive') {
            $validator = Validator::make($request->all(), [
                'cnic' => 'required|string|max:15|unique:customers,cnic',
                'address' => 'required|string|max:255',
                'name' => 'required|string|max:100',
                'mobile_no' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $customer = new Customer();
            $customer->cnic = $request->cnic;
            $customer->address = $request->address;
            $customer->name = $request->name;
            $customer->mobile_number = $request->mobile_no;
            $customer->credit = $customer->credit ?? 0;
            $customer->debit = $customer->debit ?? 0;
            $customer->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully',
                'data' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                ],
            ], 201);
        } else {
            $customer = Customer::findorfail($request->customer_id);
            $payment = $request->payment;
            if ($request->action == 'youGive') {
                if ($customer->debit > 0) {
                    if ($payment >= $customer->debit) {
                        $remainingCredit = $payment - $customer->debit;
                        $customer->debit = 0;
                        $customer->credit += $remainingCredit;
                    } else {
                        $customer->debit -= $payment;
                    }
                } else {
                    $customer->credit += $payment;
                }
                $customer->save();
                $manual_payment = new ManualPayment();
                $manual_payment->payment_type = 'You Give';
                $manual_payment->payment = $payment;
                $customer->manualPayments()->save($manual_payment);

                return response()->json([
                    'status' => 'success',
                ]);
            } elseif ($request->action == 'youGot') {
                if ($customer->credit > 0) {
                    if ($payment >= $customer->credit) {
                        $remainingDebit = $payment - $customer->credit;
                        $customer->credit = 0;
                        $customer->debit += $remainingDebit;
                    } else {
                        $customer->credit -= $payment;
                    }
                } else {
                    $customer->debit += $payment;
                }
                $customer->save();
                $manual_payment = new ManualPayment();
                $manual_payment->payment_type = 'You Got';
                $manual_payment->payment = $payment;
                $customer->manualPayments()->save($manual_payment);
                return response()->json([
                    'status' => 'success',

                ]);
            }
        }
    }

    public function Customer_edit($id)
    {
        $Customer = Customer::findOrFail($id);
        return response()->json($Customer);
    }

    public function Customer_update(Request $request, $id)
    {
        $Customer = Customer::findOrFail($id);
        $Customer->Customer = $request->Customer;
        $Customer->contact_person = $request->contact_person;
        $Customer->address = $request->address;
        $Customer->contact_no = $request->contact_no;
        $Customer->note = $request->note;
        $Customer->save();

        return response()->json(['message' => 'Customer updated successfully!']);
    }

    // Delete Customer
    public function Customer_delete($id)
    {
        $Customer = Customer::findOrFail($id);
        $Customer->delete();

        return response()->json(['message' => 'Customer deleted successfully!']);
    }
    public function customer_filter(Request $request)
    {
        $query = Customer::query();
        $sortOrder = $request->input('sort_order', 'asc');
    
        if ($request->has('filter_debit')) {
            $query->orderBy('debit', $sortOrder); 
        }
    
        if ($request->has('filter_credit')) {
            $query->orderBy('credit', $sortOrder); 
        }

        if ($request->has('hide_zero_balance')) {
            $query->where(function ($q) {
                $q->where('debit', '!=', 0)->orWhere('credit', '!=', 0);
            });
        }

        $data['customers'] = $query->get();
    
        return view('pages.customer.show', $data);
    } 

    public function salesSummary($id)
    {
        
        $customer = Customer::with('sales')->findOrFail($id);

        $summary = [
            'total_sales' => $customer->sales->count(),
            'total_amount' => $customer->sales->sum('total_amount'),
            'total_cash' => $customer->sales->sum('cash'),
            'total_discount' => $customer->sales->sum('total_discount'),
            'credit' => $customer->credit,
            'debit' => $customer->debit,
        ];

        return view('pages.customer.sales_summary', compact('customer', 'summary'));
    }

}
