@extends('index') {{-- Or your main layout file --}}

@section('title', 'Create Expense Category')

@section('content')
<style>
    /* You can share styles from other create forms or define specific ones */
    .form-container { background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 2rem; margin-top: 2rem; border: 1px solid #e9ecef; }
    .btn-primary-custom { background-color: #6f42c1; border-color: #6f42c1; color:white; } /* Example purple for categories */
    .btn-primary-custom:hover { background-color: #5a32a3; border-color: #5a32a3; }
    .text-danger-small { font-size: 0.875em; color: #dc3545; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7 col-md-9 mx-auto">
            <div class="form-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0" style="color: #6f42c1;">Create New Expense Category</h3>
                    <a href="{{ route('expense_categories.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> View All Categories
                    </a>
                </div>

                <form action="{{ route('expense_categories.store') }}" method="POST">
                    @csrf
                    @include('pages.expenses.categories._form') {{-- Include the form partial --}}

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary-custom px-5">
                            <i class="fas fa-save me-2"></i>Save Category
                        </button>
                        <a href="{{ route('expense_categories.index') }}" class="btn btn-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // For Bootstrap 5 alert dismissal, if not globally handled
    var alertList = document.querySelectorAll('.alert .btn-close');
    alertList.forEach(function (element) {
        element.addEventListener('click', function () {
            var alertNode = this.closest('.alert');
            if (alertNode) {
                var alert = new bootstrap.Alert(alertNode); // Ensure bootstrap is loaded
                alert.close();
            }
        });
    });
});
</script>
@endpush