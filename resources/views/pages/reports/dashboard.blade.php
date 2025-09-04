@extends('index')

@section('title', 'Dashboard Reports')

<style>
.bg-light-primary {
    background-color: #e0f0ff !important;
}
</style>

@section('content')
<div class="container-fluid">
    <div class="row mb-4 pt-4 align-items-center justify-content-between">
        <div class="col-lg-8 col-md-7 d-flex align-items-center mb-3 mb-md-0">
            <div class="rounded-circle d-flex justify-content-center align-items-center me-3 bg-light-primary shadow-sm"
                style="width: 56px; height: 56px;">
                <i class="fas fa-tachometer-alt text-primary fs-4"></i>
            </div>

            <div>
                <h1 class="h3 fw-bold mt-1 text-dark">Dashboard Overview</h1>
                <small class="text-muted fw-normal">Your business performance at a glance</small>
            </div>
        </div>

        <div class="col-lg-4 col-md-5 d-flex justify-content-md-end justify-content-start gap-2">
            <a href="{{ route('reports.dashboard') }}"
                class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                <i class="fas fa-sync-alt me-2"></i> Reset
            </a>

            <button class="btn bg-indigo-600 btn-primary btn-sm d-flex align-items-center">
                <i class="fas fa-download me-2"></i> Export
            </button>
        </div>
    </div>

    {{-- Filter Form for Dashboard --}}
    <div class="bg-white shadow-md rounded-xl p-6 mb-6">
        <form action="{{ route('reports.dashboard') }}" method="GET" class="space-y-6">
            <!-- Title -->
            <h2 class="text-lg font-semibold text-indigo-600 flex items-center">
                <i class="fas fa-filter mr-2"></i> Filter Dashboard Data
            </h2>

            <!-- Filters Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Sales From -->
                <div>
                    <label for="sales_start_date" class="block text-sm font-medium text-gray-600 mb-1">Sales
                        From</label>
                    <input type="date" name="sales_start_date" id="sales_start_date"
                        value="{{ $reports['filter_sales_start_date'] ?? '' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <!-- Sales To -->
                <div>
                    <label for="sales_end_date" class="block text-sm font-medium text-gray-600 mb-1">Sales To</label>
                    <input type="date" name="sales_end_date" id="sales_end_date"
                        value="{{ $reports['filter_sales_end_date'] ?? '' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <!-- Purchases From -->
                <div>
                    <label for="purchase_start_date" class="block text-sm font-medium text-gray-600 mb-1">Purchases
                        From</label>
                    <input type="date" name="purchase_start_date" id="purchase_start_date"
                        value="{{ $reports['filter_purchase_start_date'] ?? '' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <!-- Purchases To -->
                <div>
                    <label for="purchase_end_date" class="block text-sm font-medium text-gray-600 mb-1">Purchases
                        To</label>
                    <input type="date" name="purchase_end_date" id="purchase_end_date"
                        value="{{ $reports['filter_purchase_end_date'] ?? '' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <!-- Expenses From -->
                <div>
                    <label for="expense_start_date" class="block text-sm font-medium text-gray-600 mb-1">Expenses
                        From</label>
                    <input type="date" name="expense_start_date" id="expense_start_date"
                        value="{{ $reports['filter_expense_start_date'] ?? '' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <!-- Expenses To -->
                <div>
                    <label for="expense_end_date" class="block text-sm font-medium text-gray-600 mb-1">Expenses
                        To</label>
                    <input type="date" name="expense_end_date" id="expense_end_date"
                        value="{{ $reports['filter_expense_end_date'] ?? '' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('reports.dashboard') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow-sm flex items-center transition">
                    <i class="fas fa-times mr-2"></i> Clear Filters
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm flex items-center transition">
                    <i class="fas fa-filter mr-2"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>


    {{-- KPIs Row 1: Sales, Purchases, Expenses --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div
            class="bg-gradient-to-br from-indigo-50 to-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">Total Revenue</p>
                        <h4 class="text-3xl font-extrabold text-gray-800 mt-2">
                            {{ number_format($reports['totalRevenue'], 2) }}
                        </h4>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-indigo-100 shadow-inner">
                        <i class="fas fa-dollar-sign text-indigo-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Purchases -->
        <div
            class="bg-gradient-to-br from-yellow-50 to-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-wide">Total Purchases</p>
                        <h4 class="text-3xl font-extrabold text-gray-800 mt-2">
                            {{ number_format($reports['totalPurchaseValue'], 2) }}
                        </h4>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-yellow-100 shadow-inner">
                        <i class="fas fa-shopping-cart text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div
            class="bg-gradient-to-br from-red-50 to-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-red-600 uppercase tracking-wide">Total Expenses</p>
                        <h4 class="text-3xl font-extrabold text-gray-800 mt-2">
                            {{ number_format($reports['totalExpenses'], 2) }}
                        </h4>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100 shadow-inner">
                        <i class="fas fa-receipt text-red-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Profit -->
        <div
            class="bg-gradient-to-br from-green-50 to-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-600 uppercase tracking-wide">Sales Profit</p>
                        <h4 class="text-3xl font-extrabold text-gray-800 mt-2">
                            {{ number_format($reports['totalSalesProfit'], 2) }}
                        </h4>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-green-100 shadow-inner">
                        <i class="fas fa-piggy-bank text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Est. Net Profit -->
        <div
            class="bg-gradient-to-br from-cyan-50 to-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-cyan-600 uppercase tracking-wide">
                            Est. Net Profit
                        </p>
                        <small class="block text-gray-400 normal-case text-xs">
                            (Sales Profit - Expenses)
                        </small>
                        <h4 class="text-3xl font-extrabold text-gray-800 mt-2">
                            {{ number_format($reports['estimatedNetProfit'], 2) }}
                        </h4>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-cyan-100 shadow-inner">
                        <i class="fas fa-chart-line text-cyan-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sales Discount -->
        <div
            class="bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">
                            Total Sales Discount
                        </p>
                        <h4 class="text-3xl font-extrabold text-gray-800 mt-2">
                            {{ number_format($reports['totalSalesDiscount'], 2) }}
                        </h4>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 shadow-inner">
                        <i class="fas fa-tags text-gray-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Owed to Suppliers -->
        <div
            class="bg-gradient-to-br from-red-50 to-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-red-600 uppercase tracking-wide">
                            Total Owed to Suppliers
                        </p>
                        <h4 class="text-3xl font-extrabold text-gray-800 mt-2">
                            {{ number_format($reports['totalOwedToSuppliers'], 2) }}
                        </h4>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100 shadow-inner">
                        <i class="fas fa-file-invoice-dollar text-red-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Owed by Suppliers -->
        <div
            class="bg-gradient-to-br from-green-50 to-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-600 uppercase tracking-wide">
                            Total Owed by Suppliers
                        </p>
                        <h4 class="text-3xl font-extrabold text-gray-800 mt-2">
                            {{ number_format($reports['totalOwedBySuppliers'], 2) }}
                        </h4>
                    </div>
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-green-100 shadow-inner">
                        <i class="fas fa-hand-holding-usd text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- Sales Over Time Table --}}
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden mb-8">
        <!-- Header -->
        <div
            class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-gradient-to-r from-indigo-50 to-white">
            <h6 class="text-lg font-bold text-indigo-600 flex items-center">
                <i class="fas fa-chart-line mr-2 text-indigo-500"></i>
                Sales Over Time (Filtered)
            </h6>
            <button
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded-lg shadow-md flex items-center gap-2 transition">
                <i class="fas fa-download"></i> Export
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-indigo-100 text-indigo-700 uppercase text-xs tracking-wider">
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Revenue</th>
                        <th class="px-6 py-3">Profit</th>
                        <th class="px-6 py-3">No. of Sales</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($reports['salesOverTime'] as $saleData)
                    <tr class="hover:bg-indigo-50 transition duration-200">
                        <td class="px-6 py-4 text-gray-800 font-medium whitespace-nowrap">
                            {{ $saleData['date'] }}
                        </td>
                        <td class="px-6 py-4 text-green-600 font-semibold whitespace-nowrap">
                            {{ number_format($saleData['revenue'], 2) }}
                            <span class="ml-2 text-xs text-gray-500">USD</span>
                        </td>
                        <td class="px-6 py-4 text-cyan-600 font-semibold whitespace-nowrap">
                            {{ number_format($saleData['profit'], 2) }}
                            <span class="ml-2 text-xs text-gray-500">USD</span>
                        </td>
                        <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                            {{ $saleData['sales_count'] ?? 0 }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500 italic">
                            <i class="fas fa-info-circle mr-2"></i> No sales data for the selected period.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



    {{-- Purchases Over Time Table --}}
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden mb-8">
        <!-- Header -->
        <div
            class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-amber-50 to-white">
            <h6 class="text-lg font-bold text-amber-600 flex items-center">
                <i class="fas fa-shopping-cart mr-2 text-amber-500"></i>
                Purchases Over Time (Filtered)
            </h6>
            <button class="text-sm text-amber-500 hover:text-amber-700 font-medium flex items-center gap-1 transition">
                <i class="fas fa-file-export"></i> Export
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-amber-100 text-amber-800 uppercase text-xs tracking-wider">
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Total Cost</th>
                        <th class="px-6 py-3">No. of Purchases</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($reports['purchasesOverTime'] as $purchaseData)
                    <tr class="hover:bg-amber-50 transition">
                        <td class="px-6 py-3 text-gray-800 font-medium">{{ $purchaseData['date'] }}</td>
                        <td class="px-6 py-3 text-red-600 font-semibold">{{ number_format($purchaseData['cost'], 2) }}
                        </td>
                        <td class="px-6 py-3 text-gray-700">{{ $purchaseData['purchase_count'] ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 italic">
                            No purchase data for the selected period.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- Expenses Over Time Table (NEW) --}}
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden mb-8">
        <!-- Header -->
        <div
            class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-red-50 to-white">
            <h6 class="text-lg font-bold text-red-600 flex items-center">
                <i class="fas fa-money-bill-wave mr-2 text-red-500"></i>
                Expenses Over Time (Filtered)
            </h6>
            <button class="text-sm text-red-500 hover:text-red-700 font-medium flex items-center gap-1 transition">
                <i class="fas fa-file-export"></i> Export
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-red-100 text-red-800 uppercase text-xs tracking-wider">
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3 text-right">Total Amount</th>
                        <th class="px-6 py-3 text-center">No. of Expenses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($reports['expensesOverTime'] as $expenseData)
                    <tr class="hover:bg-red-50 transition">
                        <td class="px-6 py-3 text-gray-800 font-medium">{{ $expenseData['date'] }}</td>
                        <td class="px-6 py-3 text-right text-red-600 font-semibold">
                            {{ number_format($expenseData['amount'], 2) }}
                        </td>
                        <td class="px-6 py-3 text-center text-gray-700">
                            {{ $expenseData['expense_count'] ?? 0 }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500 italic">
                            No expense data for the selected period.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- Top Products (Sales) Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Top Products by Revenue -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-4">
                <h6 class="text-white font-bold text-lg">Top 5 Products by Sales Revenue</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200">
                                <th class="py-2">Product</th>
                                <th class="py-2 text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['topProductsByRevenue'] as $product)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                                <td class="py-2">
                                    <div class="flex flex-col">
                                        <span>{{ $product['name'] }}</span>
                                        <div class="h-1 bg-blue-200 rounded-full mt-1">
                                            <div class="h-1 bg-blue-600 rounded-full"
                                                style="width: {{ min(100, ($product['revenue'] / $reports['topProductsByRevenue'][0]['revenue']) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 text-right font-medium text-blue-600">
                                    ${{ number_format($product['revenue'], 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-gray-400">No data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Products by Qty Sold -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-4">
                <h6 class="text-white font-bold text-lg">Top 5 Products by Qty Sold</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200">
                                <th class="py-2">Product</th>
                                <th class="py-2 text-right">Qty Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['topProductsByQtySold'] as $product)
                            <tr class="border-b border-gray-100 hover:bg-green-50 transition">
                                <td class="py-2">
                                    <div class="flex flex-col">
                                        <span>{{ $product['name'] }}</span>
                                        <div class="h-1 bg-green-200 rounded-full mt-1">
                                            <div class="h-1 bg-green-600 rounded-full"
                                                style="width: {{ min(100, ($product['qtySold'] / $reports['topProductsByQtySold'][0]['qtySold']) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 text-right font-medium text-green-600">{{ $product['qtySold'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-gray-400">No data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Products by Profit -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-4">
                <h6 class="text-white font-bold text-lg">Top 5 Products by Sales Profit</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200">
                                <th class="py-2">Product</th>
                                <th class="py-2 text-right">Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['topProductsBySalesProfit'] as $product)
                            <tr class="border-b border-gray-100 hover:bg-purple-50 transition">
                                <td class="py-2">
                                    <div class="flex flex-col">
                                        <span>{{ $product['name'] }}</span>
                                        <div class="h-1 bg-purple-200 rounded-full mt-1">
                                            <div class="h-1 bg-purple-600 rounded-full"
                                                style="width: {{ min(100, ($product['profit'] / $reports['topProductsBySalesProfit'][0]['profit']) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 text-right font-medium text-purple-600">
                                    ${{ number_format($product['profit'], 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-gray-400">No data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    {{-- Top Purchased Products & Expense Categories Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-10">
        <!-- Top Purchased Products by Cost -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-4">
                <h6 class="text-white font-bold text-lg">Top 5 Purchased Products by Cost</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200">
                                <th class="py-2">Product</th>
                                <th class="py-2 text-right">Total Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['topPurchasedProductsByCost'] as $product)
                            <tr class="border-b border-gray-100 hover:bg-yellow-50 transition">
                                <td class="py-2">
                                    <div class="flex flex-col">
                                        <span>{{ $product['name'] }}</span>
                                        <div class="h-1 bg-yellow-200 rounded-full mt-1">
                                            <div class="h-1 bg-yellow-600 rounded-full"
                                                style="width: {{ min(100, ($product['totalCost'] / $reports['topPurchasedProductsByCost'][0]['totalCost']) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 text-right font-medium text-yellow-600">
                                    ${{ number_format($product['totalCost'], 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-gray-400">No data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Purchased Products by Qty -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-4">
                <h6 class="text-white font-bold text-lg">Top 5 Purchased Products by Qty</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200">
                                <th class="py-2">Product</th>
                                <th class="py-2 text-right">Qty Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['topPurchasedProductsByQty'] as $product)
                            <tr class="border-b border-gray-100 hover:bg-green-50 transition">
                                <td class="py-2">
                                    <div class="flex flex-col">
                                        <span>{{ $product['name'] }}</span>
                                        <div class="h-1 bg-green-200 rounded-full mt-1">
                                            <div class="h-1 bg-green-600 rounded-full"
                                                style="width: {{ min(100, ($product['qtyPurchased'] / $reports['topPurchasedProductsByQty'][0]['qtyPurchased']) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 text-right font-medium text-green-600">{{ $product['qtyPurchased'] }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-gray-400">No data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Expense Categories -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-4">
                <h6 class="text-white font-bold text-lg">Top 5 Expense Categories</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200">
                                <th class="py-2">Category</th>
                                <th class="py-2 text-right">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['expensesByCategory'] as $categoryName => $amount)
                            <tr class="border-b border-gray-100 hover:bg-red-50 transition">
                                <td class="py-2">
                                    <div class="flex flex-col">
                                        <span>{{ $categoryName ?: 'Uncategorized' }}</span>
                                        <div class="h-1 bg-red-200 rounded-full mt-1">
                                            <div class="h-1 bg-red-600 rounded-full"
                                                style="width: {{ min(100, ($amount / max($reports['expensesByCategory']) ) * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 text-right font-medium text-red-600">${{ number_format($amount, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-gray-400">No expense data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    {{-- Category Sales Performance --}}
    <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300 mb-10">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-amber-50 to-white p-4">
            <h6 class="text-amber-500 font-bold text-lg">Category Sales Performance</h6>
        </div>

        <!-- Card Body -->
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200">
                            <th class="py-2">Category Name</th>
                            <th class="py-2 text-right">Total Revenue</th>
                            <th class="py-2 text-right">Total Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports['categoriesByRevenue'] as $category)
                        <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                            <td class="py-2">{{ $category['name'] }}</td>

                            <!-- Revenue with bar -->
                            <td class="py-2 text-right font-medium text-blue-600">
                                <div class="flex flex-col">
                                    ${{ number_format($category['revenue'], 2) }}
                                    <div class="h-1 bg-blue-200 rounded-full mt-1">
                                        <div class="h-1 bg-blue-600 rounded-full"
                                            style="width: {{ min(100, ($category['revenue'] / max(array_column($reports['categoriesByRevenue'], 'revenue'))) * 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Profit with bar -->
                            <td class="py-2 text-right font-medium text-purple-600">
                                <div class="flex flex-col">
                                    ${{ number_format($category['profit'], 2) }}
                                    <div class="h-1 bg-purple-200 rounded-full mt-1">
                                        <div class="h-1 bg-purple-600 rounded-full"
                                            style="width: {{ min(100, ($category['profit'] / max(array_column($reports['categoriesByRevenue'], 'profit'))) * 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-400">No category sales data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Balances Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Customer Balances -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-4">
                <h6 class="text-white font-bold text-lg">Customer Balances</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto" style="max-height: 400px;">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200 sticky top-0 bg-white">
                                <th class="py-2">Customer</th>
                                <th class="py-2 text-right">Debit (Owes Us)</th>
                                <th class="py-2 text-right">Credit (We Owe)</th>
                                <th class="py-2 text-right">Net Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['customerBalances'] as $customer)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                                <td class="py-2">{{ $customer['name'] }}</td>
                                <td class="py-2 text-right font-medium text-blue-600">
                                    ${{ number_format($customer['debit'], 2) }}</td>
                                <td class="py-2 text-right font-medium text-gray-600">
                                    ${{ number_format($customer['credit'], 2) }}</td>
                                <td
                                    class="py-2 text-right font-bold
                                {{ $customer['balance'] > 0.009 ? 'text-red-600' : ($customer['balance'] < -0.009 ? 'text-green-600' : 'text-gray-500') }}">
                                    ${{ number_format($customer['balance'], 2) }}
                                    @if($customer['balance'] > 0.009) <small>(Owes Us)</small>
                                    @elseif($customer['balance'] < -0.009) <small>(We Owe)</small>
                                        @else <small>(Settled)</small> @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-400">No customer balance data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Supplier Balances -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-700 p-4">
                <h6 class="text-white font-bold text-lg">Supplier Balances</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto" style="max-height: 400px;">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200 sticky top-0 bg-white">
                                <th class="py-2">Supplier</th>
                                <th class="py-2 text-right">Debit (Owes Us)</th>
                                <th class="py-2 text-right">Credit (We Owe)</th>
                                <th class="py-2 text-right">Net Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['supplierBalances'] as $supplier)
                            <tr class="border-b border-gray-100 hover:bg-yellow-50 transition">
                                <td class="py-2">{{ $supplier['name'] }}</td>
                                <td class="py-2 text-right font-medium text-green-600">
                                    ${{ number_format($supplier['debit'], 2) }}</td>
                                <td class="py-2 text-right font-medium text-gray-600">
                                    ${{ number_format($supplier['credit'], 2) }}</td>
                                <td
                                    class="py-2 text-right font-bold
                                {{ $supplier['balance'] > 0.009 ? 'text-green-600' : ($supplier['balance'] < -0.009 ? 'text-red-600' : 'text-gray-500') }}">
                                    ${{ number_format($supplier['balance'], 2) }}
                                    @if($supplier['balance'] > 0.009) <small>(Supplier Owes Us)</small>
                                    @elseif($supplier['balance'] < -0.009) <small>(We Owe)</small>
                                        @else <small>(Settled)</small> @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-400">No supplier balance data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    {{-- Top Spenders Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Customers by Spending -->
        <div class="bg-white shadow-lg mb-20 rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-4">
                <h6 class="text-white font-bold text-lg">Top 5 Customers by Spending</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200">
                                <th class="py-2">#</th>
                                <th class="py-2">Customer</th>
                                <th class="py-2 text-right">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['topCustomersBySpending'] as $index => $customer)
                            <tr class="border-b border-gray-100 hover:bg-blue-50 transition">
                                <td class="py-2">{{ $index + 1 }}</td>
                                <td class="py-2">{{ $customer['name'] }}</td>
                                <td class="py-2 text-right font-medium text-blue-600">
                                    ${{ number_format($customer['totalSpent'], 2) }}
                                    <div class="h-1 bg-blue-200 rounded-full mt-1">
                                        <div class="h-1 bg-blue-600 rounded-full"
                                            style="width: {{ min(100, ($customer['totalSpent'] / $reports['topCustomersBySpending'][0]['totalSpent']) * 100) }}%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-400">No data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Suppliers by Purchase Value -->
        <div class="bg-white shadow-lg mb-20 rounded-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-700 p-4">
                <h6 class="text-white font-bold text-lg">Top 5 Suppliers by Purchase Value</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200">
                                <th class="py-2">#</th>
                                <th class="py-2">Supplier</th>
                                <th class="py-2 text-right">Total Purchase Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports['topSuppliersByPurchaseValue'] as $index => $supplier)
                            <tr class="border-b border-gray-100 hover:bg-yellow-50 transition">
                                <td class="py-2">{{ $index + 1 }}</td>
                                <td class="py-2">{{ $supplier['name'] }}</td>
                                <td class="py-2 text-right font-medium text-yellow-600">
                                    ${{ number_format($supplier['totalPurchaseValue'], 2) }}
                                    <div class="h-1 bg-yellow-200 rounded-full mt-1">
                                        <div class="h-1 bg-yellow-600 rounded-full"
                                            style="width: {{ min(100, ($supplier['totalPurchaseValue'] / $reports['topSuppliersByPurchaseValue'][0]['totalPurchaseValue']) * 100) }}%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-400">No data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@push('scripts')
{{-- Add any specific JS for this page, e.g., for DataTables or charts --}}
@endpush