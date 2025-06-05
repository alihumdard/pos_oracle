@extends('index')

@section('content')
<style>
    :root {
        --primary-color: #4361ee;
        --light-gray: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        --text-color: #2b2d42;
    }
    .card { border: none; border-radius: var(--border-radius); box-shadow: var(--box-shadow); overflow: hidden; }
    .card-header { background-color: white; border-bottom: 1px solid var(--light-gray); padding: 1.25rem 1.5rem; }
    .card-title { font-weight: 600; color: var(--text-color); margin-bottom: 0; }
    .table thead th { background-color: var(--primary-color); color: white; font-weight: 500; border: none; padding: 1rem; white-space: nowrap; }
    .table tbody td { padding: 1rem; vertical-align: middle; border-top: 1px solid var(--light-gray); }
    .table tbody td.notes-column { white-space: pre-wrap; word-break: break-word; min-width: 150px; max-width:300px; }

    .btn-primary-custom { background-color: var(--primary-color); border-color: var(--primary-color); color: white; }
    .btn-primary-custom:hover { background-color: #3a56d4; border-color: #3a56d4; }
    .filter-container { background: white; border-radius: var(--border-radius); box-shadow: var(--box-shadow); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--light-gray); }
    .filter-container .form-control,
    .filter-container .select2-container--default .select2-selection--single {
        height: 40px !important;
    }
    .filter-container .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: calc(40px - 2px - (.375rem * 2)) !important;
        padding-left: .75rem !important;
    }
    .filter-container .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(40px - 2px) !important;
    }

    /* UPDATED CSS for overall supplier balance status */
    .overall-status-supplier-owes-us { color: green; font-weight: bold; } /* GREEN: Supplier owes us */
    .overall-status-we-owe-supplier { color: red; font-weight: bold; }   /* RED: We owe supplier */
    .overall-status-settled { color: #6c757d; font-weight: normal; } /* Neutral (grey) for settled */
    /* Alternative for settled if you want it green:
    .overall-status-settled { color: green; font-weight: normal; }
    */
</style>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="filter-container">
                <form action="{{ route('purchase.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6 col-lg-2">
                            <label for="start_date_filter" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date_filter" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label for="end_date_filter" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date_filter" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="supplier_id_filter" class="form-label">Supplier</label>
                            <select name="supplier_id_filter" id="supplier_id_filter" class="form-control select2-filter" style="width:100%;">
                                <option value="">All Suppliers</option>
                                @foreach($filter_suppliers as $s_item)
                                <option value="{{ $s_item->id }}" {{ request('supplier_id_filter') == $s_item->id ? 'selected' : '' }}>{{ $s_item->supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                         <div class="col-md-6 col-lg-3">
                            <label for="product_id_filter" class="form-label">Product</label>
                            <select name="product_id_filter" id="product_id_filter" class="form-control select2-filter" style="width:100%;">
                                <option value="">All Products</option>
                                @foreach($filter_products as $p_item)
                                <option value="{{ $p_item->id }}" {{ request('product_id_filter') == $p_item->id ? 'selected' : '' }}>{{ $p_item->item_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-lg-2 text-lg-end mt-3 mt-lg-0 d-flex flex-column justify-content-end">
                             <button type="submit" class="btn btn-primary-custom w-100 mb-1" style="height: 40px;"><i class="fas fa-filter me-1"></i>Filter</button>
                            <a href="{{ route('purchase.index') }}" class="btn btn-secondary w-100" style="height: 40px;"><i class="fas fa-times me-1"></i>Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <h3 class="card-title mb-2 mb-md-0">Purchase History</h3>
                    <a href="{{ route('purchase.create') }}" class="btn btn-primary-custom">
                        <i class="fas fa-plus me-2"></i>Add New Purchase
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Product</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total Amount</th>
                                    <th class="text-end">Cash Paid (This Purchase)</th>
                                    <th>Supplier Overall Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $index => $purchase)
                                @php
                                    $overall_status_text = 'N/A';
                                    $overall_status_class = 'overall-status-settled'; // Default to settled

                                    if ($purchase->supplier) {
                                        // Calculate supplier's current overall net balance
                                        // $purchase->supplier->debit (supplier owes us)
                                        // $purchase->supplier->credit (we owe supplier)
                                        $net_overall_supplier_balance = $purchase->supplier->debit - $purchase->supplier->credit;
                                        $epsilon = 0.009; // For floating point comparisons

                                        if ($net_overall_supplier_balance > $epsilon) { // Supplier owes us overall
                                            $overall_status_text = 'Supplier Owes You: ' . number_format($net_overall_supplier_balance, 2);
                                            $overall_status_class = 'overall-status-supplier-owes-us'; // GREEN
                                        } elseif ($net_overall_supplier_balance < -$epsilon) { // We owe supplier overall
                                            $overall_status_text = 'You Owe Supplier: ' . number_format(abs($net_overall_supplier_balance), 2);
                                            $overall_status_class = 'overall-status-we-owe-supplier'; // RED
                                        } else { // Overall balance is zero or very close to it
                                            $overall_status_text = 'Overall Settled';
                                            // $overall_status_class remains 'overall-status-settled' (defaulted to grey, can be green if preferred)
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $purchases->firstItem() + $index }}</td>
                                    <td>{{ $purchase->purchase_date->format('d M, Y') }}</td>
                                    <td>
                                        {{ $purchase->supplier->supplier ?? 'N/A' }}
                                    </td>
                                    <td>{{ $purchase->product->item_name ?? 'N/A' }}</td>
                                    <td class="text-end">{{ $purchase->quantity }}</td>
                                    <td class="text-end">{{ number_format($purchase->unit_price, 2) }}</td>
                                    <td class="text-end">{{ number_format($purchase->total_amount, 2) }}</td>
                                    <td class="text-end">{{ number_format($purchase->cash_paid_at_purchase, 2) }}</td>
                                    <td class="{{ $overall_status_class }}">
                                        {{ $overall_status_text }}
                                    </td>
                                    <td class="notes-column">{{ $purchase->notes ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No purchases found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $purchases->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@pushOnce('scripts')
<script>
$(document).ready(function() {
    $('.select2-filter').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endPushOnce