@extends('index')

@section('title', 'Product Reports')

@section('content')
<div class="container-fluid py-10">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 fw-bold text-dark">Product Reports</h1>
    </div>

    <div class="row g-4 mb-10">
        <!-- Top Products by Revenue -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-lg border-0 rounded-3 h-100">
                <div class="card-header bg-gradient-primary text-white rounded-top-3 py-3 d-flex align-items-center">
                    <i class="bi bi-currency-dollar fs-4 me-2"></i>
                    <h6 class="m-0 fw-bold">Top Products by Revenue</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports['topProductsByRevenue'] as $index => $product)
                                <tr>
                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td class="text-end text-success fw-semibold">
                                        {{ number_format($product['revenue'], 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products by Quantity -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-lg border-0 rounded-3 h-100">
                <div class="card-header bg-gradient-primary text-white rounded-top-3 py-3 d-flex align-items-center">
                    <i class="bi bi-box-seam fs-4 me-2"></i>
                    <h6 class="m-0 fw-bold">Top Products by Quantity Sold</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="text-end">Qty Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports['topProductsByQtySold'] as $index => $product)
                                <tr>
                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td class="text-end text-primary fw-semibold">
                                        {{ $product['qtySold'] }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products by Profit -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-lg border-0 rounded-3 h-100">
                <div class="card-header bg-gradient-primary text-white rounded-top-3 py-3 d-flex align-items-center">
                    <i class="bi bi-graph-up-arrow fs-4 me-2"></i>
                    <h6 class="m-0 fw-bold">Top Products by Profit</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="text-end">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports['topProductsBySalesProfit'] as $index => $product)
                                <tr>
                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td class="text-end text-danger fw-semibold">
                                        {{ number_format($product['profit'], 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="card shadow-lg border-0 rounded-3 mb-4">
        <div class="card-header bg-gradient-primary text-white rounded-top-3 py-3 d-flex align-items-center">
            <i class="bi bi-bar-chart-line fs-4 me-2"></i>
            <h6 class="m-0 fw-bold">Category Sales Performance</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle" id="example1" width="100%">
                    <thead class="table-light">
                        <tr>
                            <th>Category Name</th>
                            <th class="text-end">Total Revenue</th>
                            <th class="text-end">Total Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports['categoriesByRevenue'] as $category)
                        <tr>
                            <td class="fw-semibold">{{ $category['name'] }}</td>
                            <td class="text-end text-success fw-bold">
                                {{ number_format($category['revenue'], 2) }}
                            </td>
                            <td class="text-end text-danger fw-bold">
                                {{ number_format($category['profit'], 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No category sales data available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
 $(document).ready(function() {
    $('#example1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ],
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        responsive: true
    });
});

</script>
@endpush