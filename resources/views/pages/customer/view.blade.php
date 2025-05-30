@extends('index')

@section('content')

<div class="row mt-5">
    <div class="col-12">
        
        <div class="card mt-4">
            <!-- Payment Box -->
            <div class="row">
                <div class="col-6 mb-3 mt-4 p-4">
                    <div style="background-color: black; color: white; padding: 20px; border-radius: 10px;">

                        <table style="width: 100%; color: white; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid white;">Customer Name</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid white;">Payment Type</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid white; ">Balance</th>
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
                    </div>
                </div>


                <div class="col-5 mb-4 mt-5 p-1">
                    <form id="paymentForm">
                        <!-- Dropdowns -->
                        <div class="form-group">
                            <label for="dropdownAction">Select Payment Method</label>
                            <select id="dropdownAction" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="youGot">You Got</option>
                                <option value="youGive">You Give</option>
                            </select>

                        </div>

                        <!-- You Give Form -->
                        <div id="youGiveForm" class="mt-3" style="display: none;">
                            <div class="form-group">
                                <label for="paymentInputYouGive">Enter Payment</label>
                                <input type="number" id="paymentInputYouGive" class="form-control" placeholder="Enter amount">
                                <input type="hidden" id="customerId" value="{{ $customer->id }}" class="form-control">

                            </div>
                            <button type="button" id="addPaymentYouGive" class="btn btn-primary">Add Payment</button>
                        </div>

                        <!-- You Got Form -->
                        <div id="youGotForm" class="mt-3" style="display: none;">
                            <div class="form-group">
                                <label for="paymentInputYouGot">Enter Payment</label>
                                <input type="number" id="paymentInputYouGot" class="form-control" placeholder="Enter amount">
                                <input type="hidden" id="customerId" value="{{ $customer->id }}" class="form-control">

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
        <table class="table table-hover w-100" id="example1">
            <thead class="bg-primary">
                <tr>

                    <th>Total Amount</th>
                    <th>Cash</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tableHolder">

                @foreach($sales as $sale)

                <tr>
                    <td>{{ $sale->total_amount ?? 'N/A' }}</td>
                    <td>{{ $sale->cash ?? 'N/A' }}</td>
                    <!-- <td>{{ $sale->note ?? 'N/A' }}</td> -->
                    <td style="display: flex; justify-content: space-between;">
                        <a href="javascript:void(0)" class="view-detail" data-id="{{ $sale->id }}">
                            <i class="fa fa-eye" aria-hidden="true"></i> View</a>
                            <a href="{{ route('pages.customer.invoice', $sale->id) }}" 
                        class="btn btn-sm btn-primary">
                            Regenerate Invoice
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

        $(document).on('click', '.view-detail', function() {
            var saleId = $(this).data('id');
            $.ajax({
                url: '/sales/' + saleId + '/detail',
                type: 'GET',
                success: function(response) {
                    console.log(response);
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

        // Add payment for "You Give"
        $('#addPaymentYouGive').on('click', function() {
            var credit = $('#paymentInputYouGive').val();
            var customerId = $('#customerId').val();
            if (credit) {
                sendPaymentData('youGive', credit, customerId); // Send data to backend for "You Give"
            }
        });
        // Add payment for "You Got"
        $('#addPaymentYouGot').on('click', function() {
            var debit = $('#paymentInputYouGot').val();
            var customerId = $('#customerId').val();

            if (debit) {
                sendPaymentData('youGot', debit, customerId);
            }
        });

        function sendPaymentData(action, amount, customerId) {
            var data = {
                action: action,
                payment: amount,
                customer_id: customerId,
                _token: '{{ csrf_token() }}'
            };

            function refreshPage() {
                location.reload();
            }
            $.ajax({
                url: '/customer/add',
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.status === 'success') {
                        refreshPage();
                    }
                },
                error: function(error) {
                    alert('Error sending data. Please try again.');
                }
            });
        }
    });
</script>
@endPushOnce