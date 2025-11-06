@extends('index')

@section('title', 'Purchases and Suppliers Report')

@section('content')
<style>
    :root {
        --primary-color: #ffc107; /* Using warning color for purchases */
        --primary-hover: #e0a800;
        --text-color: #2b2d42;
        --light-gray: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .card { border: none; border-radius: var(--border-radius); box-shadow: var(--box-shadow); }
    .card-header-custom { background-color: var(--primary-color); color: #fff; }
    .card-header-custom h6 { color: #fff !important; }
    .table thead th { background-color: var(--primary-color); color: white; font-weight: 500; white-space: nowrap; }
    .table tbody td.notes-column { white-space: pre-wrap; word-break: break-word; }
    .filter-container { background: white; border-radius: var(--border-radius); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--light-gray); }
    .balance-positive { color: green; font-weight: bold; }
    .balance-negative { color: red; font-weight: bold; }
    .balance-zero { color: #6c757d; }
</style>

<div class="container-fluid pt-16 sm:pt-6">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Purchases and Suppliers Report</h1>
    </div>

    {{-- Filters --}}
    <div class="filter-container">
        <form action="{{ route('reports.purchases_suppliers') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="purchase_start_date" class="form-label">Start Date</label>
                    <input type="date" name="purchase_start_date" id="purchase_start_date" class="form-control form-control-sm" value="{{ request('purchase_start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="purchase_end_date" class="form-label">End Date</label>
                    <input type="date" name="purchase_end_date" id="purchase_end_date" class="form-control form-control-sm" value="{{ request('purchase_end_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="purchase_supplier_id" class="form-label">Supplier</label>
                    <select name="purchase_supplier_id" id="purchase_supplier_id" class="form-control form-control-sm select2-filter" style="width:100%;">
                        <option value="">All Suppliers</option>
                        @foreach($reports['filterSuppliers'] as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('purchase_supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->supplier }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="purchase_product_id" class="form-label">Product</label>
                    <select name="purchase_product_id" id="purchase_product_id" class="form-control form-control-sm select2-filter" style="width:100%;">
                        <option value="">All Products</option>
                        @foreach($reports['filterProducts'] as $product)
                        <option value="{{ $product->id }}" {{ request('purchase_product_id') == $product->id ? 'selected' : '' }}>{{ $product->item_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 text-end mt-3">
                    <button type="submit" class="btn btn-sm" style="background-color: var(--primary-color); color:white;"><i class="fas fa-filter me-1"></i>Apply Filters</button>
                    <a href="{{ route('reports.purchases_suppliers') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times me-1"></i>Clear Filters</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Supplier Purchase Summary --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 card-header-custom">
            <h6 class="m-0 font-weight-bold">Supplier Purchase Summary</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="supplierSummaryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th class="text-end">Total Purchase Value</th>
                            <th class="text-center">Purchase Count</th>
                            <th class="text-end">Current Overall Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports['supplierPurchaseSummary'] as $summary)
                        <tr>
                            <td>{{ $summary['name'] }}</td>
                            <td class="text-end">{{ number_format($summary['total_purchase_value'], 2) }}</td>
                            <td class="text-center">{{ $summary['purchase_count'] }}</td>
                            <td class="text-end @if($summary['balance'] > 0.009) balance-positive @elseif($summary['balance'] < -0.009) balance-negative @else balance-zero @endif">
                                {{ number_format($summary['balance'], 2) }}
                                @if($summary['balance'] > 0.009) (Owes Us) @elseif($summary['balance'] < -0.009) (We Owe) @else (Settled) @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">No supplier summary data available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- All Purchases List --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 card-header-custom">
            <h6 class="m-0 font-weight-bold">Detailed Purchases List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="allPurchasesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Product</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total Amount</th>
                            <th class="text-end">Cash Paid</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports['allPurchases'] as $purchase)
                        <tr>
                            <td>{{ $purchase->purchase_date->format('d M, Y') }}</td>
                            <td>{{ $purchase->supplier->supplier ?? 'N/A' }}</td>
                            <td>{{ $purchase->product->item_name ?? 'N/A' }}</td>
                            <td class="text-end">{{ $purchase->quantity }}</td>
                            <td class="text-end">{{ number_format($purchase->unit_price, 2) }}</td>
                            <td class="text-end">{{ number_format($purchase->total_amount, 2) }}</td>
                            <td class="text-end">{{ number_format($purchase->cash_paid_at_purchase, 2) }}</td>
                            <td class="notes-column">{{ $purchase->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">No purchases found for the selected criteria.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                 {{-- If you paginate $reports['allPurchases'] in controller, add links here --}}
                 {{-- Example: @if($reports['allPurchases'] instanceof \Illuminate\Pagination\LengthAwarePaginator) {{ $reports['allPurchases']->appends(request()->query())->links() }} @endif --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2-filter').select2({ placeholder: "Select an option", allowClear: true, width: '100%' });
    // If you want DataTables for client-side interactions:
    // $('#supplierSummaryTable').DataTable();
    // $('#allPurchasesTable').DataTable();
});
</script>
@endpush