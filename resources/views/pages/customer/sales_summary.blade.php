<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('pages.css')

    <style>
        @media print {
            .content-wrapper, .invoice-box {
                display: block !important;
                background: white;
            }
            #print_invoice, .btn-primary {
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

        .table th, .table td {
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
                <div class="invoice-box">
                    <div class="text-center">
                        <img src="{{ asset('assets/logo/logo.jpg') }}" alt="Logo" width="120" height="120" class="invoice-logo">
                        <h1 class="heading">Rana Electronics</h1>
                        <p class="sub-heading">Customer Sales Summary</p>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p><strong>Date:</strong> {{ date('d-m-Y') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Chief Executive:</strong> USAMA RANA</p>
                            <p><strong>Mobile:</strong> 03007667440 | 03218991304</p>
                        </div>
                    </div>
                    <div class="section-title">Customer Details</div>
                    <p><strong>Name:</strong> {{ $customer->name }}</p>
                    <p><strong>Mobile:</strong> {{ $customer->mobile_number }}</p>
                    <p><strong>Address:</strong> {{ $customer->address }}</p>

                    <div class="section-title">Summary</div>
                    <ul>
                        <li><strong>Total Sales:</strong> {{ $summary['total_sales'] }}</li>
                        <li><strong>Total Amount:</strong> {{ number_format($summary['total_amount'], 2) }}</li>
                        <li><strong>Total Cash:</strong> {{ number_format($summary['total_cash'], 2) }}</li>
                        <li><strong>Total Discount:</strong> {{ number_format($summary['total_discount'], 2) }}</li>
                        <li><strong>Remaining Credit:</strong> {{ number_format($summary['credit'], 2) }}</li>
                        <li><strong>Remaining Debit:</strong> {{ number_format($summary['debit'], 2) }}</li>
                    </ul>

                    <div class="section-title">All Sales</div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sale ID</th>
                                    <th>Total Amount</th>
                                    <th>Cash</th>
                                    <th>Discount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->sales as $index => $sale)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $sale->id }}</td>
                                        <td>{{ number_format($sale->total_amount, 2) }}</td>
                                        <td>{{ number_format($sale->cash, 2) }}</td>
                                        <td>{{ number_format($sale->total_discount, 2) }}</td>
                                        <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class=" mt-4">
                        <button class="btn btn-primary" onclick="window.print()" id="print_invoice">
                            <i class="fas fa-print"></i> Print Summary
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@include('pages.script')
</body>
</html>
