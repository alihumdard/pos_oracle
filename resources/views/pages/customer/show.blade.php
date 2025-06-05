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
    .modal-header .close, .modal-header .btn-close {
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

<div class="modal fade" id="addCutomerModal" tabindex="-1" role="dialog" aria-labelledby="addCutomerModalLabel" aria-hidden="true">
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
                            <input type="text" name="mobile_no" class="form-control" id="mobile_no" placeholder="Enter Mobile No">
                            <small class="text-danger d-none" id="mobileNoError">Mobile number is required.</small>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" id="address" placeholder="Enter Address">
                            <small class="text-danger d-none" id="addressError">Address is required.</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn" data-action="add">Save Customer</button>
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
                    <label class="mb-0 me-2">Sort By:</label>
                    <select name="sort_order" class="form-control form-control-sm">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Low to High</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>High to Low</option>
                    </select>
                    <div class="checkbox-group ms-3">
                        <label><input type="checkbox" name="filter_debit" {{ request('filter_debit') ? 'checked' : '' }}> Debit</label>
                        <label><input type="checkbox" name="filter_credit" {{ request('filter_credit') ? 'checked' : '' }}> Credit</label>
                        <label><input type="checkbox" name="hide_zero_balance" {{ request('hide_zero_balance') ? 'checked' : '' }}> Hide Zero Balance</label>
                    </div>
                    <button type="submit" class="ms-auto">Apply Filter</button>
                </form>
            </div>

            <div class="card customer-card" style="padding:10px;">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-2 mb-md-0">
                        <h3 class="card-title">Customer Detail</h3>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-primary-custom" id="addCustomerBtn">
                            <i class="fa fa-plus mr-2"></i>Add Customer
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover w-100" id="example1">
                            <thead>
                                <tr>
                                    <th>#Sr.No</th>
                                    <th>Customer Name</th>
                                    <th>Mobile Number</th>
                                    <th>Address</th>
                                    <th>CNIC</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableHolder">
                                @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $customer->name ?? '' }}</td>
                                    <td>{{ $customer->mobile_number ?? ''}}</td>
                                    <td style="max-width: 200px; white-space: normal; word-break: break-word; overflow-wrap: break-word;">
                                        {{ $customer->address ?? '' }}
                                    </td>
                                    <td>{{ $customer->cnic ?? '' }}</td>
                                    <td>{{ $customer->debit ?? '' }}</td>
                                    <td>{{ $customer->credit ?? '' }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('customer.view', ['id' => $customer->id]) }}" class="btn btn-sm btn-info" title="View Details">
                                                <i class="fa fa-eye"></i> View
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
    function initDataTable() {
        if ($('#example1').length) {
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().clear().destroy();
            }
            $('#example1').DataTable({
                responsive: false, 
                lengthChange: true,
                autoWidth: false, 
                scrollY: false, 
                scrollX: false, 
                buttons: ["excel", "pdf"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        }
    }
    initDataTable();

    $('#addCustomerBtn').click(function() {
        $('#addCutomerModalLabel').text('Add New Customer');
        $('#customerForm')[0].reset();
        $('.text-danger').addClass('d-none');
        $('#submitBtn').text('Save Customer').data('action', 'add').removeData('id');
        $('#addCutomerModal').modal('show');
    });

    $('#customerForm').submit(function(e) {
        e.preventDefault();
        const actionType = $('#submitBtn').data('action');
        const customerId = $('#submitBtn').data('id');
        let url = '{{ route("add.customers") }}';
        let methodType = 'POST';

        if (actionType === 'edit' && customerId) { 
            url = '/customers/' + customerId; 
            methodType = 'PUT'; 
        }

        const formData = {
            _token: '{{ csrf_token() }}',
            name: $('#name').val().trim(),
            mobile_no: $('#mobile_no').val().trim(),
            address: $('#address').val().trim(),
            cnic: $('#cnic').val().trim(),
        };
        if(methodType === 'PUT') {
            formData._method = 'PUT';
        }

        let hasError = false;
        $('.text-danger').addClass('d-none');
        
        function getErrorElementId(key) {
            if (key === 'mobile_no') return 'mobileNoError';
            return key + 'Error';
        }

        Object.keys(formData).forEach(function(key) {
            if (key === '_token' || key === '_method') return;
            const value = formData[key];
            const errorElementId = getErrorElementId(key);
            const errorElement = $('#' + errorElementId);
            
            if (!value) {
                if (errorElement.length) {
                    errorElement.removeClass('d-none');
                }
                hasError = true;
            }
        });

        if (hasError) return;

        $.ajax({
            url: url,
            type: methodType,
            data: formData,
            success: function(response) {
                Swal.fire({
                    title: "Success!",
                    text: (response.message || (actionType === 'add' ? 'Customer added successfully!' : 'Customer updated successfully!')),
                    icon: "success",
                });
                $('#addCutomerModal').modal('hide');
                $('#customerForm')[0].reset();
                refreshTable();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        const errorElementId = getErrorElementId(key);
                        const errorElement = $('#' + errorElementId);
                         if (errorElement.length) {
                            errorElement.text(errors[key][0]).removeClass('d-none');
                        }
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: "Please correct the highlighted fields.",
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong! Please try again.",
                    });
                }
            },
        });
    });

    function refreshTable() {
        $('#tableHolder').load("{{ route('show.customers') }} #tableHolder > *", function() {
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }
            initDataTable();
        });
    }
});
</script>
@endPushOnce