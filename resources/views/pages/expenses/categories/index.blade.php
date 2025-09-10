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

<div class="max-w-7xl mx-auto px-4 pt-10">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-tags text-blue-600"></i> Expense Categories
        </h1>
        <a href="{{ route('expense_categories.create') }}"
            class="inline-flex items-center px-4 py-2 mt-10 sm:mt-0 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">
            <i class="fas fa-plus mr-2"></i> Add New Category
        </a>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="mb-4 flex items-center justify-between p-3 bg-green-100 text-green-800 rounded-lg shadow">
            <span>{{ session('success') }}</span>
            <button type="button" class="text-green-800 hover:text-green-900" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 flex items-center justify-between p-3 bg-red-100 text-red-800 rounded-lg shadow">
            <span>{{ session('error') }}</span>
            <button type="button" class="text-red-800 hover:text-red-900" data-bs-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Card -->
    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <!-- Card Header -->
        <div class="flex flex-col bg-blue-600 sm:flex-row items-start sm:items-center justify-between px-5 py-3 border-b ">
            <h6 class="font-semibold  text-white text-lg">All Categories</h6>
            <form action="{{ route('expense_categories.index') }}" method="GET" class="mt-3 sm:mt-0">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Search categories..."
                        value="{{ request('search') }}"
                        class="w-48 border border-gray-300 rounded-lg p-2 text-sm ">
                    <button type="submit"
                        class="bg-white text-black text-sm px-3 py-2 rounded-lg shadow transition">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-left">No. of Expenses</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $index => $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $categories->firstItem() + $index }}</td>
                            <td class="px-4 py-2 font-medium text-gray-800">{{ $category->name }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $category->description ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $category->expenses_count ?? $category->expenses->count() }}</td>
                            <td class="px-4 py-2 text-center flex justify-center gap-2">
                                <a href="{{ route('expense_categories.edit', $category->id) }}"
                                    class="inline-flex items-center px-2 py-1 text-sm text-blue-600 hover:text-blue-800 rounded transition"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('expense_categories.destroy', $category->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this category? This might affect existing expenses if not handled carefully.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-2 py-1 text-sm text-red-600 hover:text-red-800 rounded transition"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-600">
                                No expense categories found.
                                <a href="{{ route('expense_categories.create') }}" class="text-purple-600 hover:underline">
                                    Add one now!
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t">
            {{ $categories->appends(request()->query())->links() }}
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