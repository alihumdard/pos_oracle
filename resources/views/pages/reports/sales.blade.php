@extends('index')

@section('title', 'Sales Reports')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sales Reports</h1>
    </div>

    {{-- Filter Form for Sales Report --}}
    <div class="filter-container mb-4 card card-body shadow-sm">
        <form action="{{ route('reports.sales') }}" method="GET"> {{-- Make sure this route name is correct --}}
            <h6 class="mb-3">Filter Sales Data</h6>
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="start_date" class="form-label">From Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ $reports['filter_start_date'] ?? request('start_date') }}">
                </div>
                <div class="col-md-5">
                    <label for="end_date" class="form-label">To Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ $reports['filter_end_date'] ?? request('end_date') }}">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary btn-sm mt-3"><i class="fas fa-filter me-1"></i>Filter</button>
                    <a href="{{ route('reports.sales') }}" class="btn btn-secondary btn-sm mt-3"><i class="fas fa-times me-1"></i>Clear</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sales Over Time (Filtered)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="salesOverTimeTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Revenue</th>
                            <th>Profit</th>
                            <th>No. of Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports['salesOverTime'] as $saleData)
                        <tr>
                            <td>{{ $saleData['date'] }}</td>
                            <td>{{ number_format($saleData['revenue'], 2) }}</td>
                            <td>{{ number_format($saleData['profit'], 2) }}</td>
                            <td>{{ $saleData['sales_count'] ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No sales data available for the selected period.</td>
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
{{-- <script>
$(document).ready(function() {
    $('#salesOverTimeTable').DataTable();
});
</script> --}}
@endpush