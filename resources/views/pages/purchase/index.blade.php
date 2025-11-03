@extends('index')
@section('content')
    <style>
        :root {
            --primary-color: #4361ee;
            --light-gray: #e9ecef;
            --border-radius: 8px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --text-color: #2b2d42;
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--light-gray);
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            border: none;
            padding: 1rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--light-gray);
        }

        .table tbody td.notes-column {
            white-space: pre-wrap;
            word-break: break-word;
            min-width: 150px;
            max-width: 300px;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-primary-custom:hover {
            background-color: #3a56d4;
            border-color: #3a56d4;
        }

        .filter-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--light-gray);
        }

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
        .overall-status-supplier-owes-us {
            color: green;
            font-weight: bold;
        }

        /* GREEN: Supplier owes us */
        .overall-status-we-owe-supplier {
            color: red;
            font-weight: bold;
        }

        /* RED: We owe supplier */
        .overall-status-settled {
            color: #6c757d;
            font-weight: normal;
        }

        /* Neutral (grey) for settled */

        /* Modal styles */
        .modal-body .table tfoot th {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-color);
        }

        .modal-body .table thead th {
            background-color: var(--light-gray);
            color: var(--text-color);
        }
    </style>

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">
                <div class="filter-container">
                    <form action="{{ route('purchase.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6 col-lg-2">
                                <label for="start_date_filter" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date_filter" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <label for="end_date_filter" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date_filter" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label for="supplier_id_filter" class="form-label">Supplier</label>
                                <select name="supplier_id_filter" id="supplier_id_filter"
                                    class="form-control select2-filter" style="width:100%;">
                                    <option value="">All Suppliers</option>
                                    @foreach($filter_suppliers as $s_item)
                                        <option value="{{ $s_item->id }}" {{ request('supplier_id_filter') == $s_item->id ? 'selected' : '' }}>{{ $s_item->supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label for="product_id_filter" class="form-label">Product</label>
                                <select name="product_id_filter" id="product_id_filter" class="form-control select2-filter"
                                    style="width:100%;">
                                    <option value="">All Products</option>
                                    @foreach($filter_products as $p_item)
                                        <option value="{{ $p_item->id }}" {{ request('product_id_filter') == $p_item->id ? 'selected' : '' }}>{{ $p_item->item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-lg-2 text-lg-end mt-3 mt-lg-0 d-flex flex-column justify-content-end">
                                <button type="submit" class="btn btn-primary-custom w-100 mb-1" style="height: 40px;"><i
                                        class="fas fa-filter me-1"></i>Filter</button>
                                <a href="{{ route('purchase.index') }}" class="btn btn-secondary w-100"
                                    style="height: 40px;"><i class="fas fa-times me-1"></i>Clear</a>
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
                                        <th>Model</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Total Amount</th>
                                        <th class="text-end">Cash Paid (This Purchase)</th>
                                        <th>Supplier Overall Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases as $index => $purchase)
                                        @php
                                            $overall_status_text = 'N/A';
                                            $overall_status_class = 'overall-status-settled'; // Default to settled

                                            if ($purchase->supplier) {
                                                $net_overall_supplier_balance = $purchase->supplier->debit - $purchase->supplier->credit;
                                                $epsilon = 0.009;

                                                if ($net_overall_supplier_balance > $epsilon) {
                                                    $overall_status_text = 'Supplier Owes You: ' . number_format($net_overall_supplier_balance, 2);
                                                    $overall_status_class = 'overall-status-supplier-owes-us';
                                                } elseif ($net_overall_supplier_balance < -$epsilon) {
                                                    $overall_status_text = 'You Owe Supplier: ' . number_format(abs($net_overall_supplier_balance), 2);
                                                    $overall_status_class = 'overall-status-we-owe-supplier';
                                                } else {
                                                    $overall_status_text = 'Overall Settled';
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
                                            <td>{{ $purchase->product->item_code ?? 'N/A' }}</td>
                                            <td class="text-end">{{ $purchase->quantity }}</td>
                                            <td class="text-end">{{ number_format($purchase->unit_price, 2) }}</td>
                                            <td class="text-end">{{ number_format($purchase->total_amount, 2) }}</td>
                                            <td class="text-end">{{ number_format($purchase->cash_paid_at_purchase, 2) }}</td>
                                            <td class="{{ $overall_status_class }}">
                                                {{ $overall_status_text }}
                                            </td>
                                            <td>
                                                {{-- ===== 'data-purchase-date' Add Hua Hai ===== --}}
                                                @if ($purchase->supplier)
                                                    <button type="button" class="btn btn-sm btn-success btn-view-supplier"
                                                        title="View Supplier Summary" data-toggle="modal"
                                                        data-target="#supplierSummaryModal"
                                                        data-supplier-id="{{ $purchase->supplier->id }}"
                                                        data-supplier-name="{{ $purchase->supplier->supplier }}"
                                                        data-purchase-date="{{ $purchase->purchase_date->format('Y-m-d') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('purchase.edit', $purchase->id) }}"
                                                    class="btn btn-sm btn-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">No purchases found.</td>
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


    {{-- ===== MODAL KA HTML ===== --}}
    <div class="modal fade" id="supplierSummaryModal" tabindex="-1" aria-labelledby="supplierSummaryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierSummaryModalLabel">Supplier Summary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modal-loader" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="modal-supplier-content" style="display: none;">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Product Name</th>
                                        <th>Model</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="supplier-products-tbody">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Grand Total:</th>
                                        <th class="text-end" id="supplier-grand-total"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div id="modal-error-message" class="text-center text-danger" style="display: none;">
                        Could not load supplier details.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@pushOnce('scripts')
{{-- ===== JAVASCRIPT (formatDate function tabdeel hua hai) ===== --}}
<script>
    $(document).ready(function () {
        // Select2 for filters
        $('.select2-filter').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });

        // Function to format numbers as currency
        function formatCurrency(number) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
        }

        // ===== YEH FUNCTION UPDATE HUA HAI =====
        // Function to format date as "d M, Y" (e.g., "03 Nov, 2025")
        function formatDate(dateString) {
             // Mahinon (Months) ke naam
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            
            // ===== YEH LINE TABDEEL HUI HAI =====
            // Date object (Yeh Laravel ke full timestamp '2025-11-03T...Z' ko bhi handle kar lega)
            const date = new Date(dateString); 
            
            // Din (Day) hasil karein aur '0' add karein agar zaroorat ho (03)
            const day = String(date.getDate()).padStart(2, '0');
            
            // Mahinay (Month) ka naam array se hasil karein (Nov)
            const month = months[date.getMonth()];
            
            // Saal (Year) hasil karein (2025)
            const year = date.getFullYear();
            
            // Format mein jor dein
            return `${day} ${month}, ${year}`;
        }

        // Event delegation (Double click se bachne ke liye .off() ke sath)
        $(document).off('click', '.btn-view-supplier').on('click', '.btn-view-supplier', function () {
            
            // Data button se hasil karein
            var supplierId = $(this).data('supplier-id');
            var supplierName = $(this).data('supplier-name');
            var purchaseDate = $(this).data('purchase-date'); 

            // URL banayein
            var url = "{{ route('supplier.purchase.summary', ['supplier' => ':id']) }}";
            url = url.replace(':id', supplierId);

            // 1. Modal ko reset karein
            $('#supplierSummaryModalLabel').text('Purchase Summary: ' + supplierName);
            $('#modal-loader').show();
            $('#modal-supplier-content').hide();
            $('#modal-error-message').hide();
            $('#supplier-products-tbody').empty();
            $('#supplier-grand-total').text('');

            // 2. AJAX request (Date ko data mein bhej rahe hain)
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {
                    date: purchaseDate // <-- Date ko query parameter ke tor par bhejein
                },
                success: function (response) {
                    if (response.status === 'success') {
                        // 3. Modal ko naye data se populate karein
                        var productsTbody = $('#supplier-products-tbody');
                        var counter = 1;

                        if (response.purchases.length > 0) {
                            response.purchases.forEach(function (item) {
                                
                                // Naya row structure
                                var row = `<tr>
                                    <td>${counter}</td>
                                    <td>${formatDate(item.purchase_date)}</td>
                                    <td>${item.product ? item.product.item_name : 'N/A'}</td>
                                    <td>${item.product ? item.product.item_code : 'N/A'}</td>
                                    <td class="text-end">${item.quantity}</td>
                                    <td class="text-end">${formatCurrency(item.total_amount)}</td>
                                </tr>`;
                                productsTbody.append(row);
                                counter++;
                            });
                        } else {
                            var row = `<tr><td colspan="6" class="text-center">No purchases found on or before this date.</td></tr>`;
                            productsTbody.append(row);
                        }

                        // Grand total set karein
                        $('#supplier-grand-total').text(formatCurrency(response.grand_total));

                        // 4. Content show karein
                        $('#modal-loader').hide();
                        $('#modal-supplier-content').show();
                    } else {
                        $('#modal-loader').hide();
                        $('#modal-error-message').text(response.message || 'An error occurred.').show();
                    }
                },
                error: function (xhr) {
                    console.error('AJAX Error:', xhr);
                    $('#modal-loader').hide();
                    $('#modal-error-message').text('Failed to fetch data. Please try again.').show();
                }
            });
        });

    });
</script>
@endpushOnce