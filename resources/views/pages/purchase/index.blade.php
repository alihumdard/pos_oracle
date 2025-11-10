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

        /* UPDATED CSS for balance status */
        .overall-status-supplier-owes-us {
            color: green;
            font-weight: bold;
        }

        /* GREEN: Supplier owes us (We overpaid) */
        .overall-status-we-owe-supplier {
            color: red;
            font-weight: bold;
        }

        /* RED: We owe supplier (Underpaid) */
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

        /* ===== NEW CSS FOR SUPPLIER CARDS ===== */
        .supplier-card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            /* Spacing between cards */
            margin-bottom: 1.5rem;
        }

        .supplier-card {
            flex: 1;
            min-width: 180px;
            /* Minimum width for cards */
            padding: 1rem;
            border-radius: var(--border-radius);
            background: #f8f9fa;
            border: 1px solid var(--light-gray);
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .supplier-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--box-shadow);
        }

        .supplier-card.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: var(--box-shadow);
        }

        .supplier-card strong {
            display: block;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .supplier-card span {
            font-size: 0.85rem;
        }

        /* ===== END OF NEW CSS ===== */
    </style>

    <div class="container-fluid pt-16 sm:pt-6">
        <div class="row">
            <div class="col-12">

                <div class="supplier-card-container">
                    <div class="supplier-card {{ request('supplier_id_filter') == '' ? 'active' : '' }}"
                        data-supplier-id="">
                        <strong>All Suppliers</strong>
                        <span>View All Purchases</span>
                    </div>

                    @foreach($filter_suppliers as $s_item)
                        <div class="supplier-card {{ request('supplier_id_filter') == $s_item->id ? 'active' : '' }}"
                            data-supplier-id="{{ $s_item->id }}">
                            <strong>{{ $s_item->supplier }}</strong>
                            <span>View Purchases</span>
                        </div>
                    @endforeach
                </div>
                <div class="filter-container">
                    <form action="{{ route('purchase.index') }}" method="GET" id="filterForm">
                        <input type="hidden" name="supplier_id_filter" id="supplier_id_filter_hidden"
                            value="{{ request('supplier_id_filter') }}">

                        <div class="row g-3 align-items-end">
                            <div class="col-md-6 col-lg-3">
                                <label for="start_date_filter" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date_filter" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label for="end_date_filter" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date_filter" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>

                            <div class="col-md-6 col-lg-4">
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
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 gap-md-0">
  <!-- Left: Title -->
  <h3 class="card-title mb-0">Purchase History</h3>

  <!-- Right: Buttons Group -->
  <div class="d-flex flex-wrap justify-content-md-end gap-2">
    <a href="{{ route('purchase.create') }}" class="btn btn-primary-custom">
      <i class="fas fa-plus me-2"></i>Add New Purchase
    </a>
    <a href="{{ route('payment.create') }}" class="btn btn-info">
      <i class="fas fa-dollar-sign me-2"></i>Add Payment
    </a>
  </div>
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
                                        <th class="text-end">Cash Paid</th>
                                        <th>Remaining Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases as $index => $purchase)
                                        @php
                                            $overall_status_text = 'N/A';
                                            $overall_status_class = 'overall-status-settled';

                                            // Calculate balance for THIS purchase only: Total - Paid
                                            $current_purchase_balance = $purchase->total_amount - $purchase->cash_paid_at_purchase;
                                            $epsilon = 0.009;

                                            if ($current_purchase_balance > $epsilon) {
                                                // Positive balance means we still owe money for this purchase
                                                $overall_status_text = ' ' . number_format($current_purchase_balance, 2);
                                                $overall_status_class = 'overall-status-we-owe-supplier';
                                            } elseif ($current_purchase_balance < -$epsilon) {
                                                // Negative balance means we overpaid (unlikely but possible)
                                                $overall_status_text = '+' . number_format(abs($current_purchase_balance), 2);
                                                $overall_status_class = 'overall-status-supplier-owes-us';
                                            } else {
                                                // Balance is effectively zero
                                                $overall_status_text = 'No Balance'; // Corrected spelling
                                                $overall_status_class = 'overall-status-settled';
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
                                            <td class="{{ $overall_status_class }}"> {{ $overall_status_text }}
                                            </td>
                                            <td>
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


    {{-- ===== MODAL HTML (NOW modal-xl) ===== --}}
    <div class="modal fade" id="supplierSummaryModal" tabindex="-1" aria-labelledby="supplierSummaryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
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
                            <table id="supplier-summary-table" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Product Name</th>
                                        <th>Model</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Total Amount</th>
                                        <th class="text-end">Cash Paid</th>
                                        <th class="text-end">Total Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="supplier-products-tbody">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Grand Total:</th>
                                        <th class="text-end" id="supplier-grand-total"></th>
                                        <th class="text-end" id="supplier-grand-cash"></th>
                                        <th class="text-end" id="supplier-grand-blance"></th>
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
<script>
    $(document).ready(function () { // <-- FIX 1: Syntax theek kar di
        // Select2 for product filter ONLY
        $('#product_id_filter').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });

        // ===== NEW JS FOR SUPPLIER CARD CLICK =====
        $(document).on('click', '.supplier-card', function () {
            var supplierId = $(this).data('supplier-id'); // <-- FIX 2: Syntax theek kar di
            $('#supplier_id_filter_hidden').val(supplierId);
            $('#filterForm').submit();
        });
        // ===== END OF NEW JS =====

        // Function to format numbers as currency
        function formatCurrency(number) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
        }

        // Function to format date as "d M, Y"
        function formatDate(dateString) {
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            return `${day} ${month}, ${year}`;
        }

        // Event delegation for supplier view button
        $(document).off('click', '.btn-view-supplier').on('click', '.btn-view-supplier', function () {
            var supplierId = $(this).data('supplier-id'); // <-- FIX 3: Syntax theek kar di
            var supplierName = $(this).data('supplier-name'); // <-- FIX 4: Syntax theek kar di
            var purchaseDate = $(this).data('purchase-date'); // <-- FIX 5: Syntax theek kar di

            var url = "{{ route('supplier.purchase.summary', ['supplier' => ':id']) }}";
            url = url.replace(':id', supplierId);

            // Reset modal
            $('#supplierSummaryModalLabel').text('Ledger Summary: ' + supplierName);
            $('#modal-loader').show();
            $('#modal-supplier-content').hide();
            $('#modal-error-message').hide();
            
            // Ensure DataTable is destroyed if it exists
            if ($.fn.DataTable.isDataTable('#supplier-summary-table')) {
                $('#supplier-summary-table').DataTable().destroy();
            }

            // Clear table body and footer
            $('#supplier-products-tbody').empty();
            $('#supplier-grand-total').text('');
            $('#supplier-grand-cash').text('');
            $('#supplier-grand-blance').text('');

            // AJAX request
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {
                    date: purchaseDate
                },
                success: function (response) {
                    if (response.status === 'success') {
                        var productsTbody = $('#supplier-products-tbody');
                        var counter = 1;
                        var cumulativeBalance = 0;
                        var sumTotalDebit = 0;  // Total of all purchases
                        var sumTotalCredit = 0; // Total of all payments

                        productsTbody.empty();

                        if (response.transactions && response.transactions.length > 0) {
                            response.transactions.forEach(function (item) {
                                
                                var debit_amount = parseFloat(item.debit) || 0;
                                var credit_amount = parseFloat(item.credit) || 0;
                                
                                cumulativeBalance += (debit_amount - credit_amount);

                                sumTotalDebit += debit_amount;
                                sumTotalCredit += credit_amount;

                                var row;
                                
                                if (item.type === 'Purchase') {
                                    row = `<tr>
                                        <td>${counter}</td>
                                        <td>${formatDate(item.purchase_date)}</td>
                                        <td>${item.product ? item.product.item_name : 'N/A'}</td>
                                        <td>${item.product ? item.product.item_code : 'N/A'}</td>
                                        <td class="text-end">${item.quantity || ''}</td>
                                        <td class="text-end">${formatCurrency(debit_amount)}</td>
                                        <td class="text-end">${formatCurrency(credit_amount)}</td>
                                        <td class="text-end" style="font-weight: bold;">${formatCurrency(cumulativeBalance)}</td>
                                    </tr>`;
                                } else {
                                    // 'Payment' row
                                    row = `<tr style="background-color: #f0fdf4;">
                                        <td>${counter}</td>
                                        <td>${formatDate(item.purchase_date)}</td>
                                        <td colspan="3" style="color: #15803d;"><strong>Payment</strong> <em>(${item.notes || 'N/A'})</em></td>
                                        <td class="text-end"></td>` + `<td class="text-end" style="color: #15803d;">${formatCurrency(credit_amount)}</td>` + `<td class="text-end" style="font-weight: bold;">${formatCurrency(cumulativeBalance)}</td>` + `</tr>`;
                                }
                                
                                productsTbody.append(row);
                                counter++;
                            });

                            // DataTable initialize karein
                            setTimeout(function () {
                                $('#supplier-summary-table').DataTable({
                                    dom: 'Bfrtip',
                                    buttons: [
                                        {
                                            extend: 'pdfHtml5',
                                            text: 'Download PDF',
                                            title: 'Supplier Ledger - ' + (response.supplier_name || supplierName),
                                            filename: function () {
                                                var name = (response.supplier_name || supplierName).replace(/[^a-z0-N9_-]/gi, '_');
                                                return 'supplier_ledger_' + name + '_' + (purchaseDate || new Date().toISOString().slice(0, 10));
                                            },
                                            orientation: 'landscape',
                                            pageSize: 'A4',
                                            exportOptions: { columns: ':visible' }
                                        }
                                    ],
                                    paging: false, searching: false, info: false, ordering: false
                                });
                            }, 50);

                            // Footer update karein
                            $('#supplier-grand-total').text(formatCurrency(sumTotalDebit));
                            $('#supplier-grand-cash').text(formatCurrency(sumTotalCredit));
                            $('#supplier-grand-blance').text(formatCurrency(cumulativeBalance));

                        } else {
                            // Empty state
                            var row = `<tr><td colspan="8" class="text-center">No transactions found on or before this date.</td></tr>`;
                            productsTbody.append(row);
                            $('#supplier-grand-total').text(formatCurrency(0));
                            $('#supplier-grand-cash').text(formatCurrency(0));
                            $('#supplier-grand-blance').text(formatCurrency(0));
                        }
                        
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