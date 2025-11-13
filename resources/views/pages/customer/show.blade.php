@extends('index')


@section('content')
    <style>
        /* Root Variables */
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

        /* General Body Styling - CRITICAL: REMOVED CENTERING STYLES */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            /* Removed: display: flex; justify-content: center; align-items: center; height: 100vh; */
            margin: 0;

            padding-bottom: 20px;
            /* Add some bottom padding if needed */
            /* Ensure no overflow-x: hidden here */
            overflow-x: auto;
            /* Allow body to scroll if content somehow pushes it */
        }

        /* Container for the filter form, adjusted for responsiveness */
        .filter-container-wrapper {
            display: flex;
            justify-content: center;
            /* Center the form within its column */
            width: 100%;
            /* Ensure it takes full width of its parent column */
        }

        /* Filter Form Styles */
        form.filter-form {
            border: 1px solid var(--light-gray);
            background: white;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
        }

        form.filter-form select,
        form.filter-form input[type="checkbox"],
         {
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: var(--transition);
            /* flex-grow: 1; */
        }
         form.filter-form button {
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: var(--transition);
            flex-grow: 1;
        }

        form.filter-form select {
            cursor: pointer;
            min-width: 150px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            margin-bottom: 0;
        }

        input[type="checkbox"] {
            transform: scale(1.1);
            margin-right: 8px;
            accent-color: var(--primary-color);
            min-width: unset;
        }

        form.filter-form button {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: var(--border-radius);
            transition: transform 0.2s ease-in-out, background 0.3s ease-in-out;
            text-align: center;
            min-width: 120px;
        }

        form.filter-form button:hover {
            background: linear-gradient(to right, var(--primary-hover), #004494);
            transform: translateY(-2px);
        }

        /* Card and Table Styles */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-top: 20px;
            overflow: hidden;
            /* This is fine here to contain the card content */
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Table Responsive Wrapper - CRITICAL FOR HORIZONTAL SCROLLING */
        .table-responsive {
            overflow-x: auto;
            /* THIS IS THE KEY */
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling on iOS */
            padding-bottom: 10px;
            /* Add some padding at the bottom of the scrollable area */
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            /* Do NOT set white-space: nowrap here on the table itself,
                   apply it to th/td if you want to force single line content */
        }

        .table th,
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--light-gray);
            white-space: nowrap;
            /* Forces content into a single line, causing overflow */
            min-width: 120px;
            /* Ensures columns are wide enough to trigger scroll */
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
            white-space: nowrap;
            /* Keep headers on one line */
        }

        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        /* Action Buttons within table */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 1px solid var(--light-gray);
            padding: 1.25rem 1.5rem;
            background-color: var(--primary-color);
            color: white;
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-header .close {
            color: white;
            opacity: 0.8;
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

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
                padding: 1rem;
            }

            .card-header .btn {
                width: 100%;
                margin-top: 10px;
            }

            /* Adjust modal dialog for smaller screens */
            .modal-dialog {
                margin: 1rem;
            }

            .modal-body .row>.form-group {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            /* Adjust filter form for smaller screens */
            form.filter-form {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 15px;
            }

            form.filter-form select,
            form.filter-form button {
                width: 100%;
                min-width: unset;
            }

            .checkbox-group {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .checkbox-group label {
                width: 100%;
            }

            .col-12.text-right {
                text-align: left !important;
                margin-top: 15px;
            }

            .col-12.text-right .btn {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .table-responsive thead th {
                white-space: nowrap;
            }
        }
    </style>


    <div class="row pt-16 sm:pt-6">
        <div class="col-12 justify-content-end align-items-center d-flex">
            <button class="btn btn-primary" onclick="history.back()">← Back</button>
        </div>
    </div>

    <div class="modal fade" id="addCutomerModal" tabindex="-1" role="dialog" aria-labelledby="addCutomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCutomerModalLabel">Add New Supplier</h5>
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
                <div class="card">
                    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="mb-2 mb-md-0">
                            <h3 class="card-title">Customer Detail</h3>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-primary" id="addCustomerBtn">
                                <i class="fa fa-plus mr-2"></i>Add Customer
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="filter-container-wrapper mb-4">
                            <form action="{{ route('customer.filter') }}" method="GET" class="filter-form">
                                @csrf
                                <label class="mb-0">Sort By:</label>
                                <select name="sort_order" class="form-control">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Low to High
                                    </option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>High to Low
                                    </option>
                                </select>
                                <div
                                    class="checkbox-group flex flex-col md:flex-row md:flex-wrap md:items-center md:gap-4 gap-2">
                                    <label
                                        class="flex items-center justify-between md:justify-start w-full md:w-auto text-gray-700">
                                        <span>Debit</span>
                                        <input type="checkbox" name="filter_debit" {{ request('filter_debit') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 order-last md:order-first md:ml-2 ml-1">
                                    </label>

                                    <label
                                        class="flex items-center justify-between md:justify-start w-full md:w-auto text-gray-700">
                                        <span>Credit</span>
                                        <input type="checkbox" name="filter_credit" {{ request('filter_credit') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 order-last md:order-first md:ml-2 ml-1">
                                    </label>

                                    <label
                                        class="flex items-center justify-between md:justify-start w-full md:w-auto text-gray-700">
                                        <span>Hide Zero Balance</span>
                                        <input type="checkbox" name="hide_zero_balance" {{ request('hide_zero_balance') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 order-last md:order-first md:ml-2 ml-1">
                                    </label>
                                </div>

                                <button type="submit">Apply Filter</button>
                            </form>
                        </div>

                        {{-- DataTables Table --}}
                        <table class="table table-hover w-100" id="example1">
                            <thead class="bg-primary text-white">
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
                                        <td
                                            style="max-width: 150px; white-space: normal; word-break: break-word; overflow-wrap: break-word;">
                                            {{ $customer->address ?? '' }}
                                        </td>
                                        <td>{{ $customer->cnic ?? '' }}</td>
                                        <td>{{ $customer->debit ?? '' }}</td>
                                        <td>{{ $customer->credit ?? '' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('customer.view', ['id' => $customer->id]) }}"
                                                    class="btn btn-sm btn-info" title="View Details">
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

@endsection

@pushOnce('scripts')
    <script>
        $(document).ready(function () {
            // Initializing DataTable
            initDataTable();

            // Open modal for adding a new customer
            $('#addCustomerBtn').click(function () {
                $('#addCutomerModalLabel').text('Add New Customer');
                $('#customerForm')[0].reset(); // Reset form fields
                $('.text-danger').addClass('d-none'); // Hide all validation errors
                $('#submitBtn').text('Save Customer').data('action', 'add');
                $('#addCutomerModal').modal('show');
            });

            // Form submit handler for add/edit
            $('#customerForm').submit(function (e) {
                e.preventDefault();

                const actionType = $('#submitBtn').data('action');
                const customerId = $('#submitBtn').data('id');
                const url = actionType === 'add' ? '{{ route("add.customers") }}' : '/customers/' + customerId;

                const formData = {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val().trim(),
                    mobile_no: $('#mobile_no').val().trim(),
                    address: $('#address').val().trim(),
                    cnic: $('#cnic').val().trim(),
                };

                // Frontend validation
                let hasError = false;
                $('.text-danger').addClass('d-none'); // Hide previous errors

                function getErrorElementId(key) {
                    return key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase()) + 'Error';
                }

                // Validate each field
                Object.keys(formData).forEach(function (key) {
                    const value = formData[key];
                    const errorElement = $('#' + getErrorElementId(key));

                    if (!value && key !== '_token') {
                        errorElement.removeClass('d-none');
                        hasError = true;
                    }
                });

                if (hasError) return; // Stop if there are frontend validation errors

                // AJAX request for add or update
                $.ajax({
                    url: url,
                    type: actionType === 'add' ? 'POST' : 'PUT',
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message || (actionType === 'add' ? 'Customer added successfully!' : 'Customer updated successfully!'),
                            icon: "success",
                        });

                        $('#addCutomerModal').modal('hide');
                        $('#customerForm')[0].reset(); // Reset form fields
                        refreshTable();
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) { // Validation errors from backend
                            const errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function (key) {
                                const errorElementId = getErrorElementId(key);
                                $('#' + errorElementId).text(errors[key][0]).removeClass('d-none');
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
                                text: "Something went wrong!",
                                footer: '<a href="#">An error occurred. Please try again.</a>'
                            });
                        }
                    },
                });
            });

            // Function to refresh the table content and reinitialize DataTables
            function refreshTable() {
                // Use a temporary element to load the new table content to avoid issues during reinitialization
                $('body').append('<div id="tempTableContent" style="display:none;"></div>');
                $('#tempTableContent').load("{{ route('show.customers') }} #tableHolder > *", function () {
                    // Destroy the old DataTable instance if it exists
                    if ($.fn.DataTable.isDataTable('#example1')) {
                        $('#example1').DataTable().destroy();
                    }

                    // Replace the old table body with the new one
                    $('#tableHolder').html($('#tempTableContent #tableHolder').html());
                    $('#tempTableContent').remove(); // Remove the temporary element

                    // Reinitialize the DataTable
                    initDataTable();
                });
            }

            // Initialize/Reinitialize DataTable
            function initDataTable() {
                if ($('#example1').length) {
                    if ($.fn.DataTable.isDataTable('#example1')) {
                        // Destroy previous instance of DataTable
                        $('#example1').DataTable().clear().destroy();
                    }

                    // Re-initialize DataTable with options
                    $('#example1').DataTable({
                        responsive: false, // <--- IMPORTANT: Changed to false
                        lengthChange: true,
                        autoWidth: false, // <--- IMPORTANT: Changed to false
                        scrollY: false,
                        scrollX: true,   // <--- IMPORTANT: Changed to false
                        buttons: ["excel", "pdf"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                } else {
                    console.log('Table #example1 not found in DOM, skipping initialization');
                }
            }

            // Edit customer logic
            $(document).on('click', '.edit-customer', function () {
                const customerId = $(this).data('id');
                $.ajax({
                    url: '/customers/' + customerId,
                    type: 'GET',
                    success: function (response) {
                        $('#addCutomerModalLabel').text('Edit Customer');
                        $('#customerForm')[0].reset();
                        $('.text-danger').addClass('d-none'); // Hide errors
                        $('#submitBtn').text('Update Customer').data('action', 'edit').data('id', customerId);
                        $('#name').val(response.customer.name);
                        $('#mobile_no').val(response.customer.mobile_number);
                        $('#address').val(response.customer.address);
                        $('#cnic').val(response.customer.cnic);
                        $('#addCutomerModal').modal('show');
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: "Could not fetch customer details. Please try again.",
                        });
                    },
                });
            });

            // Delete customer logic
            $(document).on('click', '.delete-customer', function () {
                const customerId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you really want to delete this customer?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/customers/' + customerId,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Customer deleted successfully!",
                                    icon: "success",
                                });
                                refreshTable();
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Something went wrong!",
                                    footer: '<a href="#">An error occurred while deleting the customer. Please try again.</a>'
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Cancelled",
                            text: "The customer is safe.",
                            icon: "info"
                        });
                    }
                });
            });
        });
    </script>
@endPushOnce