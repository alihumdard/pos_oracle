@extends('index') {{-- Or your main layout file --}}

@section('title', 'Edit Expense')

@section('content')
<style>
    /* You can share styles from other create forms or define specific ones */
    .form-container { background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 2rem; margin-top: 2rem; border: 1px solid #e9ecef; }
    .btn-primary-custom { background-color: #007bff; border-color: #007bff; color:white; } /* Example color */
    .btn-primary-custom:hover { background-color: #0069d9; border-color: #0062cc;}
    .text-danger-small { font-size: 0.875em; color: #dc3545; }
</style>

<div class="container-fluid pt-16 sm:pt-6">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="form-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0" style="color: #007bff;">Edit Expense</h3>
                     <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> View All Expenses
                    </a>
                </div>

                <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Include the form partial and pass the expense data --}}
                    @include('pages.expenses._form', ['expense' => $expense, 'categories' => $categories])

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary-custom px-5">
                            <i class="fas fa-save me-2"></i>Update Expense
                        </button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary px-4">Cancel</a>
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
    $('.select2-basic').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });

    // For Bootstrap 5 alert dismissal, if not globally handled
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
                    alertNode.style.display = 'none';
                }
            }
        });
    });
});
</script>
@endpush