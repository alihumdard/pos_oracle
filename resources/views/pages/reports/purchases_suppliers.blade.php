@extends('index')

@section('title', 'Purchases and Suppliers Report')

@section('content')
<style>
:root {
    --primary-color: #ffc107;
    /* Using warning color for purchases */
    --primary-hover: #e0a800;
    --text-color: #2b2d42;
    --light-gray: #e9ecef;
    --border-radius: 8px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}

.card-header-custom {
    background-color: var(--primary-color);
    color: #fff;
}

.card-header-custom h6 {
    color: #fff !important;
}

.table thead th {
    background-color: var(--primary-color);
    color: white;
    font-weight: 500;
    white-space: nowrap;
}

.table tbody td.notes-column {
    white-space: pre-wrap;
    word-break: break-word;
}

.filter-container {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--light-gray);
}

.balance-positive {
    color: green;
    font-weight: bold;
}

.balance-negative {
    color: red;
    font-weight: bold;
}

.balance-zero {
    color: #6c757d;
}
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 pt-10 pb-3 font-bold text-gray-800">Purchases and Suppliers Report</h1>
    </div>

    {{-- Filters --}}
    <div class="card shadow-lg border-0 rounded-3 mb-4">
        <!-- Header -->
        <div class="card-header bg-gradient-primary text-white rounded-top-3 py-3 d-flex align-items-center">
            <i class="bi bi-funnel-fill fs-4 me-2"></i>
            <h6 class="m-0 fw-bold">Filter Purchases</h6>
        </div>

        <!-- Body -->
        <div class="card-body">
            <form action="{{ route('reports.purchases_suppliers') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <!-- Start Date -->
                    <div class="col-md-3">
                        <label for="purchase_start_date" class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="purchase_start_date" id="purchase_start_date"
                            class="form-control form-control-sm shadow-sm" value="{{ request('purchase_start_date') }}">
                    </div>

                    <!-- End Date -->
                    <div class="col-md-3">
                        <label for="purchase_end_date" class="form-label fw-semibold">End Date</label>
                        <input type="date" name="purchase_end_date" id="purchase_end_date"
                            class="form-control form-control-sm shadow-sm" value="{{ request('purchase_end_date') }}">
                    </div>

                    <!-- Supplier -->
                    <div class="col-md-3">
                        <label for="purchase_supplier_id" class="form-label fw-semibold">Supplier</label>
                        <select name="purchase_supplier_id" id="purchase_supplier_id"
                            class="form-control form-control-sm select2-filter shadow-sm" style="width:100%;">
                            <option value="">All Suppliers</option>
                            @foreach($reports['filterSuppliers'] as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ request('purchase_supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->supplier }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product -->
                    <div class="col-md-3">
                        <label for="purchase_product_id" class="form-label fw-semibold">Product</label>
                        <select name="purchase_product_id" id="purchase_product_id"
                            class="form-control form-control-sm select2-filter shadow-sm" style="width:100%;">
                            <option value="">All Products</option>
                            @foreach($reports['filterProducts'] as $product)
                            <option value="{{ $product->id }}"
                                {{ request('purchase_product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->item_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-12 text-end mt-3">
                        <button type="submit" class="btn btn-sm btn-primary shadow-sm me-2">
                            <i class="fas fa-filter me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('reports.purchases_suppliers') }}"
                            class="btn btn-sm btn-outline-secondary shadow-sm">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- Supplier Purchase Summary --}}
   <div class="card shadow-lg border-0 rounded-3 mb-4">
    <!-- Card Header -->
    <div class="card-header bg-gradient-primary text-white rounded-top-3 py-3 d-flex align-items-center">
        <i class="bi bi-truck fs-4 me-2"></i>
        <h6 class="m-0 fw-bold">Supplier Purchase Summary</h6>
    </div>

    <!-- Card Body -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="example1" width="100%">
                <thead class="bg-gradient-primary">
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
                        <td class="fw-semibold">{{ $summary['name'] }}</td>
                        <td class="text-end text-success fw-bold">
                            {{ number_format($summary['total_purchase_value'], 2) }}
                        </td>
                        <td class="text-center text-primary fw-semibold">
                            {{ $summary['purchase_count'] }}
                        </td>
                        <td class="text-end fw-bold 
                            @if($summary['balance'] > 0.009) text-success 
                            @elseif($summary['balance'] < -0.009) text-danger 
                            @else text-muted @endif">
                            {{ number_format($summary['balance'], 2) }}
                            @if($summary['balance'] > 0.009) <span class="badge bg-success ms-2">Owes Us</span>
                            @elseif($summary['balance'] < -0.009) <span class="badge bg-danger ms-2">We Owe</span>
                            @else <span class="badge bg-secondary ms-2">Settled</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No supplier summary data available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

    {{-- All Purchases List --}}
   <div class="card shadow-lg border-0 rounded-3 mb-4">
    <!-- Card Header -->
    <div class="card-header bg-gradient-primary text-white rounded-top-3 py-3 d-flex align-items-center">
        <i class="bi bi-receipt fs-4 me-2"></i>
        <h6 class="m-0 fw-bold">Detailed Purchases List</h6>
    </div>

    <!-- Card Body -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle" id="example2" width="100%">
                <thead class="table-light">
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
                        <td class="fw-semibold">{{ $purchase->supplier->supplier ?? 'N/A' }}</td>
                        <td>{{ $purchase->product->item_name ?? 'N/A' }}</td>
                        <td class="text-end text-primary fw-semibold">{{ $purchase->quantity }}</td>
                        <td class="text-end text-info fw-semibold">{{ number_format($purchase->unit_price, 2) }}</td>
                        <td class="text-end text-success fw-bold">{{ number_format($purchase->total_amount, 2) }}</td>
                        <td class="text-end text-warning fw-bold">{{ number_format($purchase->cash_paid_at_purchase, 2) }}</td>
                        <td class="notes-column text-muted fst-italic">{{ $purchase->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No purchases found for the selected criteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2-filter').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });
    // If you want DataTables for client-side interactions:
    // $('#supplierSummaryTable').DataTable();
    // $('#allPurchasesTable').DataTable();
});
</script>
<script>
  $(document).ready(function () {
    $('#example1').DataTable({
        dom:
            "<'row mb-3'<'col-md-6 d-flex align-items-center'B><'col-md-6 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
      buttons: [
                'copy', 'excel', 'pdf', 'print', 'colvis'
            ],
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        responsive: true,
        columnDefs: [
            {
                orderable: false,
                targets: 3
            }
        ]
    });
});

</script>
<script>
  $(document).ready(function () {
    $('#example2').DataTable({
        dom:
            "<'row mb-3'<'col-md-6 d-flex align-items-center'B><'col-md-6 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
      buttons: [
                'copy', 'excel', 'pdf', 'print', 'colvis'
            ],
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        responsive: true,
        columnDefs: [
            {
                orderable: false,
                targets: 3
            }
        ]
    });
});

</script>
@endpush