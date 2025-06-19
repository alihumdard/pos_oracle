@extends('index') {{-- Or your main layout file --}}

@section('title', 'Expense Categories')

@section('content')
<style>
    .card-header-custom { background-color: #6f42c1; color: white; } /* Example purple for categories */
    .btn-action-edit { color: #17a2b8; } /* Info color */
    .btn-action-delete { color: #dc3545; } /* Danger color */
    .table th { white-space: nowrap; }
    .table td { vertical-align: middle; }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Expense Categories</h1>
        <a href="{{ route('expense_categories.create') }}" class="btn btn-sm btn-primary shadow-sm" style="background-color: #6f42c1; border-color: #6f42c1;">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Category
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

    <div class="card shadow mb-4">
        <div class="card-header py-3 card-header-custom d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">All Categories</h6>
            <form action="{{ route('expense_categories.index') }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Search categories..." value="{{ request('search') }}">
                    <button class="btn btn-light btn-sm" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="categoriesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>No. of Expenses</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $index => $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $index }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description ?? '-' }}</td>
                            <td>{{ $category->expenses_count ?? $category->expenses->count() }}</td> {{-- Load count if available or count relation --}}
                            <td>
                                <a href="{{ route('expense_categories.edit', $category->id) }}" class="btn btn-sm btn-action-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('expense_categories.destroy', $category->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this category? This might affect existing expenses if not handled carefully.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No expense categories found. <a href="{{ route('expense_categories.create') }}">Add one now!</a></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // For Bootstrap 5 alert dismissal
    var alertList = document.querySelectorAll('.alert .btn-close');
    alertList.forEach(function (element) {
        element.addEventListener('click', function () {
            var alertNode = this.closest('.alert');
            if (alertNode) {
                // Ensure Bootstrap's Alert component is available
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    var alertInstance = bootstrap.Alert.getInstance(alertNode);
                    if (!alertInstance) {
                        alertInstance = new bootstrap.Alert(alertNode);
                    }
                    alertInstance.close();
                } else {
                    // Fallback for older Bootstrap or if JS not loaded properly
                    alertNode.style.display = 'none';
                }
            }
        });
    });
});
</script>
@endpush