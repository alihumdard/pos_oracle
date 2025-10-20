<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class PurchaseController extends Controller
{
    public function create()
    {
        $data['suppliers'] = Supplier::orderBy('supplier', 'asc')->get();
        $data['categories'] = Category::orderBy('name', 'asc')->get(); // <-- ADD THIS LINE
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
            $product->original_price = $newPurchasePrice; // Update original_price to last purchase price

            // <-- 2. ADD THIS LOGIC -->
            // Update selling price ONLY if a new one was provided
            if ($request->filled('new_selling_price')) {
                $product->selling_price = (float) $request->new_selling_price;
            }
            // <-- END NEW LOGIC -->

            $product->save(); // Save all product changes

            // 4. Update Supplier Balance (Your existing logic)
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
                'message' => 'Purchase recorded, stock and product prices updated!', // Updated message
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
        // <-- 3. UPDATE THIS METHOD -->
        $product = Product::select('id', 'cost_price', 'original_price', 'selling_price', 'qty')->find($id);

        if ($product) {
            // Determine the cost price (use new cost_price, fallback to old original_price)
            $costPrice = $product->cost_price ?? $product->original_price ?? 0;

            return response()->json([
                'status' => 'success',
                'cost_price' => $costPrice, // Use this for 'Last Cost Price'
                'selling_price' => $product->selling_price ?? 0, // <-- Send current selling price
                'current_qty' => $product->qty,
            ]);
        }
        return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
    }

    public function index(Request $request)
    {
        $query = Purchase::with([
            'product:id,item_name',
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
}
