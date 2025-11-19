@extends('index')

@section('content')
<div class="row mt-4">
    <div class="col-12 justify-content-end align-items-center d-flex">
        <button class="btn btn-primary" onclick="history.back()">← Back</button>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        
        <div class="card mt-4">
            <div class="row">
                <div class="col-6 mb-3 mt-4 p-4">
                    <div style="background-color: black; color: white; padding: 20px; border-radius: 10px;">

                        <table style="width: 100%; color: white; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid white;">Customer Name</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid white;">Payment Type</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid white;">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 10px;">{{$customer->name}}</td>
                                    @if($customer->debit > 0)
                                    <td style="padding: 10px; color: green; font-weight: bold;">You Give</td>
                                    <td style="padding: 10px; color: green; font-weight: bold;">{{$customer->debit}}/RS</td>
                                    @else
                                    <td style="padding: 10px; color: red; font-weight: bold;">You Got</td>
                                    <td style="padding: 10px; color: red; font-weight: bold;">{{$customer->credit}}/RS</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>

                        {{-- DEBUG MODE: Changed check to TRUE so it always shows --}}
                        @if(true) 
                            <hr style="border-top: 1px solid #555; margin-top: 20px;">
                            
                            @php
                                // 1. Fetch the latest active date
                                // Use optional() or check relation existence to prevent crash if relation missing
                                $activeRecovery = $customer->recoveryDates ? $customer->recoveryDates()->where('is_active', 1)->first() : null;
                                
                                // 2. Check Expiry: If date exists but is in the past (yesterday or before), mark as expired
                                $isExpired = false;
                                if ($activeRecovery) {
                                    $dateObj = \Carbon\Carbon::parse($activeRecovery->recovery_date);
                                    // isPast() returns true if date is before right now. 
                                    // We usually want 'endOfDay' so today is still valid.
                                    if ($dateObj->endOfDay()->isPast()) {
                                        $isExpired = true;
                                    }
                                }
                            @endphp

                            <h6 class="mt-3 text-warning"><i class="fa fa-calendar"></i> Recovery Payment Date</h6>

                            @if($activeRecovery && !$isExpired)
                                <div class="mt-3 p-3" style="border: 1px dashed #fff; border-radius: 5px; background-color: #1a1a1a;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span style="font-size: 1.1em; font-weight: bold; color: #00ffaa;">
                                            Due: {{ \Carbon\Carbon::parse($activeRecovery->recovery_date)->format('d M, Y') }}
                                        </span>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-warning send-reminder" data-id="{{ $activeRecovery->id }}">
                                            <i class="fa fa-bell"></i> Reminder
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-recovery" data-id="{{ $activeRecovery->id }}">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>

                            @else
                                <div class="mt-3">
                                    <label class="mb-2" style="font-size: 0.9em;">Select Next Payment Date:</label>
                                    <div class="input-group">
                                        <input type="date" id="recoveryDateInput" class="form-control" min="{{ date('Y-m-d') }}">
                                        <button class="btn btn-success" id="addRecoveryBtn">Add</button>
                                    </div>
                                    
                                    @if($isExpired)
                                        <small class="text-danger d-block mt-2">
                                            <i class="fa fa-exclamation-circle"></i> Previous date ({{ $activeRecovery->recovery_date }}) has expired.
                                        </small>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="col-5 mb-4 mt-5 p-1">
                    <form id="paymentForm">
                        <div class="form-group">
                            <label for="dropdownAction">Select Payment Method</label>
                            <select id="dropdownAction" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="youGot">You Got</option>
                                <option value="youGive">You Give</option>
                            </select>
                        </div>
                        
                        <input type="hidden" id="customerId" value="{{ $customer->id }}" class="form-control">

                        <div id="youGiveForm" class="mt-3" style="display: none;">
                            <div class="form-group">
                                <label for="paymentInputYouGive">Enter Payment</label>
                                <input type="number" id="paymentInputYouGive" class="form-control" placeholder="Enter amount">
                            </div>
                            <button type="button" id="addPaymentYouGive" class="btn btn-primary">Add Payment</button>
                        </div>
                        <div id="youGotForm" class="mt-3" style="display: none;">
                            <div class="form-group">
                                <label for="paymentInputYouGot">Enter Payment</label>
                                <input type="number" id="paymentInputYouGot" class="form-control" placeholder="Enter amount">
                            </div>
                            <button type="button" id="addPaymentYouGot" class="btn btn-primary">Add Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card w-100">
            <h5 class="text-center mt-4 mb-4">Manual Payments Detail</h5>
            <table class="table table-hover w-100" id="example1">
                <thead class="bg-primary">
                    <tr>
                        <th>Payment Type</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody id="tableHolder">
                    @foreach($manual_customers->manualPayments as $payment)
                    <tr>
                        <td>{{ $payment->payment_type }}</td>
                        @if($payment->payment_type=='You Give')
                        <td style="color:green;"> {{ $payment->payment }}</td>
                        @endif
                        @if($payment->payment_type=='You Got')
                        <td style="color:red;"> {{ $payment->payment }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12">
        <div class="card w-100">
            <h5 class="text-center mt-4 mb-4">Sales Detail</h5>
            <table class="table table-hover w-100" id="example2"> <thead class="bg-primary">
                    <tr>
                        <th>Total Amount</th>
                        <th>Cash</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableHolderSales">
                    @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->total_amount ?? 'N/A' }}</td>
                        <td>{{ $sale->cash ?? 'N/A' }}</td>
                        <td style="display: flex; justify-content: space-between;">
                            <a href="javascript:void(0)" class="view-detail" data-id="{{ $sale->id }}">
                                <i class="fa fa-eye" aria-hidden="true"></i> View</a>
                            <a href="{{ route('pages.customer.invoice', $sale->id) }}" class="btn btn-sm btn-primary">
                                Regenerate Invoice
                            </a>
                            <a href="{{ route('show.transaction', ['id' => $sale->id]) }}" class="btn btn-warning">
                                Return Sale
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('customer.sales.summary', $customer->id) }}" class="btn btn-primary" style="width: 180px; margin-top: 20px;" >
                View Sales Summary
            </a>
        </div>
    </div>
</div>

<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
            </div>
            <div id="modal-body">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@pushOnce('scripts')
<script>
    $(document).ready(function() {

        // --- VIEW DETAILS MODAL ---
        $(document).on('click', '.view-detail', function() {
            var saleId = $(this).data('id');
            $.ajax({
                url: '/sales/' + saleId + '/detail',
                type: 'GET',
                success: function(response) {
                    $('#modal-body').html(response);
                    $(".btn-secondary").click(function() {
                        $('#transactionModal').modal('hide');
                    });
                    $('#transactionModal').modal('show');
                },
                error: function() {
                    alert('Error fetching transaction details.');
                }
            });
        });

        // --- TOGGLE PAYMENT FORMS ---
        $('#dropdownAction').on('change', function() {
            var action = $(this).val();
            if (action === 'youGive') {
                $('#youGiveForm').fadeIn();
                $('#youGotForm').fadeOut();
            } else if (action === 'youGot') {
                $('#youGiveForm').fadeOut();
                $('#youGotForm').fadeIn();
            } else {
                $('#youGiveForm').fadeOut();
                $('#youGotForm').fadeOut();
            }
        });

        // --- ADD PAYMENT (YOU GIVE) ---
        $('#addPaymentYouGive').on('click', function() {
            var credit = $('#paymentInputYouGive').val();
            var customerId = $('#customerId').val();
            if (credit) {
                sendPaymentData('youGive', credit, customerId); 
            }
        });
        
        // --- ADD PAYMENT (YOU GOT) ---
        $('#addPaymentYouGot').on('click', function() {
            var debit = $('#paymentInputYouGot').val();
            var customerId = $('#customerId').val();
            if (debit) {
                sendPaymentData('youGot', debit, customerId);
            }
        });

        // --- HELPER: SEND PAYMENT DATA ---
        function sendPaymentData(action, amount, customerId) {
            var data = {
                action: action,
                payment: amount,
                customer_id: customerId,
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '/customer/add',
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload();
                    }
                },
                error: function(error) {
                    alert('Error sending data. Please try again.');
                }
            });
        }

        // ============================================================
        // NEW: RECOVERY DATE JS LOGIC
        // ============================================================

        // 1. ADD RECOVERY DATE
        $('#addRecoveryBtn').on('click', function() {
            var date = $('#recoveryDateInput').val();
            var customerId = $('#customerId').val();

            if (!date) {
                alert('Please select a date');
                return;
            }

            $.ajax({
                url: '/customer/recovery/add', // Ensure route exists
                type: 'POST',
                data: {
                    date: date,
                    customer_id: customerId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert('Error adding recovery date.');
                }
            });
        });

        // 2. DELETE RECOVERY DATE
        $('.delete-recovery').on('click', function() {
            var id = $(this).data('id');
            if(confirm('Are you sure you want to delete this recovery date?')) {
                $.ajax({
                    url: '/customer/recovery/delete', // Ensure route exists
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Error deleting date.');
                    }
                });
            }
        });

        // 3. SEND REMINDER
        $('.send-reminder').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);
            
            // Visual feedback
            btn.html('<i class="fa fa-spinner fa-spin"></i> Sending...');
            btn.prop('disabled', true);

            $.ajax({
                url: '/customer/recovery/reminder', // Ensure route exists
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Reminder sent successfully!');
                    btn.html('<i class="fa fa-check"></i> Sent');
                    setTimeout(function(){
                        btn.html('<i class="fa fa-bell"></i> Reminder');
                        btn.prop('disabled', false);
                    }, 2000);
                },
                error: function() {
                    alert('Error sending reminder.');
                    btn.html('<i class="fa fa-bell"></i> Reminder');
                    btn.prop('disabled', false);
                }
            });
        });

    });
</script>
@endPushOnce