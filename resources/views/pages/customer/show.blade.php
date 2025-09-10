@extends('index')

@section('title', 'Customer Management')

@section('content')
<style>
:root {
    --primary-color: #007bff;
    --primary-hover: #0056b3;
    --secondary-color: #f8f9fa;
    --text-color: #343a40;
    --light-gray: #e9ecef;
    --border-radius: 8px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

/* 🔹 Filter Bar */
.customer-filter-container {
    border: 1px solid var(--light-gray);
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

/* 🔹 Card */
.customer-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    max-width: 1400px;
    margin: 0 auto;
    /* Center align on big screens */
}

.customer-card .card-header {
    background: linear-gradient(to right, #2563eb, #1d4ed8);
    border-bottom: 1px solid var(--light-gray);
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

.customer-card .card-title {
    font-weight: 600;
    color: white;
    margin-bottom: 0;
}

/* 🔹 Table */
.customer-card .table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
}

.customer-card .table thead th {
    background-color: var(--primary-color);
    color: white;
    font-weight: 600;
    border-bottom: none;
    padding: 1rem;
    white-space: nowrap;
}

.customer-card .table td {
    padding: 0.9rem;
    vertical-align: middle;
    border-top: 1px solid var(--light-gray);
    min-width: 120px;
    white-space: nowrap;
}

.customer-card .table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* 🔹 Action buttons */
.customer-card .action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}

.customer-card .action-buttons .btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
}

/* 🔹 Responsive */
@media (max-width: 768px) {
    .customer-filter-container {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
        padding: 15px;
    }

    .customer-filter-container select,
    .customer-filter-container button {
        width: 100%;
    }

    .customer-card .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .customer-card .card-header .btn {
        width: 100%;
    }
}
</style>

<!-- Back Button -->
<div class="flex justify-end py-10 pr-3">
    <button onclick="history.back()" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white 
        bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-500 hover:to-blue-700 
        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Back
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="customerForm">
                    <div class="row">
                        <div class="form-group col-md-6 col-12">
                            <label for="name">Customer Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                            <small class="text-danger d-none" id="nameError">Name is required.</small>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="cnic">CNIC</label>
                            <input type="text" name="cnic" class="form-control" id="cnic" placeholder="Enter the CNIC">
                            <small class="text-danger d-none" id="cnicError">CNIC is required.</small>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="mobile_no">Mobile No</label>
                            <input type="text" name="mobile_no" class="form-control" id="mobile_no"
                                placeholder="Enter Mobile No">
                            <small class="text-danger d-none" id="mobileNoError">Mobile number is required.</small>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" id="address"
                                placeholder="Enter Address">
                            <small class="text-danger d-none" id="addressError">Address is required.</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn" data-action="add">
                            Save Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <div class="customer-filter-container">
        <form action="{{ route('customer.filter') }}" method="GET" class="w-100">
            @csrf
            <label class="fw-semibold">Sort By:</label>
            <select name="sort_order" class="form-control">
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Low to High</option>
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>High to Low</option>
            </select>

            <div class="checkbox-group d-flex gap-3 my-2">
                <label><input type="checkbox" name="filter_debit" {{ request('filter_debit') ? 'checked' : '' }}>
                    Debit</label>
                <label><input type="checkbox" name="filter_credit" {{ request('filter_credit') ? 'checked' : '' }}>
                    Credit</label>
                <label><input type="checkbox" name="hide_zero_balance"
                        {{ request('hide_zero_balance') ? 'checked' : '' }}> Hide Zero Balance</label>
            </div>

            <button type="submit" class="btn btn-primary">Apply Filter</button>
        </form>
    </div>

    <!-- Customer Card -->
    <div class=" mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4 p-4 border-b border-gray-200">
            <!-- Left: Title -->
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa fa-users text-blue-600"></i> Customer Detail
            </h3>

            <!-- Right: Search + Add -->
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <!-- Search -->
                <div class="relative w-full md:w-56">
                    <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Search customer..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                <!-- Add Customer -->
                <button type="button"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-blue-700 transition"
                    data-toggle="modal" data-target="#addCustomerModal">
                    <i class="fa fa-plus"></i> Add Customer
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="p-4 overflow-x-auto">
            <table id="example1" class="w-full text-sm text-left border-collapse">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-2">#Sr.No</th>
                        <th class=" py-2">Customer Name</th>
                        <th class="px-4 py-2">Mobile Number</th>
                        <th class="px-4 py-2">Address</th>
                        <th class="px-4 py-2">CNIC</th>
                        <th class="px-4 py-2">Debit</th>
                        <th class="px-4 py-2">Credit</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-center">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 font-medium text-blue-600">{{ $customer->name ?? '' }}</td>
                        <td class="px-4 py-2">{{ $customer->mobile_number ?? '' }}</td>
                        <td class="px-4 py-2 max-w-xs whitespace-normal break-words">
                            {{ $customer->address ?? '' }}
                        </td>
                        <td class="px-4 py-2">{{ $customer->cnic ?? '' }}</td>
                        <td class="px-4 py-2 text-red-600 font-semibold text-right">{{ $customer->debit ?? '' }}</td>
                        <td class="px-4 py-2 text-green-600 font-semibold text-right">{{ $customer->credit ?? '' }}</td>
                        <td class="px-4 py-2 ">
                            <div class="flex justify-center gap-1 ">
                                <a href="{{ route('customer.view', ['id' => $customer->id]) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1 border border-blue-500 text-blue-600 rounded-md text-xs hover:bg-blue-50">
                                    <i class="fa fa-eye"></i>
                                    <span>View</span>
                                </a>

                                <a href="#"
                                    class="inline-flex items-center gap-1 px-3 py-1 border border-yellow-500 text-yellow-600 rounded-md text-xs hover:bg-yellow-50">
                                    <i class="fa fa-edit"></i>
                                    <span>Edit</span>
                                </a>

                                <a href="#" onclick="return confirm('Are you sure you want to delete this customer?')"
                                    class="inline-flex items-center gap-1 px-3 py-1 border border-red-500 text-red-600 rounded-md text-xs hover:bg-red-50">
                                    <i class="fa fa-trash"></i>
                                    <span>Delete</span>
                                </a>

                            </div>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@stop

@pushOnce('scripts')
<script>
$(document).ready(function() {
   $('#example1').DataTable({
        dom: '<"dt-toolbar overflow-x-auto"Bfrtip>', // ✅ Toolbar ko scrollable banaya
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ],
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        columnDefs: [{
            orderable: false,
            targets: 6
        }],
        initComplete: function() {
            // Flex row force + spacing
            $('.dt-buttons').addClass('flex flex-nowrap gap-2 w-[40%] ');
            $('.dt-buttons button').addClass(
                'bg-blue-600 text-white px-3 py-1 rounded-md shadow hover:bg-blue-700 transition'
                );
        }
    });
});
</script>
@endPushOnce