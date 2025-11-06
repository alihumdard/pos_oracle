@extends('index')

@section('title', 'Dashboard Reports')

@section('content')
<div class="container-fluid pt-16 sm:pt-6">
    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Dashboard Overview</h1>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('reports.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-sync-alt me-1"></i>Reset View
            </a>
        </div>
    </div>

    {{-- Filter Form for Dashboard --}}
    <div class="filter-container mb-4 card card-body shadow-sm">
        <form action="{{ route('reports.dashboard') }}" method="GET">
            <h6 class="mb-3 text-primary">Filter Dashboard Data</h6>
            <div class="row g-3">
                <div class="col-md-4 col-lg-2">
                    <label for="sales_start_date" class="form-label">Sales From:</label>
                    <input type="date" name="sales_start_date" id="sales_start_date" class="form-control form-control-sm" value="{{ $reports['filter_sales_start_date'] ?? '' }}">
                </div>
                <div class="col-md-4 col-lg-2">
                    <label for="sales_end_date" class="form-label">Sales To:</label>
                    <input type="date" name="sales_end_date" id="sales_end_date" class="form-control form-control-sm" value="{{ $reports['filter_sales_end_date'] ?? '' }}">
                </div>
                <div class="col-md-4 col-lg-2">
                    <label for="purchase_start_date" class="form-label">Purchases From:</label>
                    <input type="date" name="purchase_start_date" id="purchase_start_date" class="form-control form-control-sm" value="{{ $reports['filter_purchase_start_date'] ?? '' }}">
                </div>
                <div class="col-md-4 col-lg-2">
                    <label for="purchase_end_date" class="form-label">Purchases To:</label>
                    <input type="date" name="purchase_end_date" id="purchase_end_date" class="form-control form-control-sm" value="{{ $reports['filter_purchase_end_date'] ?? '' }}">
                </div>
                <div class="col-md-4 col-lg-2">
                    <label for="expense_start_date" class="form-label">Expenses From:</label>
                    <input type="date" name="expense_start_date" id="expense_start_date" class="form-control form-control-sm" value="{{ $reports['filter_expense_start_date'] ?? '' }}">
                </div>
                <div class="col-md-4 col-lg-2">
                    <label for="expense_end_date" class="form-label">Expenses To:</label>
                    <input type="date" name="expense_end_date" id="expense_end_date" class="form-control form-control-sm" value="{{ $reports['filter_expense_end_date'] ?? '' }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Apply Filters</button>
                    <a href="{{ route('reports.dashboard') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear Filters</a>
                </div>
            </div>
        </form>
    </div>

    {{-- KPIs Row 1: Sales, Purchases, Expenses --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($reports['totalRevenue'], 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Purchases</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($reports['totalPurchaseValue'], 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Expenses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($reports['totalExpenses'], 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-receipt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sales Profit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($reports['totalSalesProfit'], 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-piggy-bank fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
             <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Est. Net Profit <small>(Sales Profit - Expenses)</small></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($reports['estimatedNetProfit'], 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-chart-line fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Total Sales Discount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($reports['totalSalesDiscount'], 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-tags fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Owed to Suppliers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($reports['totalOwedToSuppliers'], 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Owed by Suppliers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($reports['totalOwedBySuppliers'], 2) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Sales Over Time Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Sales Over Time (Filtered)</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="schedulesTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead><tr><th>Date</th><th>Revenue</th><th>Profit</th><th>No. of Sales</th></tr></thead>
                    <tbody>
                        @forelse ($reports['salesOverTime'] as $saleData)
                        <tr>
                            <td>{{ $saleData['date'] }}</td>
                            <td>{{ number_format($saleData['revenue'], 2) }}</td>
                            <td>{{ number_format($saleData['profit'], 2) }}</td>
                            <td>{{ $saleData['sales_count'] ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">No sales data for the selected period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Purchases Over Time Table --}}
     <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-warning">Purchases Over Time (Filtered)</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead><tr><th>Date</th><th>Total Cost</th><th>No. of Purchases</th></tr></thead>
                    <tbody>
                        @forelse ($reports['purchasesOverTime'] as $purchaseData)
                        <tr>
                            <td>{{ $purchaseData['date'] }}</td>
                            <td>{{ number_format($purchaseData['cost'], 2) }}</td>
                            <td>{{ $purchaseData['purchase_count'] ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">No purchase data for the selected period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Expenses Over Time Table (NEW) --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-danger">Expenses Over Time (Filtered)</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead><tr><th>Date</th><th class="text-end">Total Amount</th><th class="text-center">No. of Expenses</th></tr></thead>
                    <tbody>
                        @forelse ($reports['expensesOverTime'] as $expenseData)
                        <tr>
                            <td>{{ $expenseData['date'] }}</td>
                            <td class="text-end">{{ number_format($expenseData['amount'], 2) }}</td>
                            <td class="text-center">{{ $expenseData['expense_count'] ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">No expense data for the selected period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top Products (Sales) Row --}}
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Top 5 Products by Sales Revenue</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>Product</th><th class="text-end">Revenue</th></tr></thead>
                            <tbody>
                                @forelse ($reports['topProductsByRevenue'] as $product)
                                <tr><td>{{ $product['name'] }}</td><td class="text-end">{{ number_format($product['revenue'], 2) }}</td></tr>
                                @empty
                                <tr><td colspan="2" class="text-center">No data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Top 5 Products by Qty Sold</h6></div>
                <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>Product</th><th class="text-end">Qty Sold</th></tr></thead>
                            <tbody>
                                @forelse ($reports['topProductsByQtySold'] as $product)
                                <tr><td>{{ $product['name'] }}</td><td class="text-end">{{ $product['qtySold'] }}</td></tr>
                                @empty
                                <tr><td colspan="2" class="text-center">No data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Top 5 Products by Sales Profit</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>Product</th><th class="text-end">Profit</th></tr></thead>
                            <tbody>
                                @forelse ($reports['topProductsBySalesProfit'] as $product)
                                <tr><td>{{ $product['name'] }}</td><td class="text-end">{{ number_format($product['profit'], 2) }}</td></tr>
                                @empty
                                <tr><td colspan="2" class="text-center">No data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Purchased Products & Expense Categories Row --}}
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-warning">Top 5 Purchased Products by Cost</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>Product</th><th class="text-end">Total Cost</th></tr></thead>
                            <tbody>
                                @forelse ($reports['topPurchasedProductsByCost'] as $product)
                                <tr><td>{{ $product['name'] }}</td><td class="text-end">{{ number_format($product['totalCost'], 2) }}</td></tr>
                                @empty
                                <tr><td colspan="2" class="text-center">No data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-warning">Top 5 Purchased Products by Qty</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>Product</th><th class="text-end">Qty Purchased</th></tr></thead>
                            <tbody>
                                @forelse ($reports['topPurchasedProductsByQty'] as $product)
                                <tr><td>{{ $product['name'] }}</td><td class="text-end">{{ $product['qtyPurchased'] }}</td></tr>
                                @empty
                                <tr><td colspan="2" class="text-center">No data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-danger">Top 5 Expense Categories</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>Category</th><th class="text-end">Total Amount</th></tr></thead>
                            <tbody>
                                @forelse ($reports['expensesByCategory'] as $categoryName => $amount)
                                <tr><td>{{ $categoryName ?: 'Uncategorized' }}</td><td class="text-end">{{ number_format($amount, 2) }}</td></tr>
                                @empty
                                <tr><td colspan="2" class="text-center">No expense data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

     {{-- Category Sales Performance --}}
    <div class="card shadow mb-4">
         <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Category Sales Performance</h6></div>
         <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead><tr><th>Category Name</th><th class="text-end">Total Revenue</th><th class="text-end">Total Profit</th></tr></thead>
                    <tbody>
                        @forelse ($reports['categoriesByRevenue'] as $category)
                        <tr>
                            <td>{{ $category['name'] }}</td>
                            <td class="text-end">{{ number_format($category['revenue'], 2) }}</td>
                            <td class="text-end">{{ number_format($category['profit'], 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">No category sales data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Balances Row --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Customer Balances</h6></div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                            <thead><tr><th>Customer</th><th class="text-end">Debit (Owes Us)</th><th class="text-end">Credit (We Owe)</th><th class="text-end">Net Balance</th></tr></thead>
                            <tbody>
                                @forelse ($reports['customerBalances'] as $customer)
                                <tr>
                                    <td>{{ $customer['name'] }}</td>
                                    <td class="text-end">{{ number_format($customer['debit'], 2) }}</td>
                                    <td class="text-end">{{ number_format($customer['credit'], 2) }}</td>
                                    <td class="text-end {{ $customer['balance'] > 0.009 ? 'text-danger font-weight-bold' : ($customer['balance'] < -0.009 ? 'text-success font-weight-bold' : '') }}">
                                        {{ number_format($customer['balance'], 2) }}
                                        @if($customer['balance'] > 0.009) <small>(Owes Us)</small> @elseif($customer['balance'] < -0.009) <small>(We Owe)</small> @else <small>(Settled)</small> @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center">No customer balance data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-warning">Supplier Balances</h6></div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                            <thead><tr><th>Supplier</th><th class="text-end">Debit (Owes Us)</th><th class="text-end">Credit (We Owe)</th><th class="text-end">Net Balance</th></tr></thead>
                            <tbody>
                                @forelse ($reports['supplierBalances'] as $supplier)
                                <tr>
                                    <td>{{ $supplier['name'] }}</td>
                                    <td class="text-end">{{ number_format($supplier['debit'], 2) }}</td>
                                    <td class="text-end">{{ number_format($supplier['credit'], 2) }}</td>
                                    <td class="text-end {{ $supplier['balance'] > 0.009 ? 'text-success font-weight-bold' : ($supplier['balance'] < -0.009 ? 'text-danger font-weight-bold' : '') }}">
                                        {{ number_format($supplier['balance'], 2) }}
                                         @if($supplier['balance'] > 0.009) <small>(Supplier Owes Us)</small> @elseif($supplier['balance'] < -0.009) <small>(We Owe)</small> @else <small>(Settled)</small> @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center">No supplier balance data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Spenders Row --}}
    <div class="row">
        <div class="col-lg-6">
             <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Top 5 Customers by Spending</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>#</th><th>Customer</th><th class="text-end">Total Spent</th></tr></thead>
                            <tbody>
                                @forelse ($reports['topCustomersBySpending'] as $index => $customer)
                                <tr><td>{{ $index + 1 }}</td><td>{{ $customer['name'] }}</td><td class="text-end">{{ number_format($customer['totalSpent'], 2) }}</td></tr>
                                @empty
                                <tr><td colspan="3" class="text-center">No data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-warning">Top 5 Suppliers by Purchase Value</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>#</th><th>Supplier</th><th class="text-end">Total Purchase Value</th></tr></thead>
                            <tbody>
                                @forelse ($reports['topSuppliersByPurchaseValue'] as $index => $supplier)
                                <tr><td>{{ $index + 1 }}</td><td>{{ $supplier['name'] }}</td><td class="text-end">{{ number_format($supplier['totalPurchaseValue'], 2) }}</td></tr>
                                @empty
                                <tr><td colspan="3" class="text-center">No data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')


    <script src="{{asset('assets/plugins/sparklines/sparkline.js')}}"></script>
    <script src="{{asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/demo.js')}}"></script>
    <script src="{{asset('assets/dist/js/pages/dashboard.js')}}"></script>

<script>
$(document).ready(function() {
    var table = $('#schedulesTable').DataTable({
        paging: true,
        searching: true,
        ordering: false,
        info: true,
        // 1. Disable built-in responsive (prevents column hiding)
        responsive: false,
        // 2. Enable horizontal scrolling
        scrollX: true,
        // 3. Updated DOM with 'flex-wrap' for better mobile button/search stacking
        dom: '<"top d-flex flex-wrap justify-content-between align-items-center mb-3"Bf>rt<"bottom d-flex flex-wrap justify-content-between align-items-center"lip>',
        buttons: [
            { extend: 'copyHtml5', text: ' Copy', className: 'btn btn-secondary btn-sm' },
            { extend: 'csvHtml5', text: ' CSV', className: 'btn btn-info btn-sm' },
            { extend: 'excelHtml5', text: ' Excel', className: 'btn btn-success btn-sm' },
            { extend: 'pdfHtml5', text: ' PDF', className: 'btn btn-danger btn-sm' },
            { extend: 'print', text: ' Print', className: 'btn btn-primary btn-sm' }
        ]
    });

    // If standard movement isn't working due to complex DOM, try this explicitly if needed:
    // table.buttons().container().appendTo('#schedulesTable_wrapper .top'); 
    // (Your custom DOM might already handle this automatically depending on DT version)
});
</script>
@endpush