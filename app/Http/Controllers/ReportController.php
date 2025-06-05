<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $salesStartDate = $request->input('sales_start_date');
        $salesEndDate = $request->input('sales_end_date');
        $purchaseStartDate = $request->input('purchase_start_date');
        $purchaseEndDate = $request->input('purchase_end_date');
        $expenseStartDate = $request->input('expense_start_date');
        $expenseEndDate = $request->input('expense_end_date');

        $salesQuery = Sale::query();
        if ($salesStartDate) { $salesQuery->whereDate('created_at', '>=', $salesStartDate); }
        if ($salesEndDate) { $salesQuery->whereDate('created_at', '<=', $salesEndDate); }
        $sales = $salesQuery->get();

        $allTransactionIdsFromSales = [];
        foreach ($sales as $sale) {
            $ids = $sale->transaction_id;
            if (is_string($ids)) {
                Log::warning("Dashboard - Sale ID {$sale->id}: transaction_id was a string, attempting to decode: " . $ids);
                $decodedIds = json_decode($ids, true);
                $ids = (json_last_error() === JSON_ERROR_NONE && is_array($decodedIds)) ? $decodedIds : [];
            }
            if (is_array($ids)) {
                $allTransactionIdsFromSales = array_merge($allTransactionIdsFromSales, $ids);
            } elseif ($ids !== null) {
                 Log::warning("Dashboard - Sale ID {$sale->id}: transaction_id is not array/string/null. Type: " . gettype($ids));
            }
        }
        $allTransactionIdsFromSales = array_filter(array_unique($allTransactionIdsFromSales), 'is_numeric');

        $transactionsMap = collect();
        if (!empty($allTransactionIdsFromSales)) {
            $transactionsMap = Transaction::with('products.category')->whereIn('id', $allTransactionIdsFromSales)->get()->keyBy('id');
        }

        $customers = Customer::all();
        $categoryMap = Category::all()->keyBy('id');
        $customerMap = $customers->keyBy('id');

        $totalRevenue = 0;
        $totalSalesDiscountApplied = 0;
        $totalCalculatedSalesProfit = 0;
        $salesByDate = [];
        $productSalesStats = [];
        $categorySalesStats = [];
        $customerSpending = [];

        foreach ($sales as $sale) {
            $saleNetRevenue = (float) $sale->total_amount;
            $totalRevenue += $saleNetRevenue;
            $totalSalesDiscountApplied += (float) $sale->total_discount;

            $saleDate = $sale->created_at->format('Y-m-d');
            if (!isset($salesByDate[$saleDate])) {
                $salesByDate[$saleDate] = ['revenue' => 0, 'profit' => 0, 'sales_count' => 0];
            }
            $salesByDate[$saleDate]['revenue'] += $saleNetRevenue;
            $salesByDate[$saleDate]['sales_count']++;

            $currentSaleTransactionIds = $sale->transaction_id;
            if (!is_array($currentSaleTransactionIds)) {
                if (is_string($currentSaleTransactionIds)) {
                    $decodedIds = json_decode($currentSaleTransactionIds, true);
                    $currentSaleTransactionIds = (json_last_error() === JSON_ERROR_NONE && is_array($decodedIds)) ? $decodedIds : [];
                } else {
                    $currentSaleTransactionIds = [];
                }
            }
            $currentSaleTransactionIds = array_filter($currentSaleTransactionIds, 'is_numeric');

            foreach ($currentSaleTransactionIds as $transId) {
                $transaction = $transactionsMap->get($transId);
                if ($transaction && $transaction->products) {
                    $product = $transaction->products;
                    $qty = (int) $transaction->quantity;

                    $lineItemRevenueForProfit = (float) $transaction->amount - (float)($transaction->discount ?? 0);
                    $lineItemCost = (float) ($product->original_price ?? 0) * $qty;
                    $transactionLineProfit = $lineItemRevenueForProfit - $lineItemCost;

                    $totalCalculatedSalesProfit += $transactionLineProfit;
                    $salesByDate[$saleDate]['profit'] += $transactionLineProfit;

                    if (!isset($productSalesStats[$product->id])) {
                        $productSalesStats[$product->id] = ['name' => $product->item_name, 'qtySold' => 0, 'revenue' => 0, 'profit' => 0];
                    }
                    $productSalesStats[$product->id]['qtySold'] += $qty;
                    $productSalesStats[$product->id]['revenue'] += (float) $transaction->total_amount;
                    $productSalesStats[$product->id]['profit'] += $transactionLineProfit;

                    if ($product->category) {
                        $category = $product->category;
                        if (!isset($categorySalesStats[$category->id])) {
                            $categorySalesStats[$category->id] = ['name' => $category->name, 'revenue' => 0, 'profit' => 0];
                        }
                        $categorySalesStats[$category->id]['revenue'] += (float) $transaction->total_amount;
                        $categorySalesStats[$category->id]['profit'] += $transactionLineProfit;
                    }
                }
            }
            $customerId = $sale->customer_id;
            if (is_array($customerId)) $customerId = $customerId[0] ?? null;
            if ($customerId && $customerMap->has($customerId)) {
                $customer = $customerMap->get($customerId);
                if (!isset($customerSpending[$customer->id])) {
                    $customerSpending[$customer->id] = ['name' => $customer->name, 'totalSpent' => 0, 'salesCount' => 0];
                }
                $customerSpending[$customer->id]['totalSpent'] += $saleNetRevenue;
                $customerSpending[$customer->id]['salesCount']++;
            }
        }
        ksort($salesByDate);
        $salesOverTime = array_map(function ($date, $data) {
            return ['date' => $date, 'revenue' => $data['revenue'], 'profit' => $data['profit'], 'sales_count' => $data['sales_count']];
        }, array_keys($salesByDate), array_values($salesByDate));

        $purchasesQuery = Purchase::with(['product:id,item_name', 'supplier:id,supplier']);
        if ($purchaseStartDate) { $purchasesQuery->whereDate('purchase_date', '>=', $purchaseStartDate); }
        if ($purchaseEndDate) { $purchasesQuery->whereDate('purchase_date', '<=', $purchaseEndDate); }
        $purchases = $purchasesQuery->get();
        $suppliers = Supplier::all();
        $totalPurchaseValue = $purchases->sum('total_amount');
        $purchasesByDateAgg = [];
        $productPurchaseStatsAgg = [];
        $supplierPurchaseStats = [];
        foreach ($purchases as $purchase) {
            $purchaseAmount = (float) $purchase->total_amount;
            $purchaseDate = $purchase->purchase_date->format('Y-m-d');
            if (!isset($purchasesByDateAgg[$purchaseDate])) {
                $purchasesByDateAgg[$purchaseDate] = ['cost' => 0, 'purchase_count' => 0];
            }
            $purchasesByDateAgg[$purchaseDate]['cost'] += $purchaseAmount;
            $purchasesByDateAgg[$purchaseDate]['purchase_count']++;
            if ($purchase->product) {
                $product = $purchase->product;
                if (!isset($productPurchaseStatsAgg[$product->id])) {
                    $productPurchaseStatsAgg[$product->id] = ['name' => $product->item_name, 'qtyPurchased' => 0, 'totalCost' => 0];
                }
                $productPurchaseStatsAgg[$product->id]['qtyPurchased'] += (int) $purchase->quantity;
                $productPurchaseStatsAgg[$product->id]['totalCost'] += $purchaseAmount;
            }
            if ($purchase->supplier) {
                $supplier = $purchase->supplier;
                if (!isset($supplierPurchaseStats[$supplier->id])) {
                    $supplierPurchaseStats[$supplier->id] = ['name' => $supplier->supplier, 'totalPurchaseValue' => 0, 'purchaseCount' => 0];
                }
                $supplierPurchaseStats[$supplier->id]['totalPurchaseValue'] += $purchaseAmount;
                $supplierPurchaseStats[$supplier->id]['purchaseCount']++;
            }
        }
        ksort($purchasesByDateAgg);
        $purchasesOverTime = array_map(fn($date, $data) => ['date' => $date, 'cost' => $data['cost'], 'purchase_count' => $data['purchase_count']], array_keys($purchasesByDateAgg), array_values($purchasesByDateAgg));
        $supplierBalancesData = $suppliers->map(fn($supp) => ['id' => $supp->id, 'name' => $supp->supplier, 'debit' => (float) $supp->debit, 'credit' => (float) $supp->credit, 'balance' => (float) $supp->debit - (float) $supp->credit])->sortBy('name')->values()->toArray();
        $totalOwedToSuppliers = collect($supplierBalancesData)->where('balance', '<', 0)->sum(fn($item) => abs($item['balance']));
        $totalOwedBySuppliers = collect($supplierBalancesData)->where('balance', '>', 0)->sum('balance');

        $expenseQuery = Expense::with('category');
        if ($expenseStartDate) { $expenseQuery->whereDate('expense_date', '>=', $expenseStartDate); }
        if ($expenseEndDate) { $expenseQuery->whereDate('expense_date', '<=', $expenseEndDate); }
        $expenses = $expenseQuery->get();
        $totalExpenses = $expenses->sum('amount');
        $expensesByCategory = $expenses->groupBy('category.name')->map(fn($group) => $group->sum('amount'))->sortDesc();
        $expensesByDateAgg = [];
        foreach ($expenses as $expense) {
            $expDate = $expense->expense_date->format('Y-m-d');
            if(!isset($expensesByDateAgg[$expDate])) { $expensesByDateAgg[$expDate] = ['amount' => 0, 'expense_count' => 0]; }
            $expensesByDateAgg[$expDate]['amount'] += (float) $expense->amount;
            $expensesByDateAgg[$expDate]['expense_count']++;
        }
        ksort($expensesByDateAgg);
        $expensesOverTime = array_map(fn($date, $data) => ['date' => $date, 'amount' => $data['amount'], 'expense_count' => $data['expense_count']], array_keys($expensesByDateAgg), array_values($expensesByDateAgg));

        $customerBalancesData = $customers->map(fn($cust) => ['id' => $cust->id, 'name' => $cust->name, 'debit' => (float) $cust->debit, 'credit' => (float) $cust->credit, 'balance' => (float) $cust->debit - (float) $cust->credit])->sortBy('name')->values()->toArray();

        $reports = [
            'totalRevenue' => $totalRevenue,
            'totalSalesDiscount' => $totalSalesDiscountApplied,
            'totalSalesProfit' => $totalCalculatedSalesProfit,
            'salesOverTime' => $salesOverTime,
            'topProductsByRevenue' => collect($productSalesStats)->sortByDesc('revenue')->values()->take(5)->toArray(),
            'topProductsByQtySold' => collect($productSalesStats)->sortByDesc('qtySold')->values()->take(5)->toArray(),
            'topProductsBySalesProfit' => collect($productSalesStats)->sortByDesc('profit')->values()->take(5)->toArray(),
            'categoriesByRevenue' => collect($categorySalesStats)->sortByDesc('revenue')->values()->toArray(),
            'customerBalances' => $customerBalancesData,
            'topCustomersBySpending' => collect($customerSpending)->sortByDesc('totalSpent')->values()->take(5)->toArray(),
            'totalPurchaseValue' => $totalPurchaseValue,
            'purchasesOverTime' => $purchasesOverTime,
            'topPurchasedProductsByCost' => collect($productPurchaseStatsAgg)->sortByDesc('totalCost')->values()->take(5)->toArray(),
            'topPurchasedProductsByQty' => collect($productPurchaseStatsAgg)->sortByDesc('qtyPurchased')->values()->take(5)->toArray(),
            'topSuppliersByPurchaseValue' => collect($supplierPurchaseStats)->sortByDesc('totalPurchaseValue')->values()->take(5)->toArray(),
            'supplierBalances' => $supplierBalancesData,
            'totalOwedToSuppliers' => $totalOwedToSuppliers,
            'totalOwedBySuppliers' => $totalOwedBySuppliers,
            'totalExpenses' => $totalExpenses,
            'expensesByCategory' => $expensesByCategory->take(5)->toArray(),
            'expensesOverTime' => $expensesOverTime,
            'netOperationalFlow' => $totalRevenue - $totalPurchaseValue,
            'estimatedNetProfit' => $totalCalculatedSalesProfit - $totalExpenses,
            'filter_sales_start_date' => $salesStartDate,
            'filter_sales_end_date' => $salesEndDate,
            'filter_purchase_start_date' => $purchaseStartDate,
            'filter_purchase_end_date' => $purchaseEndDate,
            'filter_expense_start_date' => $expenseStartDate,
            'filter_expense_end_date' => $expenseEndDate,
        ];
        return view('pages.reports.dashboard', compact('reports'));
    }

    public function sales(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $salesQuery = Sale::query();
        if ($startDate) { $salesQuery->whereDate('created_at', '>=', $startDate); }
        if ($endDate) { $salesQuery->whereDate('created_at', '<=', $endDate); }
        $salesCollection = $salesQuery->get();

        $allTransactionIds = [];
        foreach ($salesCollection as $sale) {
            $ids = $sale->transaction_id;
            if (is_string($ids)) {
                $decodedIds = json_decode($ids, true);
                $ids = (json_last_error() === JSON_ERROR_NONE && is_array($decodedIds)) ? $decodedIds : [];
            }
            if (is_array($ids)) {
                $allTransactionIds = array_merge($allTransactionIds, $ids);
            }
        }
        $allTransactionIds = array_filter(array_unique($allTransactionIds), 'is_numeric');
        
        $transactionsMap = collect();
        if (!empty($allTransactionIds)) {
            $transactionsMap = Transaction::with('products')->whereIn('id', $allTransactionIds)->get()->keyBy('id');
        }

        $salesByDate = [];
        foreach ($salesCollection as $sale) {
            $saleAmount = (float) $sale->total_amount;
            $saleDate = $sale->created_at->format('Y-m-d');
            if (!isset($salesByDate[$saleDate])) {
                $salesByDate[$saleDate] = ['revenue' => 0, 'profit' => 0, 'sales_count' => 0];
            }
            $salesByDate[$saleDate]['revenue'] += $saleAmount;
            $salesByDate[$saleDate]['sales_count']++;

            $currentSaleTransactionIds = $sale->transaction_id;
            if (!is_array($currentSaleTransactionIds)) {
                if (is_string($currentSaleTransactionIds)) {
                    $decodedIds = json_decode($currentSaleTransactionIds, true);
                    $currentSaleTransactionIds = (json_last_error() === JSON_ERROR_NONE && is_array($decodedIds)) ? $decodedIds : [];
                } else {
                    $currentSaleTransactionIds = [];
                }
            }
            $currentSaleTransactionIds = array_filter($currentSaleTransactionIds, 'is_numeric');

            foreach ($currentSaleTransactionIds as $transId) {
                $transaction = $transactionsMap->get($transId);
                if ($transaction && $transaction->products) {
                    $product = $transaction->products;
                    $qty = (int) $transaction->quantity;
                    $lineItemRevenueForProfit = (float) $transaction->amount - (float)($transaction->discount ?? 0);
                    $lineItemCost = (float) ($product->original_price ?? 0) * $qty;
                    $transactionLineProfit = $lineItemRevenueForProfit - $lineItemCost;
                    $salesByDate[$saleDate]['profit'] += $transactionLineProfit;
                }
            }
        }
        ksort($salesByDate);
        $salesOverTime = array_map(fn($date, $data) => ['date' => $date, 'revenue' => $data['revenue'], 'profit' => $data['profit'], 'sales_count' => $data['sales_count']], array_keys($salesByDate), array_values($salesByDate));

        $reports = [
            'salesOverTime' => $salesOverTime,
            'filter_start_date' => $startDate,
            'filter_end_date' => $endDate,
        ];
        return view('pages.reports.sales', compact('reports'));
    }

    public function products(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $salesQuery = Sale::query();
        if ($startDate) { $salesQuery->whereDate('created_at', '>=', $startDate); }
        if ($endDate) { $salesQuery->whereDate('created_at', '<=', $endDate); }
        $sales = $salesQuery->get();

        $allTransactionIds = [];
        foreach ($sales as $sale) {
            $ids = $sale->transaction_id;
            if (is_string($ids)) {
                $decodedIds = json_decode($ids, true);
                $ids = (json_last_error() === JSON_ERROR_NONE && is_array($decodedIds)) ? $decodedIds : [];
            }
            if (is_array($ids)) {
                $allTransactionIds = array_merge($allTransactionIds, $ids);
            }
        }
        $allTransactionIds = array_filter(array_unique($allTransactionIds), 'is_numeric');

        $transactionsMap = collect();
        if (!empty($allTransactionIds)) {
            $transactionsMap = Transaction::with('products.category')->whereIn('id', $allTransactionIds)->get()->keyBy('id');
        }
        
        $productSalesStats = [];
        $categorySalesStats = [];

        foreach ($sales as $sale) {
            $currentSaleTransactionIds = $sale->transaction_id;
            if (!is_array($currentSaleTransactionIds)) {
                 if (is_string($currentSaleTransactionIds)) {
                    $decodedIds = json_decode($currentSaleTransactionIds, true);
                    $currentSaleTransactionIds = (json_last_error() === JSON_ERROR_NONE && is_array($decodedIds)) ? $decodedIds : [];
                } else {
                    $currentSaleTransactionIds = [];
                }
            }
            $currentSaleTransactionIds = array_filter($currentSaleTransactionIds, 'is_numeric');

            foreach ($currentSaleTransactionIds as $transId) {
                $transaction = $transactionsMap->get($transId);
                if ($transaction && $transaction->products) {
                    $product = $transaction->products;
                    $category = $product->category;
                    $qty = (int) $transaction->quantity;

                    $lineItemRevenueForProfit = (float) $transaction->amount - (float)($transaction->discount ?? 0);
                    $lineItemCost = (float) ($product->original_price ?? 0) * $qty;
                    $transactionLineProfit = $lineItemRevenueForProfit - $lineItemCost;
                    
                    $transactionNetRevenueForStat = (float) $transaction->total_amount;

                    if (!isset($productSalesStats[$product->id])) {
                        $productSalesStats[$product->id] = ['name' => $product->item_name, 'qtySold' => 0, 'revenue' => 0, 'profit' => 0];
                    }
                    $productSalesStats[$product->id]['qtySold'] += $qty;
                    $productSalesStats[$product->id]['revenue'] += $transactionNetRevenueForStat;
                    $productSalesStats[$product->id]['profit'] += $transactionLineProfit;

                    if ($category) {
                        if (!isset($categorySalesStats[$category->id])) {
                            $categorySalesStats[$category->id] = ['name' => $category->name, 'revenue' => 0, 'profit' => 0];
                        }
                        $categorySalesStats[$category->id]['revenue'] += $transactionNetRevenueForStat;
                        $categorySalesStats[$category->id]['profit'] += $transactionLineProfit;
                    }
                }
            }
        }
        $reports = [
            'topProductsByRevenue' => collect($productSalesStats)->sortByDesc('revenue')->values()->take(10)->toArray(),
            'topProductsByQtySold' => collect($productSalesStats)->sortByDesc('qtySold')->values()->take(10)->toArray(),
            'topProductsBySalesProfit' => collect($productSalesStats)->sortByDesc('profit')->values()->take(10)->toArray(),
            'categoriesByRevenue' => collect($categorySalesStats)->sortByDesc('revenue')->values()->toArray(),
            'filter_start_date' => $startDate,
            'filter_end_date' => $endDate,
        ];
        return view('pages.reports.products', compact('reports'));
    }
    
    public function customers(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $customers = Customer::all();

        $salesQuery = Sale::query();
        if ($startDate) { $salesQuery->whereDate('created_at', '>=', $startDate); }
        if ($endDate) { $salesQuery->whereDate('created_at', '<=', $endDate); }
        $sales = $salesQuery->get();

        $customerSpending = [];
        $customerMap = $customers->keyBy('id');
        foreach ($sales as $sale) {
            $saleAmount = (float) $sale->total_amount;
            $customerId = $sale->customer_id;
            if (is_array($customerId)) $customerId = $customerId[0] ?? null;
            if ($customerId && $customerMap->has($customerId)) {
                $customer = $customerMap->get($customerId);
                if (!isset($customerSpending[$customer->id])) {
                    $customerSpending[$customer->id] = ['name' => $customer->name, 'totalSpent' => 0, 'salesCount' => 0];
                }
                $customerSpending[$customer->id]['totalSpent'] += $saleAmount;
                $customerSpending[$customer->id]['salesCount']++;
            }
        }
        $customerBalancesData = $customers->map(function ($cust) {
            return ['id' => $cust->id, 'name' => $cust->name, 'debit' => (float) $cust->debit, 'credit' => (float) $cust->credit, 'balance' => (float) $cust->debit - (float) $cust->credit];
        })->sortBy('name')->values()->toArray();
        $topCustomersBySpending = collect($customerSpending)->sortByDesc('totalSpent')->values()->take(10)->toArray();
        $reports = [
            'customerBalances' => $customerBalancesData,
            'topCustomersBySpending' => $topCustomersBySpending,
            'filter_start_date' => $startDate,
            'filter_end_date' => $endDate,
        ];
        return view('pages.reports.customers', compact('reports'));
    }

    public function purchasesAndSuppliers(Request $request)
    {
        $purchaseStartDate = $request->input('purchase_start_date');
        $purchaseEndDate = $request->input('purchase_end_date');
        $purchaseSupplierId = $request->input('purchase_supplier_id');
        $purchaseProductId = $request->input('purchase_product_id');

        $purchasesQuery = Purchase::with(['product:id,item_name', 'supplier:id,supplier,debit,credit'])
                            ->orderBy('purchase_date', 'desc')->orderBy('id', 'desc');
        if ($purchaseStartDate) { $purchasesQuery->whereDate('purchase_date', '>=', $purchaseStartDate); }
        if ($purchaseEndDate) { $purchasesQuery->whereDate('purchase_date', '<=', $purchaseEndDate); }
        if ($purchaseSupplierId) { $purchasesQuery->where('supplier_id', $purchaseSupplierId); }
        if ($purchaseProductId) { $purchasesQuery->where('product_id', $purchaseProductId); }
        $allPurchases = $purchasesQuery->get();

        $suppliers = Supplier::all(); 
        $supplierPurchaseSummary = $suppliers->map(function ($supplier) use ($purchaseStartDate, $purchaseEndDate, $purchaseSupplierId, $purchaseProductId) {
            $suppPurchasesQuery = $supplier->purchases();
            
            if ($purchaseStartDate) { $suppPurchasesQuery->where('purchase_date', '>=', $purchaseStartDate); }
            if ($purchaseEndDate) { $suppPurchasesQuery->where('purchase_date', '<=', $purchaseEndDate); }
            if ($purchaseProductId) { $suppPurchasesQuery->where('product_id', $purchaseProductId); }

            if ($purchaseSupplierId && $purchaseSupplierId != $supplier->id) {
                 return [
                    'id' => $supplier->id, 'name' => $supplier->supplier,
                    'total_purchase_value' => 0, 'purchase_count' => 0,
                    'balance' => (float) $supplier->debit - (float) $supplier->credit,
                ];
            }
            $filteredPurchases = $suppPurchasesQuery->get();

            return [
                'id' => $supplier->id,
                'name' => $supplier->supplier,
                'total_purchase_value' => (float) $filteredPurchases->sum('total_amount'),
                'purchase_count' => $filteredPurchases->count(),
                'balance' => (float) $supplier->debit - (float) $supplier->credit,
            ];
        })->sortByDesc('total_purchase_value')->values()->toArray();


        $filterSuppliers = Supplier::select('id', 'supplier')->orderBy('supplier', 'asc')->get();
        $filterProducts = Product::select('id', 'item_name')->orderBy('item_name', 'asc')->get();
        $reports = [
            'allPurchases' => $allPurchases,
            'supplierPurchaseSummary' => $supplierPurchaseSummary,
            'filterSuppliers' => $filterSuppliers,
            'filterProducts' => $filterProducts,
            'filter_purchase_start_date' => $purchaseStartDate,
            'filter_purchase_end_date' => $purchaseEndDate,
            'filter_purchase_supplier_id' => $purchaseSupplierId,
            'filter_purchase_product_id' => $purchaseProductId,
        ];
        return view('pages.reports.purchases_suppliers', compact('reports'));
    }

     public function expensesReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $categoryId = $request->input('expense_category_id');
        $searchTerm = $request->input('search_term');

        $query = Expense::with('category', 'user')->orderBy('expense_date', 'desc');
        if ($startDate) { $query->whereDate('expense_date', '>=', $startDate); }
        if ($endDate) { $query->whereDate('expense_date', '<=', $endDate); }
        if ($categoryId) { $query->where('expense_category_id', $categoryId); }
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('paid_to', 'like', '%' . $searchTerm . '%')
                  ->orWhere('reference_number', 'like', '%' . $searchTerm . '%');
            });
        }
        $expenses = $query->paginate(20);
        $expenseCategories = ExpenseCategory::orderBy('name')->get();
        
        $totalExpensesForPeriod = (clone $query)->sum('amount');

        return view('pages.reports.expenses', compact('expenses', 'expenseCategories', 'totalExpensesForPeriod', 'startDate', 'endDate', 'categoryId', 'searchTerm'));
    }
}
