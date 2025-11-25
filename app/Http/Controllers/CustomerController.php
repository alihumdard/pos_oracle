<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerRecoveryDate;
use App\Models\ManualPayment;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Http\Request;
// --- IMPORTS ADDED FOR WHATSAPP & LOGGING ---
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function customer_show()
    {
        // 1. Pehle Database se customers fetch karein
        $customers = Customer::with(['sales', 'activeRecoveryDate'])
            ->where(function ($query) {
                $query->where('debit', '>', 0)
                    ->orWhere('credit', '>', 0);
            })
            ->whereHas('sales')
            ->get();

        $data['customers'] = $customers->sortBy(function ($customer) {
            return $customer->activeRecoveryDate
                ? $customer->activeRecoveryDate->recovery_date
                : '9999-12-31';
        });

        return view('pages.customer.show', $data);
    }
    public function view($id)
    {
        $data['manual_customers'] = Customer::with(['manualPayments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Fixed: Eager load recoveryDates ordered by latest created first
        // This ensures the active date logic in the view works correctly
        $data['customer'] = Customer::with(['sales', 'recoveryDates' => function ($q) {
            $q->orderBy('id', 'desc');
        }])->findOrFail($id);

        $data['sales'] = Sale::where('customer_id', $id)->orderBy('created_at', 'desc')->get();
        return view('pages.customer.view', $data);
    }

    public function detail($id)
    {
        $sale = Sale::findOrFail($id);

        $transaction_id_from_sale = $sale->transaction_id;
        $transactionIds           = [];

        if (is_array($transaction_id_from_sale)) {
            $transactionIds = $transaction_id_from_sale;
        } elseif (is_string($transaction_id_from_sale)) {
            $decoded_ids = json_decode($transaction_id_from_sale, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_ids)) {
                $transactionIds = $decoded_ids;
            }
        }

        $data['transactions'] = Transaction::with('products')->whereIn('id', $transactionIds)->get();

        return view('pages.sales.detail', $data);
    }

    public function customer_add(Request $request)
    {
        if ($request->action != 'youGot' && $request->action != 'youGive') {
            $validator = Validator::make($request->all(), [
                'cnic'      => 'nullable|string|max:20',
                'address'   => 'required|string|max:255',
                'name'      => 'required|string|max:100',
                'mobile_no' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $customer                = new Customer();
            $customer->cnic          = empty($request->cnic) ? null : $request->cnic;
            $customer->address       = $request->address;
            $customer->name          = $request->name;
            $customer->mobile_number = $request->mobile_no;
            $customer->credit        = $customer->credit ?? 0;
            $customer->debit         = $customer->debit ?? 0;
            $customer->save();
            return response()->json([
                'status'  => 'success',
                'message' => 'Customer created successfully',
                'data'    => [
                    'id'   => $customer->id,
                    'name' => $customer->name,
                ],
            ], 201);
        } else {
            $customer = Customer::findorfail($request->customer_id);
            $payment  = $request->payment;
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
                $manual_payment               = new ManualPayment();
                $manual_payment->payment_type = 'You Give';
                $manual_payment->payment      = $payment;
                $manual_payment->note         = $request->note;
                $customer->manualPayments()->save($manual_payment);

                return response()->json([
                    'status' => 'success',
                ]);
            } elseif ($request->action == 'youGot') {
                if ($customer->credit > 0) {
                    if ($payment >= $customer->credit) {
                        $remainingDebit   = $payment - $customer->credit;
                        $customer->credit = 0;
                        $customer->debit += $remainingDebit;
                    } else {
                        $customer->credit -= $payment;
                    }
                } else {
                    $customer->debit += $payment;
                }
                $customer->save();
                $manual_payment               = new ManualPayment();
                $manual_payment->payment_type = 'You Got';
                $manual_payment->payment      = $payment;
                $manual_payment->note         = $request->note;
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
        $Customer                 = Customer::findOrFail($id);
        $Customer->Customer       = $request->Customer;
        $Customer->contact_person = $request->contact_person;
        $Customer->address        = $request->address;
        $Customer->contact_no     = $request->contact_no;
        $Customer->note           = $request->note;
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
        // Use eager loading to prevent N+1 issues
        $query = Customer::with(['sales', 'activeRecoveryDate']);

        // 1. Handle Recovery Status Filters (Pending, Today, Upcoming)
        if ($request->has('recovery_status')) {
            $status = $request->recovery_status;
            $today  = \Carbon\Carbon::today()->format('Y-m-d');

            // CASE 1: "No Date"
            if ($status == 'no_date') {
                $query->whereDoesntHave('activeRecoveryDate')
                    ->where(function ($q) {
                        $q->where('debit', '>', 0)
                            ->orWhere('credit', '>', 0);
                    });
            }
            // CASE 2: Pending, Today, Upcoming
            else {
                $query->whereHas('activeRecoveryDate', function ($q) use ($status, $today) {
                    $q->where('is_active', 1);

                    if ($status == 'pending') {
                        $q->where('recovery_date', '<', $today);
                    } elseif ($status == 'today') {
                        $q->where('recovery_date', '=', $today);
                    } elseif ($status == 'upcoming') {
                        $q->where('recovery_date', '>', $today);
                    }
                });
            }
        }

        // 2. Handle "Hide Zero Balance"
        if ($request->has('hide_zero_balance')) {
            $query->where(function ($q) {
                $q->where('debit', '!=', 0)->orWhere('credit', '!=', 0);
            });
        }

        // 3. Handle Sorting Logic
        // Pehle check karein ke kya user ne Debit ya Credit se sort karne ko kaha hai?
        $hasManualSort = false;
        $sortOrder = $request->input('sort_order', 'asc');

        if ($request->has('filter_debit')) {
            $query->orderBy('debit', $sortOrder);
            $hasManualSort = true;
        }

        if ($request->has('filter_credit')) {
            $query->orderBy('credit', $sortOrder);
            $hasManualSort = true;
        }

        // Data fetch karein
        $customers = $query->get();

        // 4. CUSTOM DATE SORTING (Agar user ne Debit/Credit sort select nahi kiya)
        // Yeh wohi logic hai jo customer_show mein lagayi thi
        if (!$hasManualSort) {
            $customers = $customers->sortBy(function ($customer) {
                return $customer->activeRecoveryDate 
                    ? $customer->activeRecoveryDate->recovery_date 
                    : '9999-12-31'; // No date walay end pe
            });
        }

        $data['customers'] = $customers;

        return view('pages.customer.show', $data);
    }

    public function salesSummary($id)
    {
        $customer = Customer::with('sales')->findOrFail($id);

        $summary = [
            'total_sales'    => $customer->sales->count(),
            'total_amount'   => $customer->sales->sum('total_amount'),
            'total_cash'     => $customer->sales->sum('cash'),
            'total_discount' => $customer->sales->sum('total_discount'),
            'credit'         => $customer->credit,
            'debit'          => $customer->debit,
        ];

        return view('pages.customer.sales_summary', compact('customer', 'summary'));
    }

    // ============================================================
    // RECOVERY DATES & WHATSAPP LOGIC
    // ============================================================

    public function addRecoveryDate(Request $request)
    {
        // 1. Deactivate ALL previous dates for this customer
        CustomerRecoveryDate::where('customer_id', $request->customer_id)
            ->update(['is_active' => 0]);

        // 2. Create the new active date
        CustomerRecoveryDate::create([
            'customer_id'   => $request->customer_id,
            'recovery_date' => $request->date,
            'is_active'     => 1,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Date added successfully']);
    }

    public function deleteRecoveryDate(Request $request)
    {
        CustomerRecoveryDate::where('id', $request->id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Date deleted']);
    }

    // --- NEW FUNCTION: Mark as Received ---
    public function markRecoveryReceived(Request $request)
    {
        $recovery = CustomerRecoveryDate::find($request->id);
        if ($recovery) {
            $recovery->is_received = 1;
            $recovery->save();
            return response()->json(['status' => 'success', 'message' => 'Marked Received']);
        }
        return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
    }
}
