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

            /* #tittle {
        display: none !important; 
      } */
            #print_invoice {
                display: none;
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

                    <div class="row">
                        <div class="col-12">

                            <div class="invoice p-3 mb-3">
                                <!-- title row -->
                                <div class="row">
                                    <div class="col-12 text-center mt-5 mb-5">
                                        <h2>
                                            Rana Electronics Invoice
                                            <!-- <small class="float-right">Date: 2/10/2014</small> -->
                                        </h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        
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


                                <button onclick="printContent()" id="print_invoice">Print Invoice</button>
                                <button> <a href="{{route('generate-pdf', ['id' => $sale->id]) }}">Generate Pdf</a> </button>
                            
                            </div>


                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <script>
        function printContent() {
            window.print();
        }
    </script>
</body>

</html>