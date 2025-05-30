<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Source Sans Pro', sans-serif;

            padding: 20px;
        }

        .container {
            max-width: 1000px;
            height: 50vh;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            /* Align items to both sides */
            align-items: center;
        }

        .invoice-header h2 {
            margin: 0;
            /* Remove margin from the header */
        }

        .invoice-header span {
            font-size: 14px;
            font-weight: 300;
        }

        /* Custom styles for the invoice */
        .invoice {
            padding: 20px;
            margin-bottom: 20px;
            /* border: 1px solid #ddd; */
            /* border-radius: 5px; */
            /* background-color: #fff; */
            font-family: Arial, sans-serif;
        }

        .invoice-logo {
            display: block;
            margin: 0 auto;
            border-radius: 5px;
        }

        .heading {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-top: 15px;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .customer-info {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .customer-detail p {
            margin: 5px 0;
            font-size: 14px;
            /* color: #555; */
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        /* .table th {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #333;
        } */

        /* .table-striped tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        } */

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            text-transform: uppercase;
            margin-right: 10px;
        }

        button a {
            color: #fff;
            text-decoration: none;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Invoice Header -->
        <div class="invoice-header">

            <span>{{ \Carbon\Carbon::now()->format('l, F j, Y g:i A') }}</span>
        </div>
        <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row text-center">
                <div class="col-12 mt-5 mb-1">
                    <img src="{{ base_path('public/assets/logo/logo.jpg')  }}" alt="not image" width="150" height="150" class="invoice-logo">
                    <h1 class="heading mt-3">Rana Electronics Invoice</h1>

                </div>
            </div>

            <div class="row">
                <div class="col-6 text-start">
                    <h5 class="mb-0">Chief Executive</h5>
                    <h3 class="mt-1">USAMA RANA</h3>
                </div>
                <div class="col-6 text-end">
                    <h4 class="mb-0">Invoice Number : RE-{{ $sale->id }}</h4>
                    <h4 class="mb-0">Mobile Number</h4>
                    <h4 class="mt-1">03007667440</h4>
                    <h4 class="mt-2">03218991304</h4>
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
                <div class="col-6">
                    <!-- <p class="lead">Amount Due 2/22/2014</p> -->

                    <div class="table-responsive">
                        <table class="table">

                            <tr>
                                <th> Total Discount </th>
                                <td>{{$invoices->sum('discount') }}</td>
                            </tr>
                            <tr>
                                <th>Total Service Charges:</th>
                                <td>{{$invoices->sum('service_charges') }}</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>{{$invoices->sum('total_amount') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <div id="displayNote" class="mt-3">
                <h4><strong>Note:</strong></h4>

               <p id="noteText">
               {!! !empty($sale->note) ? $sale->note : '' !!}
            
            </p>
            </div>
        </div>
    </div>

</body>

</html>