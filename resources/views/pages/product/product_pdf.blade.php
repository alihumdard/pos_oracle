<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <style>
        /* PDF Page Setup */
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        /* Standard CSS for the Back Button Container (No Flexbox) */
        .back-nav {
            width: 100%;
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-back {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        /* PRINT RULES */
        @media print {
            /* If you want the back button HIDDEN on the actual PDF file, keep this: */
            .back-nav {
                display: none !important;
            }
            
            /* Ensure the table fits the page */
            .card { border: none !important; }
        }

        /* Table Styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bg-total { background-color: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>

   

    <div class="card">
        <h2 style="text-align: center;">All Products Detail</h2>
        
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Item Name</th>
                    <th>Item Code</th>
                    <th class="text-right">Selling Price</th>
                    <th class="text-right">Original Price</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Total Original</th>
                    <th class="text-right">Total Selling</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_original_price = 0;
                    $total_selling_price = 0;
                @endphp
                @foreach($products as $product)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $product->item_name }}</td>
                    <td>{{ $product->item_code }}</td>
                    <td class="text-right">{{ number_format($product->selling_price, 2) }}</td>
                    <td class="text-right">{{ number_format($product->original_price, 2) }}</td>
                    <td class="text-center">{{ $product->qty }}</td>
                    <td class="text-right">{{ number_format($product->qty * $product->original_price, 2) }}</td>
                    <td class="text-right">{{ number_format($product->qty * $product->selling_price, 2) }}</td>
                    @php
                        $total_original_price += ($product->qty * $product->original_price);
                        $total_selling_price += ($product->qty * $product->selling_price);
                    @endphp
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-total">
                    <td colspan="5" class="text-right">Totals:</td>
                    <td class="text-center">{{ $products->sum('qty') }}</td>
                    <td class="text-right">{{ number_format($total_original_price, 2) }}</td>
                    <td class="text-right">{{ number_format($total_selling_price, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>