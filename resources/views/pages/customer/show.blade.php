@extends('index')

@section('content')
    <style>
        /* Root Variables (Unchanged) */
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

        /* General Body Styling (Unchanged) */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding-bottom: 20px;
            overflow-x: auto;
        }

        /* --- CHECKBOX SIZE FIX --- */
        input[type="checkbox"].customer-checkbox,
        input[type="checkbox"]#selectAllCustomers {
            transform: scale(1.7); /* Slightly larger checkbox size */
            margin: 0;
            cursor: pointer;
        }
        
        /* Filter Form Styles (Unchanged) */
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

        /* Card and Table Styles (Unchanged) */
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
            min-width: 250px;
        }

        .action-buttons .btn {
            padding: 0.4rem 0.3rem;
            font-size: 0.85rem;
        }

        /* Responsive (Unchanged) */
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

        .stat-box {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--card-shadow);
            border-left: 5px solid #ccc;
            /* Default Border */
            transition: transform 0.2s;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-box:hover {
            transform: translateY(-3px);
        }

        .stat-box.debit {
            border-left-color: #dc3545;
        }

        .stat-box.credit {
            border-left-color: #198754;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        /* --- 2. Aligned Filter Bar --- */
        .filter-wrapper {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
        }

        /* Custom Checkbox Button Style */
        .btn-check+.btn-outline-custom {
            color: #6c757d;
            border-color: #dee2e6;
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-check:checked+.btn-outline-custom {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: none;
        }

        /* Form Controls alignment */
        .filter-form-row {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* --- 3. Quick Action Tabs (Pills) --- */
        .nav-pills .nav-link {
            color: #495057;
            background-color: white;
            border: 1px solid #dee2e6;
            margin: 0 5px;
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .nav-pills .nav-link.active-pending {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .nav-pills .nav-link.active-today {
            background-color: #ffc107;
            color: black;
            border-color: #ffc107;
        }

        .nav-pills .nav-link.active-upcoming {
            background-color: #198754;
            color: white;
            border-color: #198754;
        }

        .nav-pills .nav-link.active-default {
            background-color: #6c757d;
            color: white;
            border-color: #6c757d;
        }

        .nav-pills .nav-link:hover {
            background-color: #e9ecef;
            color: black;
        }

        /* --- Mobile Adjustments --- */
        @media (max-width: 768px) {
            .filter-form-row {
                flex-direction: column;
                align-items: stretch;
            }

            .stat-box {
                margin-bottom: 10px;
                text-align: center;
            }
        }

        /* --- Filter Card Container --- */
        .filter-card {
            background: #ffffff;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            /* Soft modern shadow */
            border: 1px solid #eff2f7;
            margin-bottom: 25px;
        }

        /* --- 1. Sort Section --- */
        .sort-group {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 220px;
        }

        .sort-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .custom-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            color: #495057;
            cursor: pointer;
            background-color: #f8f9fa;
            transition: all 0.2s;
        }

        .custom-select:focus {
            border-color: var(--primary-color);
            background-color: #fff;
            outline: none;
        }

        /* --- 2. Checkbox Pills (Center) --- */
        .filter-toggles {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            flex-grow: 1;
            /* Takes up available middle space */
        }

        /* Hide default checkbox */
        .btn-check {
            position: absolute;
            clip: rect(0, 0, 0, 0);
            pointer-events: none;
        }

        /* Custom Pill Design */
        .filter-pill {
            display: inline-block;
            padding: 8px 18px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            background-color: #fff;
            border: 1px solid #dfe3e8;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            user-select: none;
        }

        .filter-pill:hover {
            background-color: #f8f9fa;
            border-color: #c4c8ce;
        }

        /* Active State (When Checked) */
        .btn-check:checked+.filter-pill {
            background-color: #e7f1ff;
            color: #0d6efd;
            border-color: #0d6efd;
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.15);
        }

        /* --- 3. Action Buttons (Right) --- */
        .action-group {
            display: flex;
            gap: 10px;
        }

        .btn-custom-primary {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: transform 0.2s;
        }

        .btn-custom-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
        }

        .btn-custom-reset {
            background-color: white;
            color: #6c757d;
            border: 1px solid #dfe3e8;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .btn-custom-reset:hover {
            background-color: #f8f9fa;
            color: #dc3545;
            border-color: #dc3545;
        }
        
        /* NEW STYLE: Bulk Actions Bar */
        .bulk-actions-bar {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Aligned left for better flow */
            gap: 10px;
        }

        .bulk-actions-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* --- Responsiveness --- */
        @media (max-width: 992px) {
            .filter-card form {
                flex-direction: column;
                align-items: stretch !important;
                gap: 20px;
            }

            .sort-group,
            .filter-toggles,
            .action-group {
                width: 100%;
                justify-content: center;
            }

            .action-group button,
            .action-group a {
                width: 50%;
                /* Buttons share width on mobile */
                text-align: center;
            }
            .bulk-actions-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .bulk-actions-group {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>

    <div class="row pt-16 sm:pt-6">
        <div class="col-12 justify-content-end align-items-center d-flex">
            <button class="btn btn-primary" onclick="history.back()">← Back</button>
        </div>
    </div>

    {{-- ADD/EDIT CUSTOMER MODAL (Remains Unchanged) --}}
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
                                <small class="text-danger d-none" id="cnicError">Invalid CNIC format.</small>
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
    
    {{-- NEW: BULK RECOVERY DATE MODAL --}}
    <div class="modal fade" id="bulkRecoveryModal" tabindex="-1" role="dialog" aria-labelledby="bulkRecoveryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkRecoveryModalLabel">Set Bulk Recovery Date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info" id="selectedCustomerCount"></div>
                    <label class="mb-2" style="font-size: 1em; color: #343a40;">Select Recovery Date:</label>
                    <input type="date" id="bulkRecoveryDateInput" class="form-control" min="{{ date('Y-m-d') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmBulkRecoveryBtn">Set Date for Selected</button>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                   <div class="card-header d-flex flex-column flex-md-row align-items-center bg-white py-3 border-bottom">

                        {{-- Title Section --}}
                        <div class="mb-2 mb-md-0">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-users me-2 text-primary"></i> Customer Detail
                            </h5>
                        </div>

                        {{-- ADD CUSTOMER BUTTON ONLY --}}
                        <div class="d-flex gap-2 ms-md-auto ml-md-auto align-items-center">
                            <button type="button" class="btn btn-primary px-4 shadow-sm" id="addCustomerBtn"
                                style="border-radius: 50px; font-weight: 500;">
                                <i class="fas fa-user-plus me-1"></i> Add Customer
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- FILTERS WRAPPER (Unchanged) --}}
                        <div class="card-body">

                            <div class="row mb-4">
                                <div class="col-12 col-md-6 mb-2 mb-md-0">
                                    <div class="stat-box debit">
                                        <div class="stat-label">Total You Give (Debit)</div>
                                        <div class="stat-number text-danger">{{ number_format($totalDebit ?? 0) }} RS</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="stat-box credit">
                                        <div class="stat-label">Total You Got (Credit)</div>
                                        <div class="stat-number text-success">{{ number_format($totalCredit ?? 0) }} RS
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="filter-wrapper mb-4">
                                <div class="filter-card">
                                    <form action="{{ route('customer.filter') }}" method="GET"
                                        class="d-flex align-items-center justify-content-between w-100 gap-3">
                                        @csrf
                                        @if(request('recovery_status'))
                                            <input type="hidden" name="recovery_status"
                                                value="{{ request('recovery_status') }}">
                                        @endif
                                        <div class="sort-group">
                                            <span class="sort-label"><i class="fas fa-sort-amount-down me-1"></i> Sort
                                                By:</span>
                                            <select name="sort_order" class="custom-select w-100">
                                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
                                                    Oldest First (Low to High)</option>
                                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                                    Newest First (High to Low)</option>
                                            </select>
                                        </div>
                                        <div class="filter-toggles">
                                            <input type="checkbox" class="btn-check" id="check-debit" name="filter_debit" {{ request('filter_debit') ? 'checked' : '' }}>
                                            <label class="filter-pill" for="check-debit">
                                                <i class="fas fa-arrow-up me-1"></i> Debit
                                            </label>
                                            <input type="checkbox" class="btn-check" id="check-credit" name="filter_credit"
                                                {{ request('filter_credit') ? 'checked' : '' }}>
                                            <label class="filter-pill" for="check-credit">
                                                <i class="fas fa-arrow-down me-1"></i> Credit
                                            </label>
                                            <input type="checkbox" class="btn-check" id="check-zero"
                                                name="hide_zero_balance" {{ request('hide_zero_balance') ? 'checked' : '' }}>
                                            <label class="filter-pill" for="check-zero">
                                                <i class="fas fa-eye-slash me-1"></i> Hide Zero Bal
                                            </label>
                                        </div>
                                        <div class="action-group">
                                            <button type="submit" class="btn-custom-primary">
                                                Apply Filters
                                            </button>
                                            <a href="{{ route('show.customers') }}" class="btn-custom-reset">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>

                                    </form>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center mb-4 overflow-auto pb-2">
                                <nav class="nav nav-pills flex-nowrap">
                                    <a href="{{ route('customer.filter', array_merge(request()->except('recovery_status'), ['recovery_status' => 'pending'])) }}"
                                        class="nav-link {{ request('recovery_status') == 'pending' ? 'active-pending' : '' }}">
                                        <i class="fa fa-clock me-1"></i> Pending
                                    </a>

                                    <a href="{{ route('customer.filter', array_merge(request()->except('recovery_status'), ['recovery_status' => 'today'])) }}"
                                        class="nav-link {{ request('recovery_status') == 'today' ? 'active-today' : '' }}">
                                        <i class="fa fa-calendar-day me-1"></i> Today
                                    </a>

                                    <a href="{{ route('customer.filter', array_merge(request()->except('recovery_status'), ['recovery_status' => 'upcoming'])) }}"
                                        class="nav-link {{ request('recovery_status') == 'upcoming' ? 'active-upcoming' : '' }}">
                                        <i class="fa fa-calendar-check me-1"></i> Upcoming
                                    </a>

                                    <a href="{{ route('customer.filter', array_merge(request()->except('recovery_status'), ['recovery_status' => 'no_date'])) }}"
                                        class="nav-link {{ request('recovery_status') == 'no_date' ? 'active-default' : '' }}">
                                        <i class="fa fa-calendar-times me-1"></i> No Date
                                    </a>
                                </nav>
                            </div>

                        </div>
                        
                        {{-- NEW: BULK ACTIONS BAR ABOVE THE TABLE --}}
                        <div class="bulk-actions-bar">
                            <div class="bulk-actions-group">
                                <select id="bulkActionSelector" class="form-control form-control-sm" style="width: 200px;" disabled>
                                    <option value="">Bulk Actions (0 Selected)</option>
                                    <option value="set_recovery">Set Recovery Date</option>
                                    <option value="mark_received">Mark Received</option>
                                </select>
                                <button class="btn btn-sm btn-primary" id="applyBulkActionBtn" disabled>
                                    Apply
                                </button>
                            </div>
                        </div>
                            
                        {{-- DataTables Table --}}
                        <table class="table table-hover w-100" id="example1">
                            <thead class="bg-primary text-white">
                                <tr>
                                    {{-- NEW: Master Checkbox --}}
                                    <th style="width: 50px; text-align: center;">
                                        <input type="checkbox" id="selectAllCustomers">
                                    </th>
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
                                    @php
                                        // Recovery Date data setup
                                        $activeRecovery = $customer->activeRecoveryDate;
                                        $isReceived = $activeRecovery ? ($activeRecovery->is_received == 1) : false;
                                    @endphp
                                    <tr class="clickable-row" data-href="{{ route('customer.view', ['id' => $customer->id]) }}"
                                        style="cursor: pointer;">
                                        
                                        {{-- NEW: Individual Checkbox --}}
                                        <td style="width: 50px; text-align: center;">
                                            <input type="checkbox" class="customer-checkbox" data-id="{{ $customer->id }}" data-recovery-id="{{ $activeRecovery ? $activeRecovery->id : '' }}" onclick="event.stopPropagation()">
                                        </td>
                                        
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $customer->name ?? '' }}</td>
                                        <td>{{ $customer->mobile_number ?? ''}}</td>
                                        <td style="max-width: 150px; white-space: normal; word-break: break-word;">
                                            {{ $customer->address ?? '' }}
                                        </td>
                                        <td>{{ $customer->cnic ?? '' }}</td>
                                        <td>{{ number_format($customer->debit ?? 0) }}</td>
                                        <td>{{ number_format($customer->credit ?? 0) }}</td>
                                        <td>
                                            @if($activeRecovery)
                                                @php
                                                    $rDate = \Carbon\Carbon::parse($activeRecovery->recovery_date);
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
                                                {{-- WHATSAPP BUTTON --}}
                                                @php
                                                    $balance = $customer->debit > 0 ? $customer->debit : $customer->credit;
                                                    $balanceType = $customer->debit > 0 ? 'Debit' : 'Credit';
                                                @endphp
                                                <button class="btn btn-sm btn-success open-whatsapp-btn"
                                                    data-name="{{ $customer->name }}"
                                                    data-mobile="{{ $customer->mobile_number }}"
                                                    data-balance="{{ number_format($balance) }} ({{ $balanceType }})" title="Open WhatsApp">
                                                    <i class="fab fa-whatsapp"></i> Reminder
                                                </button>
                                                
                                                {{-- NEW: MARK RECEIVED BUTTON --}}
                                                @if($activeRecovery && !$isReceived)
                                                    <button class="btn btn-sm btn-success mark-received-show"
                                                        data-id="{{ $activeRecovery->id }}" title="Mark Payment Received">
                                                        <i class="fa fa-check"></i> Receive
                                                    </button>
                                                @elseif($activeRecovery && $isReceived)
                                                    <button class="btn btn-sm btn-secondary" disabled title="Payment Already Received">
                                                        <i class="fa fa-check-double"></i> Received
                                                    </button>
                                                @endif

                                                {{-- NEW: EDIT Button --}}
                                                <button class="btn btn-sm btn-info edit-customer" data-id="{{ $customer->id }}"
                                                    title="Edit Customer Details">
                                                    <i class="fas fa-edit"></i> Edit
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
            //  ROW CLICK LOGIC (Existing - Remains Unchanged)
            // ============================================================
            $(document).on('click', '.clickable-row', function (e) {
                if ($(e.target).closest('a, button, .btn, input').length) {
                    return;
                }
                window.location = $(this).data('href');
            });
            
            // ============================================================
            // 1. BULK ACTION LOGIC (Updated for Select/Apply Buttons)
            // ============================================================
            
            // Function to update the bulk action dropdown text/state
            function updateBulkActionState() {
                const selectedCount = $('.customer-checkbox:checked').length;
                const selector = $('#bulkActionSelector');
                const applyBtn = $('#applyBulkActionBtn');
                
                // Update selector display text
                selector.find('option:first').text(`Bulk Actions (${selectedCount} Selected)`);
                // Disable/Enable the selector and apply button
                selector.prop('disabled', selectedCount === 0);
                applyBtn.prop('disabled', selectedCount === 0 || selector.val() === '');
            }
            
            // Apply button enablement based on selector change
            $('#bulkActionSelector').on('change', function() {
                updateBulkActionState();
            });

            // Master Checkbox Toggle
            $('#selectAllCustomers').on('change', function () {
                $('.customer-checkbox').prop('checked', $(this).prop('checked'));
                updateBulkActionState();
            });

            // Individual Checkbox Change
            $(document).on('change', '.customer-checkbox', function () {
                const total = $('.customer-checkbox').length;
                const checked = $('.customer-checkbox:checked').length;
                
                $('#selectAllCustomers').prop('checked', total === checked);
                updateBulkActionState();
            });

            // Bulk Action Apply Button Handler
            $('#applyBulkActionBtn').on('click', function () {
                const action = $('#bulkActionSelector').val();
                const selectedIds = $('.customer-checkbox:checked').map(function () {
                    return $(this).data('id');
                }).get();
                const selectedRecoveryIds = $('.customer-checkbox:checked').map(function () {
                    // Only get recovery IDs if they exist (are not empty string or null)
                    const recoveryId = $(this).data('recovery-id');
                    return recoveryId ? recoveryId : null;
                }).get().filter(id => id !== null); // Filter out null/empty IDs

                if (selectedIds.length === 0) {
                    Swal.fire('Error', 'Please select at least one customer.', 'warning');
                    return;
                }

                if (action === 'set_recovery') {
                    // Open Bulk Recovery Modal
                    $('#selectedCustomerCount').text(`${selectedIds.length} customer(s) selected.`);
                    $('#bulkRecoveryModal').modal('show');
                } else if (action === 'mark_received') {
                    // Execute Bulk Mark Received
                    if (selectedRecoveryIds.length === 0) {
                        Swal.fire('Error', 'No selected customers have active recovery dates to mark as received.', 'warning');
                        return;
                    }
                    confirmBulkMarkReceived(selectedRecoveryIds);
                }
            });
            
            // Confirm Bulk Recovery Date Setter
            $('#confirmBulkRecoveryBtn').on('click', function () {
                const date = $('#bulkRecoveryDateInput').val();
                if (!date) {
                    Swal.fire('Error', 'Please select a recovery date.', 'warning');
                    return;
                }
                
                const selectedIds = $('.customer-checkbox:checked').map(function () {
                    return $(this).data('id');
                }).get();

                Swal.fire({
                    title: "Confirm Date Set?",
                    text: `Are you sure you want to set the recovery date (${date}) for ${selectedIds.length} customer(s)? This will override any existing active date.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Set Date"
                }).then((result) => {
                    if (result.isConfirmed) {
                        executeBulkRecoveryDate(selectedIds, date);
                    }
                });
            });

            function executeBulkRecoveryDate(customerIds, date) {
                // Use recursion for sequential AJAX calls
                let successCount = 0;
                let failureCount = 0;
                
                const nextAction = () => {
                    if (customerIds.length === 0) {
                        $('#bulkRecoveryModal').modal('hide');
                        Swal.fire('Complete', `${successCount} customers updated successfully. ${failureCount} failed.`, 'success').then(() => {
                            location.reload();
                        });
                        return;
                    }

                    const customerId = customerIds.shift();

                    $.ajax({
                        url: '/customer/recovery/add', 
                        type: 'POST',
                        data: { 
                            date: date, 
                            customer_id: customerId, 
                            _token: '{{ csrf_token() }}' 
                        },
                        success: function () {
                            successCount++;
                            nextAction();
                        },
                        error: function () {
                            failureCount++;
                            nextAction();
                        }
                    });
                };
                
                nextAction();
            }
            
            function confirmBulkMarkReceived(recoveryIds) {
                 Swal.fire({
                    title: "Confirm Received?",
                    text: `Are you sure you want to mark ${recoveryIds.length} active payments as Received?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Mark Received"
                }).then((result) => {
                    if (result.isConfirmed) {
                        executeBulkMarkReceived(recoveryIds);
                    }
                });
            }
            
            function executeBulkMarkReceived(recoveryIds) {
                // Use recursion for sequential AJAX calls
                let successCount = 0;
                let failureCount = 0;
                
                const nextAction = () => {
                    if (recoveryIds.length === 0) {
                        Swal.fire('Complete', `${successCount} payments marked as received. ${failureCount} failed.`, 'success').then(() => {
                            location.reload(); // Reload after bulk operation finishes
                        });
                        return;
                    }

                    const recoveryId = recoveryIds.shift();
                    
                    $.ajax({
                        url: '/customer/recovery/received',
                        type: 'POST',
                        data: { id: recoveryId, _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if (response.status === 'success') {
                                successCount++;
                            } else {
                                failureCount++;
                            }
                            nextAction();
                        },
                        error: function () {
                            failureCount++;
                            nextAction();
                        }
                    });
                };
                
                nextAction();
            }

            // ============================================================
            // 2. MARK AS RECEIVED (Single Row Logic - Existing)
            // ============================================================
            $(document).on('click', '.mark-received-show', function () {
                var id = $(this).data('id');
                var btn = $(this);
                
                if (confirm('Are you sure you want to mark this payment as Received?')) {
                    btn.prop('disabled', true).text('Processing...');
                    $.ajax({
                        url: '/customer/recovery/received', 
                        type: 'POST',
                        data: { id: id, _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire('Received!', 'Payment successfully marked as received.', 'success').then(() => {
                                    location.reload(); 
                                });
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Error updating status. Please try again.', 'error');
                            btn.prop('disabled', false).html('<i class="fa fa-check"></i> Receive');
                        }
                    });
                }
            });
            // -----------------------------------------------------------
            // (Rest of the existing JS logic remains unchanged: ADD/EDIT/DELETE customer, initDataTable)
            // -----------------------------------------------------------
            
            // Edit customer logic (Fetch data)
            $(document).on('click', '.edit-customer', function () {
                const customerId = $(this).data('id');
                $.ajax({
                    url: '/customers/' + customerId, // GET route to CustomerController@Customer_edit
                    type: 'GET',
                    success: function (response) {
                        const customerData = response.customer || response; 

                        $('#addCutomerModalLabel').text('Edit Customer');
                        $('#customerForm')[0].reset();
                        $('.text-danger').addClass('d-none');
                        $('#submitBtn').text('Update Customer').data('action', 'edit').data('id', customerId); 
                        
                        $('#name').val(customerData.name);
                        $('#mobile_no').val(customerData.mobile_number); 
                        $('#address').val(customerData.address);
                        $('#cnic').val(customerData.cnic); 
                        
                        $('#addCutomerModal').modal('show');
                    },
                    error: function (xhr) {
                        Swal.fire({ icon: "error", title: "Error!", text: "Could not fetch customer details." });
                    },
                });
            });

            // Delete customer logic
            $(document).on('click', '.delete-customer', function () {
                const customerId = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you really want to delete this customer? This action is irreversible.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/customers/' + customerId, // DELETE route to CustomerController@Customer_delete
                            type: 'DELETE', // Use DELETE directly (FIXED 500 ERROR)
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                Swal.fire("Deleted!", response.message || "Customer deleted successfully!", "success");
                                refreshTable();
                            },
                            error: function (xhr) {
                                Swal.fire("Error", "Something went wrong during deletion!", "error");
                            }
                        });
                    }
                });
            });

            // Form submit handler for add/edit (Re-inserted for completeness)
            $('#customerForm').submit(function (e) {
                e.preventDefault();

                const actionType = $('#submitBtn').data('action');
                const customerId = $('#submitBtn').data('id');
                const url = actionType === 'add' ? '{{ route("add.customers") }}' : '/customers/' + customerId; 
                
                const httpMethod = actionType === 'add' ? 'POST' : 'PUT'; 

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

                    if (!value && key !== '_token' && key !== 'cnic') {
                        errorElement.removeClass('d-none').text(key.replace('_', ' ') + ' is required.');
                        hasError = true;
                    }
                });

                if (hasError) return;

                $.ajax({
                    url: url,
                    type: httpMethod, 
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
                            Swal.fire({ icon: "error", title: "Oops...", text: "Something went wrong! Check console for details." });
                            console.error("AJAX Error:", xhr.responseText); 
                        }
                    },
                });
            });

            // Function to refresh the table content
            function refreshTable() {
                const currentUrl = window.location.href; 
                $('body').append('<div id="tempTableContent" style="display:none;"></div>');
                $('#tempTableContent').load(currentUrl + " #tableHolder > *", function () { 
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