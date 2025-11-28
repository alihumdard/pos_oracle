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
        // 1. Data Fetch:
        $customers = Customer::with(['sales', 'activeRecoveryDate'])
            ->where(function ($query) {
                // FIX: Use whereRaw and ABS() to handle floating point issues strictly
                $query->whereRaw('ABS(debit) > 0.00')
                    ->orWhereRaw('ABS(credit) > 0.00');
            })
            ->get();

        // 2. Calculate Totals
        $data['totalDebit']  = $customers->sum('debit');
        $data['totalCredit'] = $customers->sum('credit');

        // 3. Sorting (Rest of the code remains the same)
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
        // 1. Validation (Highly Recommended)
        $request->validate([
            'name'      => 'required|string|max:100',
            'mobile_no' => 'required|string|max:20',
            'address'   => 'required|string|max:255',
            'cnic'      => 'nullable|string|max:20',
        ]);

        // 2. Locate and Update the Customer
        $customer = Customer::findOrFail($id);

                                                        // FIX: Map request keys (from frontend form) to database columns
        $customer->name          = $request->name;      // Correct: name from form maps to name column
        $customer->mobile_number = $request->mobile_no; // Correct: mobile_no from form maps to mobile_number column (assuming your DB column is mobile_number)
        $customer->address       = $request->address;
        $customer->cnic          = $request->cnic;

        $customer->save();

        return response()->json(['message' => 'Customer updated successfully!']);
    }

    public function customer_filter(Request $request)
    {
        // Use eager loading to prevent N+1 issues
        $query = Customer::with(['sales', 'activeRecoveryDate']);

        // Flag to track if a filter that alters the default view is applied
        $isFilteringActive = false;

        // 1. Handle Recovery Status Filters (Pending, Today, Upcoming, No Date)
        if ($request->has('recovery_status')) {
            $status            = $request->recovery_status;
            $today             = \Carbon\Carbon::today()->format('Y-m-d');
            $isFilteringActive = true;

            // CASE 1: "No Date"
            if ($status == 'no_date') {
                $query->whereDoesntHave('activeRecoveryDate')
                    ->where(function ($q) {
                        // Show only No Date customers who still have a positive balance
                        $q->whereRaw('ABS(debit) > 0.00')
                            ->orWhereRaw('ABS(credit) > 0.00');
                    });
            }

            // CASE 2: Pending, Today, Upcoming (The section with the critical fix)
            else {
                $query->whereHas('activeRecoveryDate', function ($q) use ($status, $today) {
                    // Step 1: Filter by Recovery Date status
                    $q->where('is_active', 1);

                    if ($status == 'pending') {
                        $q->where('recovery_date', '<', $today);
                    } elseif ($status == 'today') {
                        $q->where('recovery_date', '=', $today);
                    } elseif ($status == 'upcoming') {
                        $q->where('recovery_date', '>', $today);
                    }
                })
                // *** FIX: STRICT BALANCE CHECK ADDED ***
                // Step 2: Ensure that customers found in Step 1 ALSO have an outstanding balance.
                    ->where(function ($q) {
                        $q->whereRaw('ABS(debit) > 0.00')
                            ->orWhereRaw('ABS(credit) > 0.00');
                    });
                // *** END FIX ***
            }
        }

        // 2. Handle "Hide Zero Balance" Filter
        if ($request->has('hide_zero_balance')) {
            $query->where(function ($q) {
                // Explicitly show only customers not equal to zero balance
                $q->whereRaw('ABS(debit) > 0.00')->orWhereRaw('ABS(credit) > 0.00');
            });
            $isFilteringActive = true;
        }

        // 3. Default Filter (If no filter is applied, apply the Hide Zero Balance rule)
        if (! $isFilteringActive) {
            $query->where(function ($q) {
                $q->whereRaw('ABS(debit) > 0.00')
                    ->orWhereRaw('ABS(credit) > 0.00');
            });
        }

        // 4. Handle Sorting Logic
        $hasManualSort = false;
        $sortOrder     = $request->input('sort_order', 'asc');

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

        $data['totalDebit']  = $customers->sum('debit');
        $data['totalCredit'] = $customers->sum('credit');

        // 5. CUSTOM DATE SORTING (Agar user ne Debit/Credit sort select nahi kiya)
        if (! $hasManualSort) {
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

    public function fetchManualPayment($id)
    {
        // Ensure you use the correct model name (ManualPayment)
        $payment = ManualPayment::find($id);

        if (! $payment) {
            // Return a 404 response if the payment is not found
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Return the payment data as JSON to the frontend
        return response()->json($payment);
    }

    public function updateManualPayment(Request $request)
    {
        // Basic Validation (ensure payment and type are present)
        $request->validate([
            'id'           => 'required|exists:manual_payments,id',
            'customer_id'  => 'required|exists:customers,id',
            'payment'      => 'required|numeric|min:0',
            'payment_type' => 'required|in:You Give,You Got',
        ]);

        // 1. Fetch Records
        $oldPayment = ManualPayment::findOrFail($request->id);
        $customer   = Customer::findOrFail($request->customer_id);

        // Get old values before updating
        $oldType   = $oldPayment->payment_type;
        $oldAmount = $oldPayment->payment;

        // --- Start Transaction (Crucial for Balance Integrity) ---
        \DB::beginTransaction();

        try {
            // 2. REVERSE THE OLD PAYMENT'S EFFECT
            // If the original payment was 'You Give' (Credit to Customer), reverse it by Debiting the customer.
            if ($oldType === 'You Give') {
                $customer->credit -= $oldAmount;
            }
            // If the original payment was 'You Got' (Debit to Customer), reverse it by Crediting the customer.
            elseif ($oldType === 'You Got') {
                $customer->debit -= $oldAmount;
            }

            // 3. APPLY THE NEW PAYMENT'S EFFECT
            $newAmount = $request->payment;
            $newType   = $request->payment_type;

            // Apply new payment rules
            if ($newType === 'You Give') {
                $customer->credit += $newAmount;
            } elseif ($newType === 'You Got') {
                $customer->debit += $newAmount;
            }

            // Ensure balances are not negative (though logic above should handle this if starting balance is zero/positive)
            $customer->credit = max(0, $customer->credit);
            $customer->debit  = max(0, $customer->debit);

            // 4. Update the Manual Payment Record
            $oldPayment->payment_type = $newType;
            $oldPayment->payment      = $newAmount;
            $oldPayment->note         = $request->note;
            $oldPayment->save();

            // 5. Save the Customer Balance
            $customer->save();

            \DB::commit(); // Commit all changes if successful

            return response()->json([
                'status'  => 'success',
                'message' => 'Payment updated and balance recalculated successfully.',
            ]);

        } catch (\Exception $e) {
            \DB::rollback(); // Rollback if any error occurs
                             // Log the detailed error for debugging
            \Log::error("Manual Payment Update Failed: " . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Internal server error during balance update.',
            ], 500); // Return a 500 status to the AJAX handler
        }
    }

}
