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
            justify-content: space-between;  /* Align items to both sides */
            align-items: center;
        }

        .invoice-header h2 {
            margin: 0;  /* Remove margin from the header */
        }

        .invoice-header span {
            font-size: 14px;
            font-weight: 300;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        /* Table header styling */
        th {
            /* background-color: #007bff; */
            color: black;
            padding: 10px;
            text-align: left;
        }

        /* Table body styling */
        td {
            /* background-color: #f9f9f9; */
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        /* Total row styling */
        .total-row th,
        .total-row td {
            /* background-color: #e9ecef; */
            font-weight: bold;
        }

        .total-row td {
            text-align: right;
        }

        /* Styling for the container inside table */
        .table-container {
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <h2>Rana Electronics Invoice</h2>
            <span>{{ \Carbon\Carbon::now()->format('l, F j, Y g:i A') }}</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#Serial</th>
                        <th>Item Name</th>
                        <th>Item Price</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Service Charges</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$invoice->products->item_name }}</td>
                            <td>{{$invoice->products->selling_price }}</td>
                            <td>{{$invoice->quantity}}</td>
                            <td>{{$invoice->discount}}</td>
                            <td>{{$invoice->service_charges}}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <th>Total Discount</th>
                        <td colspan="5">{{$invoices->sum('discount') }}</td>
                    </tr>
                    <tr class="total-row">
                        <th>Total Service Charges</th>
                        <td colspan="5">{{$invoices->sum('service_charges') }}</td>
                    </tr>
                    <tr class="total-row">
                        <th>Total</th>
                        <td colspan="5" class="total-amount">{{$invoices->sum('total_amount') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>