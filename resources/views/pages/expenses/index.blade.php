@extends('index') {{-- Or your main layout file --}}

@section('title', 'Manage Expenses')

@section('content')
<style>
    .card-header-custom { background-color: #007bff; color: white; } /* Example color */
    .btn-action-edit { color: #17a2b8; } /* Info color */
    .btn-action-delete { color: #dc3545; } /* Danger color */
    .filter-form .form-control-sm, .filter-form .select2-container--default .select2-selection--single { height: calc(1.5em + .5rem + 2px) !important; padding: .25rem .5rem !important; font-size: .875rem !important;}
    .filter-form .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 1.5 !important; }
    .filter-form .select2-container--default .select2-selection--single .select2-selection__arrow { height: calc(1.5em + .5rem) !important;}
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Expenses</h1>
        <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Expense
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
     @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Form --}}
    <div class="card shadow mb-4">
        <div class="card-body filter-form">
            <form action="{{ route('expenses.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">From Date:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">To Date:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="category_id" class="form-label">Category:</label>
                        <select name="category_id" id="category_id" class="form-control form-control-sm select2-filter" style="width:100%;">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search Term:</label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Desc, Paid to, Ref..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-12 text-end mt-2">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter fa-sm"></i> Filter</button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times fa-sm"></i> Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header py-3 card-header-custom d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">Expenses List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="expensesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                            <th>Paid To</th>
                            <th>Reference #</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
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
                            <td>
                                <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-action-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No expenses found for the selected criteria.</td>
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
     // Bootstrap 5 alert dismissal
    var alertList = document.querySelectorAll('.alert');
    alertList.forEach(function (alert) {
        new bootstrap.Alert(alert);
    });
});
</script>
@endpush