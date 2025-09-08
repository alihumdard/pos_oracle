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

.customer-filter-container select,
.customer-filter-container input[type="checkbox"],
.customer-filter-container button {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.9rem;
    transition: var(--transition);
}

.customer-filter-container select {
    cursor: pointer;
    min-width: 150px;
    flex-grow: 1;
}

.customer-filter-container .checkbox-group {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.customer-filter-container .checkbox-group label {
    display: flex;
    align-items: center;
    margin-bottom: 0;
    font-size: 0.9rem;
}

.customer-filter-container input[type="checkbox"] {
    transform: scale(1.0);
    margin-right: 8px;
    accent-color: var(--primary-color);
}

.customer-filter-container button {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
    color: white;
    cursor: pointer;
    border: none;
    padding: 8px 20px;
    font-weight: 500;
    border-radius: var(--border-radius);
    transition: transform 0.2s ease-in-out, background 0.3s ease-in-out;
    text-align: center;
    min-width: 120px;
    flex-grow: 0;
}

.customer-filter-container button:hover {
    background: linear-gradient(to right, var(--primary-hover), #004494);
    transform: translateY(-2px);
}

.customer-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
}

.customer-card .card-header {
    background-color: white;
    border-bottom: 1px solid var(--light-gray);
    padding: 1rem 1.5rem;
}

.customer-card .card-title {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0;
}

.customer-card .card-body {
    padding: 0;
}

.customer-card .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.customer-card .table {
    width: 100%;
    margin-bottom: 0;
}

.customer-card .table th,
.customer-card .table td {
    padding: 1rem;
    vertical-align: middle;
    border-top: 1px solid var(--light-gray);
    white-space: nowrap;
    min-width: 120px;
}

.customer-card .table thead th {
    background-color: var(--primary-color);
    color: white;
    font-weight: 600;
    border-bottom: none;
}

.customer-card .table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.customer-card .action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.customer-card .action-buttons .btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
}

.customer-card td[style*="max-width"] {
    white-space: normal !important;
    word-break: break-word;
    overflow-wrap: break-word;
}

.modal-content {
    border-radius: var(--border-radius);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
    border-bottom: 1px solid var(--light-gray);
    padding: 1.25rem 1.5rem;
    background-color: var(--primary-color);
    color: white;
    border-top-left-radius: calc(var(--border-radius) - 1px);
    border-top-right-radius: calc(var(--border-radius) - 1px);
}

.modal-title {
    font-weight: 600;
}

.modal-header .close,
.modal-header .btn-close {
    color: white;
    opacity: 0.9;
}

.modal-body {
    padding: 1.5rem;
}

.modal-body .form-group {
    margin-bottom: 1rem;
}

.modal-body .form-control {
    height: 45px;
    border-radius: 6px;
    border: 1px solid #ced4da;
}

.modal-body .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.modal-body .text-danger {
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

.modal-footer {
    border-top: 1px solid var(--light-gray);
    padding: 1rem 1.5rem;
}

@media (max-width: 768px) {
    .customer-card .card-header {
        flex-direction: column;
        align-items: flex-start !important;
        padding: 1rem;
    }

    .customer-card .card-header .btn {
        width: 100%;
        margin-top: 10px;
    }

    .customer-filter-container {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
        padding: 15px;
    }

    .customer-filter-container select,
    .customer-filter-container button {
        width: 100%;
        min-width: unset;
    }

    .customer-filter-container .checkbox-group {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .customer-filter-container .checkbox-group label {
        width: 100%;
    }
}
</style>

@section('content')
<div class="flex justify-end py-10">
    <button onclick="history.back()"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-400 to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Back
    </button>
</div>
<div class="modal fade" id="addCutomerModal" tabindex="-1" role="dialog" aria-labelledby="addCutomerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCutomerModalLabel">Add New Customer</h5>
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
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn" data-action="add">Save
                            Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="customer-filter-container mb-4">
                <form action="{{ route('customer.filter') }}" method="GET" class="filter-form w-100">
                    @csrf
                    <label class="mb-2">Sort By:</label>
                    <select name="sort_order" class="form-control">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Low to High</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>High to Low
                        </option>
                    </select>
                    <div class="checkbox-group d-flex gap-3 my-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filter_debit" id="filter_debit"
                                {{ request('filter_debit') ? 'checked' : '' }}>
                            <label class="form-check-label ml-2" for="filter_debit">
                                Debit
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="filter_credit" id="filter_credit"
                                {{ request('filter_credit') ? 'checked' : '' }}>
                            <label class="form-check-label ml-2" for="filter_credit">
                                Credit
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hide_zero_balance"
                                id="hide_zero_balance" {{ request('hide_zero_balance') ? 'checked' : '' }}>
                            <label class="form-check-label ml-2" for="hide_zero_balance">
                                Hide Zero Balance
                            </label>
                        </div>
                    </div>

                    <a type="submit" class="ms-auto px-4 py-2 rounded-lg text-white font-medium shadow-md 
                        bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-500 hover:to-blue-700 transition">
                        Apply Filter
                    </a>

                </form>
            </div>

            <div class="card customer-card shadow-lg border-0 rounded-3 overflow-hidden">
                <!-- Header -->
                <div class="card-header 
                bg-gradient text-white p-3" style="background: linear-gradient(to right, #2563eb, #1d4ed8);">

                    <div class="flex justify-between flex-wrap gap-2">

                        <h3 class="card-title mb-2 mb-md-0 fw-bold">
                            <i class="fa fa-users me-2"></i> Customer Detail
                        </h3>
                        <!-- Search -->
                        <div class="position-relative" style="max-width: 220px;">
                            <i
                                class="fa fa-search position-absolute top-50 start-0 translate-middle-y text-muted ms-2"></i>
                            <input type="text" class="form-control form-control-sm pl-10"
                                placeholder="Search customer...">
                        </div>
                        
                        <!-- Add Customer -->
                        <button type="button" class="btn btn-light d-flex align-items-center gap-2" id="addCustomerBtn">
                            <i class="fa fa-plus"></i> Add Customer
                        </button>

                        <!-- Export Buttons -->
                        <!-- <button type="button" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1">
                            <i class="fa fa-file-excel"></i> Excel
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1">
                            <i class="fa fa-file-pdf"></i> PDF
                        </button> -->

                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-0">
                    <div class="table-responsive p-3">
                        <table id="example1" class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#Sr.No</th>
                                    <th>Customer Name</th>
                                    <th>Mobile Number</th>
                                    <th>Address</th>
                                    <th>CNIC</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableHolder">
                                @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold text-primary">{{ $customer->name ?? '' }}</td>
                                    <td>{{ $customer->mobile_number ?? ''}}</td>
                                    <td style="max-width: 200px; white-space: normal; word-break: break-word;">
                                        {{ $customer->address ?? '' }}
                                    </td>
                                    <td>{{ $customer->cnic ?? '' }}</td>
                                    <td class="text-danger fw-semibold">{{ $customer->debit ?? '' }}</td>
                                    <td class="text-success fw-semibold">{{ $customer->credit ?? '' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('customer.view', ['id' => $customer->id]) }}"
                                                class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <a href="" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-danger" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this customer?')">
                                                <i class="fa fa-trash"></i> Delete
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

        </div>
    </div>
</div>
@stop

@pushOnce('scripts')
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
@endPushOnce