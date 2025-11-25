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
                {{-- LEFT SIDE: BALANCE & RECOVERY DATES --}}
                <div class="col-12 col-lg-6 mb-3 mt-4 p-4">
                    <div style="background-color: black; color: white; padding: 20px; border-radius: 10px;">
                        {{-- Balance Table --}}
                        <div class="table-responsive">
                            <table style="width: 100%; color: white; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid white;">
                                            Customer Name</th>
                                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid white;">
                                            Payment Type</th>
                                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid white;">
                                            Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 10px;">{{$customer->name}}</td>
                                        @if($customer->debit > 0)
                                            <td style="padding: 10px; color: green; font-weight: bold;">You Give</td>
                                            <td style="padding: 10px; color: green; font-weight: bold;">
                                                {{$customer->debit}}/RS</td>
                                        @else
                                            <td style="padding: 10px; color: red; font-weight: bold;">You Got</td>
                                            <td style="padding: 10px; color: red; font-weight: bold;">
                                                {{$customer->credit}}/RS</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr style="border-top: 1px solid #555; margin-top: 20px;">

                        @php
                            $allDates = $customer->recoveryDates;
                            $activeRecovery = $allDates->where('is_active', 1)->first();
                            $historyDates = $allDates->where('is_active', 0);
                        @endphp

                        <h6 class="mt-3 text-warning"><i class="fa fa-calendar"></i> Recovery Payment Dates</h6>

                        {{-- INPUT FORM --}}
                        @if($customer->credit > 0)
                            <div class="mt-3 mb-4">
                                <label class="mb-2" style="font-size: 0.9em; color: #ccc;">Add New/Update Date:</label>
                                <div class="input-group">
                                    <input type="date" id="recoveryDateInput" class="form-control"
                                        min="{{ date('Y-m-d') }}">
                                    <button class="btn btn-success" id="addRecoveryBtn">
                                        <i class="fa fa-plus"></i> Set Date
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- ACTIVE DATE DISPLAY --}}
                        @if($activeRecovery)
                            @php
                                $dateObj = \Carbon\Carbon::parse($activeRecovery->recovery_date);
                                $isExpired = $dateObj->endOfDay()->isPast();
                                $isReceived = $activeRecovery->is_received == 1;
                            @endphp

                            <div class="p-3 mb-3"
                                style="border: 1px dashed {{ $isReceived ? '#28a745' : ($isExpired ? 'red' : '#00ffaa') }}; border-radius: 5px; background-color: #1a1a1a;">
                                <span class="badge {{ $isReceived ? 'bg-success' : 'bg-primary' }} mb-2">
                                    {{ $isReceived ? 'Payment Received' : 'Current Active Date' }}
                                </span>

                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <span
                                        style="font-size: 1.2em; font-weight: bold; color: {{ $isReceived ? '#28a745' : ($isExpired ? 'red' : '#00ffaa') }};">
                                        {{ $isReceived ? 'Received On: ' : 'Due: ' }} {{ $dateObj->format('d M, Y') }}
                                    </span>
                                </div>

                                @if($isExpired && !$isReceived)
                                    <small class="text-danger"><i class="fa fa-exclamation-circle"></i> This date has
                                        passed.</small>
                                @endif

                                <div class="mt-3 d-flex flex-wrap gap-2">
                                    {{-- RECEIVED BUTTON --}}
                                    @if(!$isReceived)
                                        <button class="btn btn-sm btn-success mark-received mb-1"
                                            data-id="{{ $activeRecovery->id }}">
                                            <i class="fa fa-check"></i> Received
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-secondary mb-1" disabled>
                                            <i class="fa fa-check-double"></i> Received
                                        </button>
                                    @endif

                                    {{-- ACTION BUTTONS --}}
                                    @if(!$isReceived)
                                        @php
                                            $balance = $customer->debit > 0 ? $customer->debit : $customer->credit;
                                            $balanceType = $customer->debit > 0 ? 'Debit' : 'Credit';
                                            $formattedDate = $dateObj->format('d M, Y');
                                        @endphp
                                        <button class="btn btn-sm  send-reminder mb-1"
                                            style="background: rgb(57, 166, 57); color: white;"
                                            data-name="{{ $customer->name }}" data-mobile="{{ $customer->mobile_number }}"
                                            data-balance="{{ $balance }} ({{ $balanceType }})"
                                            data-due-date="{{ $formattedDate }}">
                                            <i class="fab fa-whatsapp"></i> Reminder
                                        </button>

                                        <button class="btn btn-sm btn-danger delete-recovery mb-1"
                                            data-id="{{ $activeRecovery->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- HISTORY LIST --}}
                        @if($historyDates->count() > 0)
                            <h6 class="mt-4 text-white" style="font-size: 0.9em; text-transform: uppercase;">History</h6>
                            <div style="max-height: 200px; overflow-y: auto;">
                                <div class="table-responsive">
                                    <table class="table table-dark table-sm table-striped" style="font-size: 0.9em;">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($historyDates as $date)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($date->recovery_date)->format('d M, Y') }}</td>
                                                    <td>
                                                        @if($date->is_received)
                                                            <span class="badge bg-success">Received</span>
                                                        @else
                                                            <span class="badge bg-secondary">Skipped/Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-xs btn-danger delete-recovery"
                                                            style="padding: 2px 5px;" data-id="{{ $date->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- RIGHT SIDE: PAYMENT FORM --}}
                <div class="col-12 col-lg-6 mb-3 mt-4 p-4">
                    <form id="paymentForm">
                        <div class="form-group">
                            <label for="dropdownAction">Select Payment Method</label>
                            <select id="dropdownAction" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="youGot">You Got</option>
                                <option value="youGive">You Give</option>
                            </select>
                        </div>

                        {{-- NEW: NOTE INPUT FIELD --}}
                        <div class="form-group mt-3">
                            <label for="paymentNote">Note / Description</label>
                            <textarea id="paymentNote" class="form-control" rows="2"
                                placeholder="Optional: Add details about this payment..."></textarea>
                        </div>

                        <input type="hidden" id="customerId" value="{{ $customer->id }}" class="form-control">

                        <div id="youGiveForm" class="mt-3" style="display: none;">
                            <div class="form-group">
                                <label for="paymentInputYouGive">Enter Payment</label>
                                <input type="number" id="paymentInputYouGive" class="form-control"
                                    placeholder="Enter amount">
                            </div>
                            <button type="button" id="addPaymentYouGive" class="btn btn-primary btn-block w-100">Add
                                Payment</button>
                        </div>

                        <div id="youGotForm" class="mt-3" style="display: none;">
                            <div class="form-group">
                                <label for="paymentInputYouGot">Enter Payment</label>
                                <input type="number" id="paymentInputYouGot" class="form-control"
                                    placeholder="Enter amount">
                            </div>
                            <button type="button" id="addPaymentYouGot" class="btn btn-primary btn-block w-100">Add
                                Payment</button>
                        </div>
                    </form>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('customer.sales.summary', $customer->id) }}" class="btn btn-info w-100">
                            <i class="fa fa-list-alt"></i> View Sales Summary
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card w-100">
            <h5 class="text-center mt-4 mb-4">Manual Payments Detail</h5>
            <div class="table-responsive p-2">
                <table class="table table-hover w-100" id="example1">
                    <thead class="bg-primary">
                        <tr>
                            <th>Payment Type</th>
                            <th>Payment</th>
                            <th>Note</th> {{-- Optional: Show Note in table if you want --}}
                        </tr>
                    </thead>
                    <tbody id="tableHolder">
                        @foreach($manual_customers->manualPayments as $payment)
                            <tr>
                                <td>{{ $payment->payment_type }}</td>
                                @if($payment->payment_type == 'You Give')
                                    <td style="color:green;"> {{ $payment->payment }}</td>
                                @endif
                                @if($payment->payment_type == 'You Got')
                                    <td style="color:red;"> {{ $payment->payment }}</td>
                                @endif
                                <td>{{ $payment->note ?? '-' }}</td> {{-- Display Note --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card w-100">
            <h5 class="text-center mt-4 mb-4">Sales Detail</h5>
            <div class="table-responsive p-2">
                <table class="table table-hover w-100" id="example2">
                    <thead class="bg-primary">
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
                                <td style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 5px;">
                                    <a href="javascript:void(0)" class="view-detail btn btn-sm btn-outline-dark"
                                        data-id="{{ $sale->id }}">
                                        <i class="fa fa-eye" aria-hidden="true"></i> View
                                    </a>
                                    <a href="{{ route('pages.customer.invoice', $sale->id) }}"
                                        class="btn btn-sm btn-primary">
                                        Regenerate Invoice
                                    </a>
                                    <a href="{{ route('show.transaction', ['id' => $sale->id]) }}"
                                        class="btn btn-sm btn-warning">
                                        Return Sale
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Code (No Change) --}}
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
        $(document).ready(function () {

            // 1. WHATSAPP REMINDER (Keep your existing code here)
            $(document).on('click', '.send-reminder', function () {
                var name = $(this).data('name');
                var rawMobile = $(this).data('mobile');
                var balance = $(this).data('balance');
                var dueDate = $(this).data('due-date');

                if (!rawMobile) {
                    Swal.fire("Error", "No mobile number found for this customer.", "warning");
                    return;
                }

                var phones = rawMobile.toString().split(',').map(function (num) {
                    return num.trim();
                });

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

                function getWaLink(number) {
                    var clean = number.replace(/\D/g, '');
                    if (clean.startsWith('03')) {
                        clean = '92' + clean.substring(1);
                    } else if (clean.length === 10 && clean.startsWith('3')) {
                        clean = '92' + clean;
                    }
                    return `https://wa.me/${clean}?text=${encodedText}`;
                }

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
            });

            // 2. MARK RECEIVED (Keep existing code)
            $(document).on('click', '.mark-received', function () {
                var id = $(this).data('id');
                var btn = $(this);
                if (confirm('Are you sure you want to mark this payment as Received?')) {
                    btn.prop('disabled', true);
                    $.ajax({
                        url: '/customer/recovery/received',
                        type: 'POST',
                        data: { id: id, _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if (response.status === 'success') location.reload();
                        },
                        error: function () {
                            alert('Error updating status.');
                            btn.prop('disabled', false);
                        }
                    });
                }
            });

            // 3. VIEW DETAIL (Keep existing code)
            $(document).on('click', '.view-detail', function () {
                var saleId = $(this).data('id');
                $.ajax({
                    url: '/sales/' + saleId + '/detail',
                    type: 'GET',
                    success: function (response) {
                        $('#modal-body').html(response);
                        $(".btn-secondary").click(function () {
                            $('#transactionModal').modal('hide');
                        });
                        $('#transactionModal').modal('show');
                    },
                    error: function () { alert('Error fetching details.'); }
                });
            });

            // ============================================================
            //  4. PAYMENT FORM TOGGLE & SUBMISSION (UPDATED)
            // ============================================================
            $('#dropdownAction').on('change', function () {
                var action = $(this).val();
                $('#youGiveForm, #youGotForm').hide();

                if (action === 'youGive') {
                    $('#youGiveForm').fadeIn();
                } else if (action === 'youGot') {
                    $('#youGotForm').fadeIn();
                }
            });

            $('#addPaymentYouGive').on('click', function () {
                var credit = $('#paymentInputYouGive').val();
                var customerId = $('#customerId').val();
                if (credit) {
                    sendPaymentData('youGive', credit, customerId);
                }
            });

            $('#addPaymentYouGot').on('click', function () {
                var debit = $('#paymentInputYouGot').val();
                var customerId = $('#customerId').val();
                if (debit) {
                    sendPaymentData('youGot', debit, customerId);
                }
            });

            // UPDATED FUNCTION TO SEND NOTE
            function sendPaymentData(action, amount, customerId) {
                var note = $('#paymentNote').val(); // GET NOTE VALUE

                var data = {
                    action: action,
                    payment: amount,
                    customer_id: customerId,
                    note: note, // SEND NOTE TO SERVER
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '/customer/add',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        if (response.status === 'success') {
                            location.reload();
                        }
                    },
                    error: function (error) {
                        alert('Error sending data. Please try again.');
                    }
                });
            }

            // 5. RECOVERY DATE (Keep existing code)
            $('#addRecoveryBtn').on('click', function () {
                var date = $('#recoveryDateInput').val();
                var customerId = $('#customerId').val();
                if (!date) { alert('Please select a date'); return; }
                $.ajax({
                    url: '/customer/recovery/add',
                    type: 'POST',
                    data: { date: date, customer_id: customerId, _token: '{{ csrf_token() }}' },
                    success: function (response) { location.reload(); },
                    error: function () { alert('Error adding recovery date.'); }
                });
            });

            $('.delete-recovery').on('click', function () {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this recovery date?')) {
                    $.ajax({
                        url: '/customer/recovery/delete',
                        type: 'POST',
                        data: { id: id, _token: '{{ csrf_token() }}' },
                        success: function (response) { location.reload(); },
                        error: function () { alert('Error deleting date.'); }
                    });
                }
            });

        });
    </script>
@endPushOnce