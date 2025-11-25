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

        /* General Body Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding-bottom: 20px;
            overflow-x: auto;
        }

        /* Filter Form Styles */
        .filter-container-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
            flex-direction: column;
            gap: 15px;
        }

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
        form.filter-form button {
            border-radius: 6px;
            font-size: 16px;
            transition: var(--transition);
        }

        form.filter-form select {
            cursor: pointer;
            min-width: 150px;
            border: 1px solid #ccc;
            padding: 8px;
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
        }

        .card-body {
            padding: 1.5rem;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 10px;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
        }

        .table th,
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--light-gray);
            white-space: nowrap;
            min-width: 120px;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

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

        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }

            form.filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .checkbox-group {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="row pt-16 sm:pt-6">
        <div class="col-12 justify-content-end align-items-center d-flex">
            <button class="btn btn-primary" onclick="history.back()">← Back</button>
        </div>
    </div>

    {{-- ADD CUSTOMER MODAL --}}
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
                        {{-- FILTERS WRAPPER --}}
                        <div class="filter-container-wrapper mb-4">

                            {{-- FILTER FORM --}}
                            <form action="{{ route('customer.filter') }}" method="GET" class="filter-form">
                                @csrf
                                {{-- Keep status if selected --}}
                                @if(request('recovery_status'))
                                    <input type="hidden" name="recovery_status" value="{{ request('recovery_status') }}">
                                @endif

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

                                <button type="submit">Apply all filter</button>

                                {{-- Reset Button (Fixed Route) --}}
                                <a href="{{ route('show.customers') }}" class="btn btn-secondary"
                                    style="background: #6c757d; border:none; padding: 10px 20px; color:white; text-decoration:none; border-radius:8px;">
                                    Reset
                                </a>
                            </form>

                            {{-- NEW RECOVERY STATUS BUTTONS (PENDING, TODAY, UPCOMING) --}}
                            <div class="d-flex justify-content-center gap-2 flex-wrap mt-3">
                                <a href="{{ route('customer.filter', array_merge(request()->all(), ['recovery_status' => 'pending'])) }}"
                                    class="btn {{ request('recovery_status') == 'pending' ? 'btn-danger' : 'btn-outline-danger' }}">
                                    <i class="fa fa-clock"></i> Pending (Past Due)
                                </a>

                                <a href="{{ route('customer.filter', array_merge(request()->all(), ['recovery_status' => 'today'])) }}"
                                    class="btn {{ request('recovery_status') == 'today' ? 'btn-warning' : 'btn-outline-warning' }}">
                                    <i class="fa fa-calendar-day"></i> Today
                                </a>

                                <a href="{{ route('customer.filter', array_merge(request()->all(), ['recovery_status' => 'upcoming'])) }}"
                                    class="btn {{ request('recovery_status') == 'upcoming' ? 'btn-success' : 'btn-outline-success' }}">
                                    <i class="fa fa-calendar-check"></i> Upcoming
                                </a>
                                <a href="{{ route('customer.filter', array_merge(request()->all(), ['recovery_status' => 'no_date'])) }}"
                                    class="btn {{ request('recovery_status') == 'no_date' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                    <i class="fa fa-calendar-times"></i> No Date
                                </a>
                            </div>
                        </div>
                        <div class="row mt-4 mb-2 justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-center gap-4">
                                    {{-- Total Debit Card --}}
                                    <div class="card bg-danger text-white flex-fill text-center p-3 shadow-sm"
                                        style="border: none; border-radius: 10px;">
                                        <h6 class="mb-1" style="font-size: 0.9rem; opacity: 0.9;">Total You Give (Debit)
                                        </h6>
                                        <h4 class="mb-0 font-weight-bold">{{ number_format($totalDebit ?? 0) }} RS</h4>
                                    </div>

                                    {{-- Total Credit Card --}}
                                    <div class="card bg-success text-white flex-fill text-center p-3 shadow-sm"
                                        style="border: none; border-radius: 10px;">
                                        <h6 class="mb-1" style="font-size: 0.9rem; opacity: 0.9;">Total You Got (Credit)
                                        </h6>
                                        <h4 class="mb-0 font-weight-bold">{{ number_format($totalCredit ?? 0) }} RS</h4>
                                    </div>
                                </div>
                            </div>
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
                                    <th>Recovery Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableHolder">
                                @foreach($customers as $customer)
                                    {{-- UPDATED TR TAG --}}
                                    <tr class="clickable-row" data-href="{{ route('customer.view', ['id' => $customer->id]) }}"
                                        style="cursor: pointer;">

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $customer->name ?? '' }}</td>
                                        <td>{{ $customer->mobile_number ?? ''}}</td>
                                        <td style="max-width: 150px; white-space: normal; word-break: break-word;">
                                            {{ $customer->address ?? '' }}
                                        </td>
                                        <td>{{ $customer->cnic ?? '' }}</td>
                                        <td>{{ $customer->debit ?? '' }}</td>
                                        <td>{{ $customer->credit ?? '' }}</td>
                                        <td>
                                            @if($customer->activeRecoveryDate)
                                                @php
                                                    $rDate = \Carbon\Carbon::parse($customer->activeRecoveryDate->recovery_date);
                                                    $isPast = $rDate->isPast() && !$rDate->isToday();
                                                    $isToday = $rDate->isToday();
                                                @endphp
                                                <span
                                                    class="badge {{ $isPast ? 'bg-danger' : ($isToday ? 'bg-warning text-dark' : 'bg-success') }}"
                                                    style="font-size: 0.9em;">
                                                    {{ $rDate->format('d M, Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('customer.view', ['id' => $customer->id]) }}"
                                                    class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fa fa-eye"></i> View
                                                </a>

                                                {{-- WHATSAPP BUTTON --}}
                                                @php
                                                    $balance = $customer->debit > 0 ? $customer->debit : $customer->credit;
                                                    $balanceType = $customer->debit > 0 ? 'Debit' : 'Credit';
                                                @endphp
                                                <button class="btn btn-sm btn-success open-whatsapp-btn"
                                                    data-name="{{ $customer->name }}"
                                                    data-mobile="{{ $customer->mobile_number }}"
                                                    data-balance="{{ $balance }} ({{ $balanceType }})" title="Open WhatsApp">
                                                    <i class="fab fa-whatsapp"></i> Reminder
                                                </button>
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
            initDataTable();
// ============================================================
//  ROW CLICK LOGIC (Table Row click par View Page)
// ============================================================
$(document).on('click', '.clickable-row', function(e) {
    // Check: Agar user ne Button, Link (a tag) ya .btn class par click kiya hai to redirect mat karo
    if ($(e.target).closest('a, button, .btn, input').length) {
        return;
    }
    
    // Redirect to the URL stored in data-href
    window.location = $(this).data('href');
});
            // -----------------------------------------------------------
            // WHATSAPP CLICK-TO-CHAT LOGIC (Client Side)
            // -----------------------------------------------------------
            $(document).on('click', '.open-whatsapp-btn', function () {
                var name = $(this).data('name');
                var rawMobile = $(this).data('mobile');
                var balance = $(this).data('balance');

                // 1. Check if number exists
                if (!rawMobile) {
                    Swal.fire("Error", "No mobile number found for this customer.", "warning");
                    return;
                }

                // 2. Handle Multiple Numbers
                var phones = rawMobile.toString().split(',').map(function (num) {
                    return num.trim();
                });

                // 3. Prepare Message (Added space after balance)
                var text = `"Dear ${name},
        This is a formal reminder from *RANA ELECTRONICS KM*. You have an outstanding balance of ${balance} on your account. Kindly clear these dues at your earliest convenience.
        Thank you."
        For further detail contact us.
        *RANA ELECTRONICS KM*
        03007667440

        السلام علیکم
        جناب ${name}
        یہ *رانا الیکٹرونکس کوٹمومن* کی جانب سے ادائیگی کی یاددہانی ہے۔ آپ کے کھاتے میں ${balance} کی بقایا رقم ہے۔ برائے مہربانی ان واجبات کو جلد از جلد ادا کریں۔ شکریہ۔
        مزید تفصیلات کے لیے ہم سے رابطہ کریں۔
        *رانا الیکٹرونکس کوٹمومن*
        03007667440`;


                var encodedText = encodeURIComponent(text);

                // 4. Helper to Format Number
                function getWaLink(number) {
                    // Remove all non-digits
                    var clean = number.replace(/\D/g, '');

                    // Logic for Pakistan numbers
                    if (clean.startsWith('03')) {
                        clean = '92' + clean.substring(1); // 0300 -> 92300
                    }
                    else if (clean.length === 10 && clean.startsWith('3')) {
                        clean = '92' + clean; // 300 -> 92300
                    }
                    else if (clean.startsWith('00')) {
                        clean = clean.substring(2); // Remove leading 00 if present
                    }

                    // CHANGE HERE: Use api.whatsapp.com/send 
                    // Yeh format unsaved numbers ke liye best kaam karta hai
                    return `https://api.whatsapp.com/send?phone=${clean}&text=${encodedText}`;
                }

                // 5. Logic: One number vs Multiple
                if (phones.length === 1) {
                    window.open(getWaLink(phones[0]), '_blank');
                } else {
                    var inputOptions = {};
                    phones.forEach(function (phone) {
                        inputOptions[phone] = phone;
                    });

                    Swal.fire({
                        title: 'Select Number',
                        text: `${name} has multiple numbers. Which one to message?`,
                        input: 'radio',
                        inputOptions: inputOptions,
                        inputValue: phones[0],
                        showCancelButton: true,
                        confirmButtonText: 'Open WhatsApp <i class="fa fa-external-link"></i>',
                        confirmButtonColor: '#25D366',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed && result.value) {
                            window.open(getWaLink(result.value), '_blank');
                        }
                    });
                }
            });// -----------------------------------------------------------
            // EXISTING CUSTOMER ADD/EDIT/DELETE LOGIC
            // -----------------------------------------------------------

            // Open modal for adding a new customer
            $('#addCustomerBtn').click(function () {
                $('#addCutomerModalLabel').text('Add New Customer');
                $('#customerForm')[0].reset();
                $('.text-danger').addClass('d-none');
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

                let hasError = false;
                $('.text-danger').addClass('d-none');

                function getErrorElementId(key) {
                    return key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase()) + 'Error';
                }

                Object.keys(formData).forEach(function (key) {
                    const value = formData[key];
                    const errorElement = $('#' + getErrorElementId(key));

                    // CHANGE HERE: Humne check lagaya ke agar key 'cnic' hai to error na dikhaye
                    if (!value && key !== '_token' && key !== 'cnic') {
                        errorElement.removeClass('d-none');
                        hasError = true;
                    }
                });

                if (hasError) return;

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
                        $('#customerForm')[0].reset();
                        refreshTable();
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function (key) {
                                const errorElementId = getErrorElementId(key);
                                $('#' + errorElementId).text(errors[key][0]).removeClass('d-none');
                            });
                            Swal.fire({ icon: "error", title: "Validation Error", text: "Please correct the highlighted fields." });
                        } else {
                            Swal.fire({ icon: "error", title: "Oops...", text: "Something went wrong!" });
                        }
                    },
                });
            });

            // Edit customer logic
            $(document).on('click', '.edit-customer', function () {
                const customerId = $(this).data('id');
                $.ajax({
                    url: '/customers/' + customerId,
                    type: 'GET',
                    success: function (response) {
                        $('#addCutomerModalLabel').text('Edit Customer');
                        $('#customerForm')[0].reset();
                        $('.text-danger').addClass('d-none');
                        $('#submitBtn').text('Update Customer').data('action', 'edit').data('id', customerId);
                        $('#name').val(response.customer.name);
                        $('#mobile_no').val(response.customer.mobile_number);
                        $('#address').val(response.customer.address);
                        $('#cnic').val(response.customer.cnic);
                        $('#addCutomerModal').modal('show');
                    },
                    error: function () {
                        Swal.fire({ icon: "error", title: "Error!", text: "Could not fetch customer details." });
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
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/customers/' + customerId,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                Swal.fire("Deleted!", "Customer deleted successfully!", "success");
                                refreshTable();
                            },
                            error: function (xhr) {
                                Swal.fire("Error", "Something went wrong!", "error");
                            }
                        });
                    }
                });
            });

            // Function to refresh the table content
            function refreshTable() {
                $('body').append('<div id="tempTableContent" style="display:none;"></div>');
                $('#tempTableContent').load("{{ route('show.customers') }} #tableHolder > *", function () {
                    if ($.fn.DataTable.isDataTable('#example1')) {
                        $('#example1').DataTable().destroy();
                    }
                    $('#tableHolder').html($('#tempTableContent #tableHolder').html());
                    $('#tempTableContent').remove();
                    initDataTable();
                });
            }

            // Initialize DataTable
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
                        scrollX: true,
                        "pageLength": 100,
                        "order": [],
                        buttons: ["excel", "pdf"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }
            }
        });
    </script>
@endPushOnce