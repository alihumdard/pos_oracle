<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    @media print {

        .download_pdf,
        .card-header {
            display: none !important;
            ;
        }
    }

    /* General Card Styles */
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Card Header */
    .card-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .card-header h2 {
        font-size: 24px;
        color: #333;
    }

    /* Table Styles */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #007bff;
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Button Styles */
    .text-right {
        margin: 15px 0;
        text-align: right;
    }

    .btn-info {
        background-color: #17a2b8;
        border: none;
        padding: 10px 20px;
        color: white;
        border-radius: 5px;
        font-size: 16px;
    }

    .btn-info:hover {
        background-color: #138496;
    }

    /* PDF Icon */
    .fa-file-pdf-o {
        margin-right: 8px;
    }
</style>

<body>
    <div class="row mt-4">
    <div class="col-12 justify-content-end align-items-center d-flex">
        <button class="btn btn-primary" onclick="history.back()">← Back</button>
    </div>
</div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center mt-3">All Products Detail</h2>
                </div>

                <div class="text-right download_pdf">
                    <a href="{{ url('generate/product') }}" class="btn btn-info">
                        <i class="fa fa-file-pdf-o download_pdf"></i> Download PDF
                    </a>
                </div>

                <div class="card-body">
                    <table class="table table-hover w-100" id="example1">
                        <thead class="bg-primary">
                            <tr>
                                <th>#Sr.No</th>
                                <th>Item Name</th>
                                <th>Item Code</th>
                                <th>Selling Price</th>
                                <th>Original Price</th>
                                <th>Quantity</th>
                                <th>Total Original Price</th>
                                <th>Total Selling Price</th>
                            </tr>
                        </thead>
                        <tbody id="tableHolder">
                            @php
                            $total_original_price = 0;
                            $total_selling_price = 0;
                            @endphp
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->item_name }}</td>
                                <td>{{ $product->item_code }}</td>
                                <td>{{ $product->selling_price }}</td>
                                <td>{{ $product->original_price }}</td>
                                <td>{{ $product->qty }}</td>
                                <td>{{ ($product->qty) * ($product->original_price) }}</td>
                                <td>{{ ($product->qty) * ($product->selling_price) }}</td>
                                @php
                                $total_original_price += ($product->qty * $product->original_price);
                                $total_selling_price += ($product->qty * $product->selling_price);
                                @endphp
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="5"></td>
                                <td><strong>{{ $products->sum('qty') }}</strong></td>
                                <td><strong>{{ $total_original_price }}</strong></td>
                                <td><strong>{{ $total_selling_price }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>