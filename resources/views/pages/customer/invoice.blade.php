<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('pages.css')

    <style>
        @media print {

            .content-wrapper,
            .invoice-box {
                display: block !important;
                background: white;
            }

            #print_invoice,
            #gen_pdf,
            .note_button,
            button,
            .note,
            .btn-primary {
                display: none !important;
            }
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .invoice-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.08);
            margin: 20px auto;
        }

        .invoice-logo {
            display: block;
            margin: 0 auto;
            border-radius: 50%;
            border: 2px solid #ddd;
        }

        .heading {
            font-size: 2rem;
            font-weight: 700;
            color: #343a40;
            margin-top: 15px;
        }

        .sub-heading {
            color: #6c757d;
            font-size: 1rem;
            margin-top: 5px;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .table thead th {
            background-color: rgb(213, 167, 99);
            color: black;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
        }

        .section-title {
            background: rgb(213, 167, 99);
            color: black;
            padding: 10px 15px;
            font-weight: bold;
            border-left: 5px solid #007bff;
            border-radius: 4px;
            margin: 30px 0 15px;
        }

        .btn-primary,
        .btn-danger {
            margin: 5px;
        }

        #displayNote {
            background: #e9f7ef;
            padding: 15px;
            border-left: 4px solid #28a745;
            border-radius: 5px;
        }

        @media (max-width: 767px) {
            .text-md-end {
                text-align: left !important;
                margin-top: 10px;
            }
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
                    <div class="row mt-4">
                        <div class="col-12 justify-content-end align-items-center d-flex">
                            <button class="btn btn-primary" onclick="history.back()">← Back</button>
                        </div>
                    </div>

                    <div class="invoice-box">
                        <div class="text-center">
                            <img src="{{ asset('assets/logo/logo.jpg') }}" alt="Logo" width="120" height="120" class="invoice-logo">
                            <h1 class="heading">Rana Electronics Invoice</h1>
                            <p class="sub-heading">Near ABL Bank, Fawara Chowk, Chanab Bazar, Kot Momin</p>
                        </div>

                        @php $mytime = Carbon\Carbon::now(); @endphp

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <p><strong>Date:</strong> {{ date('d-m-Y') }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p><strong>Invoice Number: </strong> RE-{{ $sale->id }}</p>
                                <p><strong>Chief Executive:</strong> USAMA RANA</p>
                                <p><strong>Mobile:</strong> 03007667440 | 03218991304</p>
                            </div>
                        </div>

                        <div class="section-title">Customer Details</div>
                        <p><strong>Name:</strong> {{ $sale->customers->name }}</p>
                        <p><strong>Mobile No:</strong> {{ $sale->customers->mobile_number }}</p>
                        <p><strong>Address:</strong> {{ $sale->customers->address }}</p>

                        <div class="section-title">Invoice Details</div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Item code</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Discount</th>
                                        <th>Service Charges</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $invoice->products->item_name  ?? ''}}</td>
                                        <td>{{ $invoice->products->item_code }}</td>
                                        <td>{{ $invoice->products->selling_price }}</td>
                                        <td>{{ $invoice->quantity }}</td>
                                        <td>{{ $invoice->discount }}</td>
                                        <td>{{ $invoice->service_charges }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="section-title">Payment Summary</div>
                        @php
                        $total_discount = $invoices->sum('discount');
                        $total_bill = $invoices->sum('total_amount');
                        $total_services = $invoices->sum('service_charges');
                        $cash = $sale->cash ?? 0;

                        // Amount remaining for *this invoice only*
                        $remaining_payment = $cash - $total_bill;
                        $total_pending_amount = $total_bill - $cash;

                        @endphp

                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>Total Discount:</th>
                                <td>{{ $total_discount }}</td>
                            </tr>
                            <tr>
                                <th>Current Bill:</th>
                                <td>{{ $total_bill }}</td>
                            </tr>
                            <tr>
                                <th>Total Service Charges:</th>
                                <td>{{ $total_services }}</td>
                            </tr>

                            @if($remaining_payment < 0)
                                <tr>
                                <th>Pending Payment:</th>
                                <td>{{ abs($remaining_payment) }}</td>
                                </tr>
                                @else
                                <tr>
                                    <th>Remaining Payment:</th>
                                    <td>{{ $remaining_payment }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Cash:</th>
                                    <td>{{ $cash }}</td>
                                </tr>


                                <tr>
                                    <th><strong>Total Pending Amount:</strong></th>
                                    <td><strong>{{ $total_pending_amount }}</strong></td>
                                </tr>
                        </table>

                        @if(empty($sale->note))
                        <form id="noteForm" class="note-form mt-4">
                            @csrf
                            <div class="section-title">Add Note</div>
                            <div class="form-group note">
                                <textarea name="note" id="note" rows="3" class="form-control" placeholder="Enter your note here..."></textarea>
                            </div>
                            <div class="text-end mt-3 note_button">
                                <button type="button" id="submitNote" class="btn btn-success">Submit Note</button>
                            </div>
                        </form>
                        @endif

                        <div id="displayNote" class="mt-3" @if(empty($sale->note)) style="display: none;" @endif>
                            <h5><strong>Note:</strong></h5>
                            <p id="noteText">
                                {!! !empty($sale->note) ? $sale->note : '' !!}
                            </p>
                        </div>


                        <div class="text-center mt-4">
                            <button class="btn btn-primary" onclick="printContent()" id="print_invoice">
                                <i class="fas fa-print"></i> Print Invoice
                            </button>

                            <a class="btn btn-danger" href="{{ route('generate-pdf', ['id' => $sale->id]) }}" id="gen_pdf">
                                <i class="fas fa-file-pdf"></i> Generate PDF
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @include('pages.script')

    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    @if(empty($sale->note))
    <script>
        let noteEditor;

        ClassicEditor
            .create(document.querySelector('#note'))
            .then(editor => {
                noteEditor = editor;
            })
            .catch(error => {
                console.error('There was a problem initializing CKEditor 5:', error);
            });

        $(document).ready(function() {
            $('#submitNote').click(function() {
                const note = noteEditor.getData();
                const saleId = "{{ $sale->id }}";

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
                            $('#noteText').html(response.note);
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
    @endif
</body>

</html>