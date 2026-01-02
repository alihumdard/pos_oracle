<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products Detail</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .download_pdf, .card-header, .back-btn-container {
                display: none !important;
            }
            .card { border: none !important; box-shadow: none !important; }
            .table th { background-color: #007bff !important; color: white !important; -webkit-print-color-adjust: exact; }
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .table {
            width: 100%;
            margin: 20px 0;
        }

        /* Standardize Alignment */
        .table th, .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        /* Align text columns left, number columns right */
        .text-column { text-align: left; }
        .number-column { text-align: right; font-variant-numeric: tabular-nums; }
        .center-column { text-align: center; }

        .table thead th {
            background-color: #007bff;
            color: white;
            border: none;
        }

        /* Totals Row Styling */
        .total-row {
            background-color: #eee;
            font-weight: bold;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
        }

        .download-container {
            padding: 15px 20px 0 0;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row mt-4 back-btn-container">
            <div class="col-12 text-right">
                <button class="btn btn-secondary" onclick="history.back()">← Back</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center mb-0">All Products Detail</h2>
                    </div>

                    <div class="text-right download-container download_pdf">
                        <a href="{{ url('generate/product') }}" class="btn btn-info">
                            <i class="fa fa-file-pdf-o"></i> Download PDF
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="example1">
                                <thead>
                                    <tr>
                                        <th class="center-column">#Sr.No</th>
                                        <th class="text-column">Item Name</th>
                                        <th class="text-column">Item Code</th>
                                        <th class="number-column">Selling Price</th>
                                        <th class="number-column">Original Price</th>
                                        <th class="number-column">Qty</th>
                                        <th class="number-column">Total Original</th>
                                        <th class="number-column">Total Selling</th>
                                    </tr>
                                </thead>
                                <tbody id="tableHolder">
                                    @php
                                        $total_original_price = 0;
                                        $total_selling_price = 0;
                                    @endphp
                                    
                                    @foreach($products as $product)
                                    <tr>
                                        <td class="center-column">{{ $loop->iteration }}</td>
                                        <td class="text-column">{{ $product->item_name }}</td>
                                        <td class="text-column">{{ $product->item_code }}</td>
                                        <td class="number-column">{{ number_format($product->selling_price, 2) }}</td>
                                        <td class="number-column">{{ number_format($product->original_price, 2) }}</td>
                                        <td class="number-column">{{ $product->qty }}</td>
                                        <td class="number-column">{{ number_format($product->qty * $product->original_price, 2) }}</td>
                                        <td class="number-column">{{ number_format($product->qty * $product->selling_price, 2) }}</td>
                                        
                                        @php
                                            $total_original_price += ($product->qty * $product->original_price);
                                            $total_selling_price += ($product->qty * $product->selling_price);
                                        @endphp
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td colspan="5" class="text-right">TOTAL</td>
                                        <td class="number-column">{{ $products->sum('qty') }}</td>
                                        <td class="number-column">{{ number_format($total_original_price, 2) }}</td>
                                        <td class="number-column">{{ number_format($total_selling_price, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>