<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('pages.css')
    <style>
        @media print {

            .content-wrapper {
                display: block;
            }

            #print_invoice {
                display: none;
            }

            #gen_pdf {
                display: none;
            }

            .note_button {
                display: none;
            }

            button {
                display: none;
            }

            .note {
                display: none;
            }

            .btn-primary {
                display: none;
            }


        }


        .invoice-logo {
            display: block;
            margin: 0 auto;
        }

        .heading {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            @include('pages.sidebar')
        </aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">

                            <div class="invoice p-3 mb-3">
                                <!-- title row -->
                                <div class="row text-center">
                                    <div class="col-12 mt-5 mb-1">
                                        <img src="{{ asset('assets/logo/logo.jpg') }}" alt="not image" width="150" height="150" class="invoice-logo">
                                        <h1 class="heading mt-3">Rana Electronics Invoice</h1>
                                    </div>
                                </div>
                                @php
                                
                                $mytime = Carbon\Carbon::now();
                                $mytime->toDateTimeString();
                                @endphp
                                <div class="row">
                                    <div class="col-6 text-start">
                                        <h5 class="mb-0">Date: {{date('d-m-Y')}}</h5>
                                        <h3 class="mt-1"></h3>
                                    </div>
                                    </div>
                                    
                                <div class="row">
                                    <div class="col-6 text-start">
                                        <h5 class="mb-0">Chief Executive</h5>
                                        <h5 class="mt-1">USAMA RANA</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h4 class="mb-0">Mobile Number</h4>
                                        <h5 class="mt-1">03007667440</h4>
                                            <h5 class="mt-2">03218991304</h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="customer-info row">
                                            <div class="col-12">

                                                <h4 class="title"><strong>Customer Details</strong></h4>
                                                <div class="customer-detail">
                                                    <p><strong>Customer Name:</strong> {{ $sale->customers->name }} </p>
                                                    <p><strong>Mobile No:</strong>{{ $sale->customers->mobile_number }} </p>
                                                    <p><strong>Customer Address:</strong> {{ $sale->customers->address }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 w-100">
                                        <table class="table table-striped w-100">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">#Serial</th>
                                                    <!-- <th colspan="2">Customer Address</th> -->
                                                    <th colspan="2">Item Name</th>
                                                    <th colspan="2">Item Price</th>
                                                    <th colspan="2"> Qty</th>
                                                    <th colspan="2"> Discount</th>
                                                    <th colspan="2"> Service Charges</th>
                                                    <!-- <th colspan="3">Total Amount</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($invoices as $invoice)
                                                <tr>
                                                    <td colspan="2">{{ $loop->iteration }}</td>
                                                    <td colspan="2">{{$invoice->products->item_name }}</td>
                                                    <td colspan="2">{{$invoice->products->selling_price }}</td>
                                                    <td colspan="2">{{$invoice->quantity}}</td>
                                                    <td colspan="2">{{$invoice->discount}}</td>
                                                    <td colspan="2">{{$invoice->service_charges}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8">
                                        <!-- <p class="lead">Amount Due 2/22/2014</p> -->

                                        <div class="table-responsive">
                                            <table class="table">

                                                <tr>
                                                    <th> Total Discount </th>
                                                    <td>{{$invoices->sum('discount') }}</td>
                                                </tr>
                                                <tr class="text-end">
                                                    <th>Current Bill:</th>
                                                    <td>{{$invoices->sum('total_amount') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Service Charges:</th>
                                                    <td>{{$invoices->sum('service_charges') }}</td>
                                                </tr>

                                                @if($sale->customers->credit > 0)
                                                <tr>
                                                    <th>Pending Payment:</th>
                                                    <td>{{$credit }}</td>
                                                </tr>
                                                @else
                                                <tr>
                                                    <th>Remaining Payment:</th>
                                                    <td>{{$sale->customers->debit }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <th> Cash:</th>
                                                    <td>{{$cash }}</td>
                                                </tr>
                                                @php
                                                $total_pending_amount=($invoices->sum('total_amount')+$credit)-$cash;
                                                @endphp
                                                <tr>
                                                    <th> Total pending Amount:</th>
                                                    <td> <strong> {{$total_pending_amount }} </strong></td>
                                                </tr>
                                        </div>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-5">

                                <div class="col-12">
                                    <form id="noteForm" class="note-form">
                                        @csrf
                                        <h4><strong>Note</strong></h4>
                                        <div class="form-group note">
                                            <textarea name="note" id="note" rows="3" class="form-control" placeholder="Enter your note here..."></textarea>
                                        </div>
                                        <div class="text-end mt-3 note_button">
                                            <button type="button" id="submitNote" class="btn btn-primary">
                                                Submit Note
                                            </button>
                                        </div>
                                    </form>
                                    <div id="displayNote" class="mt-3" style="display: none;">
                                        <h4><strong>Note:</strong></h4>
                                        <p id="noteText"></p>
                                    </div>
                                </div>



                            </div>

                            <button class="btn btn-primary" onclick="printContent()" id="print_invoice">
                                <i class="fas fa-print"></i> Print Invoice
                            </button>

                            <a class="btn btn-primary" href="{{ route('generate-pdf', ['id' => $sale->id]) }}" id="gen_pdf">
                                <i class="fas fa-file-pdf"></i> Generate PDF
                            </a>
                        </div>


                    </div>
                </div>
        </div>
        </section>

    </div>
    </div>
    @include('pages.script')
    <script>
        $(document).ready(function() {
            $('#submitNote').click(function() {
                let note = $('#note').val();
                let saleId = "{{ $sale->id }}";

                $.ajax({
                    url: "{{ route('sale.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: saleId,
                        note: note,
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#noteForm').hide();
                            $('#noteText').text(response.note);
                            $('#displayNote').show();
                        } else {
                            alert('Failed to update the note.');
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    },
                });
            });
        });

        function printContent() {
            window.print();
        }
    </script>
</body>

</html>