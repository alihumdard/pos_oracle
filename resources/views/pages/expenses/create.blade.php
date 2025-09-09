@extends('index') {{-- Or your main layout file --}}

@section('title', 'Record New Expense') {{-- Changed title --}}

@section('content')
<style>
    .form-container { background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 2rem; margin-top: 2rem; border: 1px solid #e9ecef; }
    .btn-primary-custom { background-color: #007bff; border-color: #007bff; color:white; }
    .btn-primary-custom:hover { background-color: #0069d9; border-color: #0062cc;}
    .text-danger-small { font-size: 0.875em; color: #dc3545; }
</style>

<div class="max-w-4xl mx-auto px-4 pt-10">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-primary mb-3 sm:mb-0 flex items-center gap-2">
                <i class="fas fa-receipt text-primary"></i> Record New Expense
            </h3>
            <a href="{{ route('expenses.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg shadow-sm transition">
                <i class="fas fa-list mr-2"></i> View All Expenses
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Form partial --}}
            @include('pages.expenses._form', ['categories' => $categories])

            <!-- Buttons -->
            <div class="flex justify-center gap-3 pt-4 border-t">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium px-6 py-2 rounded-lg shadow-md transition">
                    <i class="fas fa-save"></i> Save Expense
                </button>
                <a href="{{ route('expenses.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2 rounded-lg shadow-md transition">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2-basic').select2({ // Ensure this class matches the one in _form.blade.php
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });

    // For Bootstrap 5 alert dismissal
    var alertList = document.querySelectorAll('.alert .btn-close');
    alertList.forEach(function (element) {
        element.addEventListener('click', function () {
            var alertNode = this.closest('.alert');
            if (alertNode) {
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