<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request; // <-- Request ko import karein
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function create()
    {
        $data['suppliers'] = Supplier::orderBy('supplier', 'asc')->get();
        $data['categories'] = Category::orderBy('name', 'asc')->get();
        return view('pages.purchase.create', $data);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'purchase_quantity' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'cash_paid' => 'required|numeric|min:0',
            'new_selling_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            $supplier = Supplier::findOrFail($request->supplier_id);

            // Get new purchase details from the request
            $newPurchaseQty = (int) $request->purchase_quantity;
            $newPurchasePrice = (float) $request->purchase_price;
            $totalPurchaseAmount = $newPurchaseQty * $newPurchasePrice;
            $cashPaidForThisPurchase = (float) $request->cash_paid;

            // 1. Create the Purchase Record
            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'product_id' => $request->product_id,
                'quantity' => $newPurchaseQty,
                'unit_price' => $newPurchasePrice,
                'total_amount' => $totalPurchaseAmount,
                'cash_paid_at_purchase' => $cashPaidForThisPurchase,
                'purchase_date' => $request->purchase_date,
                'notes' => $request->notes,
            ]);

            // 2. Calculate new Weighted Average Cost (WAC) for the product
            $oldQty = (int) $product->qty;
            $oldCost = (float) ($product->cost_price ?? $product->original_price ?? 0);
            $oldStockValue = $oldQty * $oldCost;
            $newStockValue = $newPurchaseQty * $newPurchasePrice;

            $newTotalQty = $oldQty + $newPurchaseQty;
            $newTotalValue = $oldStockValue + $newStockValue;

            $newAverageCost = ($newTotalQty > 0) ? $newTotalValue / $newTotalQty : $newPurchasePrice;

            // 3. Update the Product
            $product->qty = $newTotalQty;
            $product->cost_price = $newAverageCost;
            $product->original_price = $newPurchasePrice; 

            if ($request->filled('new_selling_price')) {
                $product->selling_price = (float) $request->new_selling_price;
            }
            $product->save();

            // 4. Update Supplier Balance
            $supplier->credit += $totalPurchaseAmount;

            if ($cashPaidForThisPurchase > 0) {
                $paid_from_supplier_credit = min($supplier->credit, $cashPaidForThisPurchase);
                $supplier->credit -= $paid_from_supplier_credit;
                $remaining_cash_after_clearing_supplier_credit = $cashPaidForThisPurchase - $paid_from_supplier_credit;

                if ($remaining_cash_after_clearing_supplier_credit > 0) {
                    $supplier->debit += $remaining_cash_after_clearing_supplier_credit;
                }
            }

            if ($supplier->debit > 0 && $supplier->credit > 0) {
                if ($supplier->debit >= $supplier->credit) {
                    $supplier->debit -= $supplier->credit;
                    $supplier->credit = 0;
                } else {
                    $supplier->credit -= $supplier->debit;
                    $supplier->debit = 0;
                }
            }
            $supplier->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase recorded, stock and product prices updated!',
                'purchase' => $purchase->load('product', 'supplier')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase store error: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductDetails($id)
    {
        $product = Product::select('id', 'cost_price', 'original_price', 'selling_price', 'qty')->find($id);

        if ($product) {
            $costPrice = $product->cost_price ?? $product->original_price ?? 0;
            return response()->json([
                'status' => 'success',
                'cost_price' => $costPrice,
                'selling_price' => $product->selling_price ?? 0,
                'current_qty' => $product->qty,
            ]);
        }
        return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
    }

    public function index(Request $request)
    {
        $query = Purchase::with([
            'product:id,item_name,item_code',
            'supplier:id,supplier,debit,credit'
        ])
            ->orderBy('purchase_date', 'desc')
            ->orderBy('id', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('supplier_id_filter')) {
            $query->where('supplier_id', $request->supplier_id_filter);
        }
        if ($request->filled('product_id_filter')) {
            $query->where('product_id', $request->product_id_filter);
        }

        $data['purchases'] = $query->paginate(15);
        $data['filter_suppliers'] = Supplier::select('id', 'supplier')->orderBy('supplier', 'asc')->get();
        $data['filter_products'] = Product::select('id', 'item_name')->orderBy('item_name', 'asc')->get();

        return view('pages.purchase.index', $data);
    }

    public function getProductsBySupplier($id)
    {
        try {
            $products = Product::where('supplier_id', $id)
                ->select('id', 'item_name', 'item_code')
                ->orderBy('item_name', 'asc')
                ->get();
            return response()->json([
                'status' => 'success',
                'products' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('Get Products by Supplier error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Could not fetch products.'
            ], 500);
        }
    }

    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::orderBy('supplier')->get();
        $products = Product::orderBy('item_name')->get();
        $categories = Category::orderBy('name', 'asc')->get(); 

        return view('pages.purchase.edit', compact('purchase', 'suppliers', 'products', 'categories'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'purchase_quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'cash_paid' => 'required|numeric|min:0',
            'new_selling_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            // ... (Aapka poora update logic jaisa pehle tha) ...
            // (Reversing stock, supplier balance, applying new values, etc.)
            
            // 2. GET OLD MODELS AND VALUES
            $old_supplier = $purchase->supplier;
            $old_product = $purchase->product;
            $old_qty = $purchase->quantity;
            $old_total_amount = $purchase->total_amount;
            $old_cash_paid = $purchase->cash_paid_at_purchase;
            $old_unit_price = $purchase->unit_price;

            // 3. REVERSE THE OLD TRANSACTION FROM PRODUCT STOCK & WAC
            $currentTotalValue = $old_product->qty * $old_product->cost_price;
            $purchaseValue = $old_qty * $old_unit_price; 
            $originalTotalValue = $currentTotalValue - $purchaseValue;
            $originalQty = $old_product->qty - $old_qty;
            $originalCost = ($originalQty > 0) ? $originalTotalValue / $originalQty : 0;
            
            $old_product->qty = $originalQty;
            $old_product->cost_price = $originalCost;
            $old_product->save();
            
            // 4. REVERSE THE OLD TRANSACTION FROM SUPPLIER BALANCE
            $old_supplier->credit -= $old_total_amount;
            $old_supplier->debit -= $old_cash_paid; 
            
            if ($old_supplier->debit > 0 && $old_supplier->credit > 0) {
                 if ($old_supplier->debit >= $old_supplier->credit) {
                    $old_supplier->debit -= $old_supplier->credit;
                    $old_supplier->credit = 0;
                } else {
                    $old_supplier->credit -= $old_supplier->debit;
                    $old_supplier->debit = 0;
                }
            }
            $old_supplier->save();
            
            $new_supplier = Supplier::findOrFail($validated['supplier_id']);
            $new_product = Product::findOrFail($validated['product_id']);

            // 5. CALCULATE NEW VALUES
            $new_qty = (int) $validated['purchase_quantity'];
            $new_price = (float) $validated['purchase_price'];
            $new_total_amount = $new_qty * $new_price;
            $new_cash_paid = (float) $validated['cash_paid'];

            // 6. APPLY NEW TRANSACTION TO PRODUCT STOCK & WAC
            $oldQty = (int) $new_product->qty;
            $oldCost = (float) ($new_product->cost_price ?? $new_product->original_price ?? 0);
            $oldStockValue = $oldQty * $oldCost;
            $newStockValue = $new_qty * $new_price;

            $newTotalQty = $oldQty + $new_qty;
            $newTotalValue = $oldStockValue + $newStockValue;
            $newAverageCost = ($newTotalQty > 0) ? $newTotalValue / $newTotalQty : $new_price;

            $new_product->qty = $newTotalQty;
            $new_product->cost_price = $newAverageCost;
            $new_product->original_price = $new_price;
            if ($request->filled('new_selling_price')) {
                $new_product->selling_price = (float) $validated['new_selling_price'];
            }
            $new_product->save();
            
            // 7. APPLY NEW TRANSACTION TO SUPPLIER BALANCE
            $new_supplier->credit += $new_total_amount;
            if ($new_cash_paid > 0) {
                $paid_from_credit = min($new_supplier->credit, $new_cash_paid);
                $new_supplier->credit -= $paid_from_credit;
                $remaining_cash = $new_cash_paid - $paid_from_credit;
                if ($remaining_cash > 0) {
                    $new_supplier->debit += $remaining_cash;
                }
            }
            if ($new_supplier->debit > 0 && $new_supplier->credit > 0) {
                 if ($new_supplier->debit >= $new_supplier->credit) {
                    $new_supplier->debit -= $new_supplier->credit;
                    $new_supplier->credit = 0;
                } else {
                    $new_supplier->credit -= $new_supplier->debit;
                    $new_supplier->debit = 0;
                }
            }
            if($old_supplier->id != $new_supplier->id) {
                 $new_supplier->save();
            }

            // 8. UPDATE THE PURCHASE RECORD ITSELF
            $purchase->supplier_id = $validated['supplier_id'];
            $purchase->product_id = $validated['product_id'];
            $purchase->quantity = $new_qty;
            $purchase->unit_price = $new_price;
            $purchase->total_amount = $new_total_amount;
            $purchase->cash_paid_at_purchase = $new_cash_paid;
            $purchase->purchase_date = $validated['purchase_date'];
            $purchase->notes = $validated['notes'];
            $purchase->save();

            DB::commit();

            return redirect()->route('purchase.index')->with('success', 'Purchase updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase update error: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    // ===============================================
    // == YEH METHOD UPDATE HUA HAI ==
    // ===============================================

    /**
     * Get a summary of purchases from a supplier, filtered by date.
     */
    public function getSupplierPurchaseSummary(Supplier $supplier, Request $request)
    {
        try {
            // Click ki gayi row ki date hasil karein. Agar nahi mili to aaj ki date.
            $endDate = $request->query('date', now()->format('Y-m-d'));

            // Uss supplier ki tamam purchases hasil karein,
            // jo ya to uss date par huin ya uss se pehle.
            $purchases = Purchase::where('supplier_id', $supplier->id)
                ->whereDate('purchase_date', '<=', $endDate)
                ->with('product:id,item_name,item_code') // Product info
                ->orderBy('purchase_date', 'desc') // Sab se nayi (newest) pehle
                ->orderBy('id', 'desc')
                ->get();

            // Filter ki gayi purchases ka grand total calculate karein
            $grandTotal = $purchases->sum('total_amount');

            return response()->json([
                'status' => 'success',
                'supplier_name' => $supplier->supplier,
                'purchases' => $purchases, // Grouped products ke bajaye ab purchases bhej rahe hain
                'grand_total' => $grandTotal
            ]);

        } catch (\Exception $e) {
            // Yeh 'Error: ' wali ghalti theek kar di hai
            Log::error('Supplier Summary Error: ' . $e->getMessage() . ' File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            return response()->json([
                'status' => 'error',
                'message' => 'Could not fetch supplier summary.'
            ], 500);
        }
    }
}