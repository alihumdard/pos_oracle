@extends('index')

@section('title', 'Expense Report')

@section('content')
<style>
.card-header-custom {
    background-color: #dc3545;
    color: white;
}

/* Danger color for expenses */
.table th {
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
}

.filter-form .form-control-sm,
.filter-form .select2-container--default .select2-selection--single {
    height: calc(1.5em + .5rem + 2px) !important;
    padding: .25rem .5rem !important;
    font-size: .875rem !important;
}

.filter-form .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.5 !important;
}

.filter-form .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: calc(1.5em + .5rem) !important;
}
</style>

<div class="container-fluid pt-10">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detailed Expense Report</h1>
        <a href="{{ route('expenses.create') }}" class="btn btn-sm bg-gradient-primary text-white shadow-sm">
            <i class="fas fa-plus fa-sm me-1"></i> Add New Expense
        </a>
    </div>

    <div class="card shadow-lg border-0 mb-4">
        <!-- Card Header -->
        <div class="card-header bg-gradient-primary text-white py-3">
            <h6 class="m-0 fw-bold"><i class="fas fa-filter me-2"></i>Filter Expenses</h6>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <form action="{{ route('reports.expenses') }}" method="GET">
                <div class="row g-4">
                    <!-- From Date -->
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-semibold">From Date</label>
                        <input type="date" name="start_date" id="start_date"
                            class="form-control form-control-sm shadow-sm"
                            value="{{ $startDate ?? request('start_date') }}">
                    </div>

                    <!-- To Date -->
                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-semibold">To Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm shadow-sm"
                            value="{{ $endDate ?? request('end_date') }}">
                    </div>

                    <!-- Category -->
                    <div class="col-md-3">
                        <label for="expense_category_id" class="form-label fw-semibold">Category</label>
                        <select name="expense_category_id" id="expense_category_id"
                            class="form-control form-control-sm shadow-sm select2-filter" style="width:100%;">
                            <option value="">All Categories</option>
                            @foreach($expenseCategories as $category)
                            <option value="{{ $category->id }}"
                                {{ (isset($categoryId) && $categoryId == $category->id) || old('expense_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Term -->
                    <div class="col-md-3">
                        <label for="search_term" class="form-label fw-semibold">Search Term</label>
                        <input type="text" name="search_term" id="search_term"
                            class="form-control form-control-sm shadow-sm" placeholder="Description, Paid to, Ref..."
                            value="{{ $searchTerm ?? request('search_term') }}">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary btn-sm shadow-sm me-2">
                            <i class="fas fa-filter me-1"></i> Apply Filter
                        </button>
                        <a href="{{ route('reports.expenses') }}" class="btn btn-secondary btn-sm shadow-sm">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-gradient-primary">
            <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-receipt me-2"></i>Expenses (Total for period:
                {{ number_format($totalExpensesForPeriod, 2) }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="example1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                            <th>Paid To</th>
                            <th>Reference #</th>
                            <th>Recorded By</th>
                            {{-- Add actions if you want edit/delete links here too --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date->format('d M, Y') }}</td>
                            <td>{{ $expense->category->name ?? 'N/A' }}</td>
                            <td>{{ $expense->description }}</td>
                            <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->paid_to ?? '-' }}</td>
                            <td>{{ $expense->reference_number ?? '-' }}</td>
                            <td>{{ $expense->user->name ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No expenses found for the selected criteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $expenses->appends(request()->query())->links() }}
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
});
</script>
<script>
    $(document).ready(function() {
        $('#example1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
            ],
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            responsive: true,
            "columnDefs": [{
                    "orderable": false,
                    "targets": 3
                } // Disable sorting on 'Actions' column
            ]
        });
    });
</script>
@endpush