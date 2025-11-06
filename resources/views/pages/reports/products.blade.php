@extends('index')

@section('title', 'Product Reports')

@section('content')
<div class="container-fluid pt-16 sm:pt-6">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product Reports</h1>
        {{-- Add filters here if needed --}}
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Products by Revenue</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports['topProductsByRevenue'] as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td class="text-end">{{ number_format($product['revenue'], 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center">No product data available.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Products by Quantity Sold</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th class="text-end">Quantity Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- CORRECTED KEY --}}
                                @forelse ($reports['topProductsByQtySold'] as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td class="text-end">{{ $product['qtySold'] }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center">No product data available.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Products by Sales Profit</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th class="text-end">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- CORRECTED KEY --}}
                                @forelse ($reports['topProductsBySalesProfit'] as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td class="text-end">{{ number_format($product['profit'], 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center">No product data available.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Category Sales Performance</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="categoryProductReportTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th class="text-end">Total Revenue</th>
                            <th class="text-end">Total Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports['categoriesByRevenue'] as $category)
                        <tr>
                            <td>{{ $category['name'] }}</td>
                            <td class="text-end">{{ number_format($category['revenue'], 2) }}</td>
                            <td class="text-end">{{ number_format($category['profit'], 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">No category sales data available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script>
$(document).ready(function() {
    $('#categoryProductReportTable').DataTable();
});
</script> --}}
@endpush