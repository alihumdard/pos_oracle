@extends('index')

@section('title', 'Customer Reports') {{-- Assuming your index.blade.php has a @yield('title') --}}

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="text-3xl font-bold mb-0 pt-10 pb-3 text-gray-800">Customer Reports</h1>
    </div>

    <div class="row">
        <div class="mb-4">
            <div class="card shadow-lg border-0 rounded-3 h-100">
                <div class="card-header bg-gradient-primary text-white rounded-top-3 py-3 d-flex align-items-center">
                    <i class="bi bi-people-fill fs-4 me-2"></i>
                    <h6 class="m-0 fw-bold">Top 5 Customers by Total Spending</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th class="text-end">Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports['topCustomersBySpending'] as $index => $customer)
                                <tr>
                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ $customer['name'] }}</td>
                                    <td class="text-end text-success fw-semibold">
                                        {{ number_format($customer['totalSpent'], 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        No customer spending data available.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="card shadow-lg border-0 rounded-3 h-100">
                <div class="card-header bg-gradient-primary text-white rounded-top-3 py-3 d-flex align-items-center">
                    <i class="bi bi-cash-coin fs-4 me-2"></i>
                    <h6 class="m-0 fw-bold">All Customer Balances</h6>
                </div>
              <div class="card-body">
  <!-- ✅ Scroll wrapper yahan lagao -->
  <div class="overflow-x-auto">
    <table class="table table-bordered table-hover align-middle min-w-full" id="example1">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Customer Name</th>
          <th class="text-end">Debit</th>
          <th class="text-end">Credit</th>
          <th class="text-end">Balance</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($reports['customerBalances'] as $customer)
        <tr>
          <td class="fw-semibold">{{ $customer['id'] }}</td>
          <td>{{ $customer['name'] }}</td>
          <td class="text-end text-danger fw-semibold">
            {{ number_format($customer['debit'], 2) }}
          </td>
          <td class="text-end text-primary fw-semibold">
            {{ number_format($customer['credit'], 2) }}
          </td>
          <td
            class="text-end fw-bold {{ $customer['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
            {{ number_format($customer['balance'], 2) }}
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted">
            No customer balance data available.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

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
            responsive: false,
            "columnDefs": [{
                    "orderable": false,
                    "targets": 3
                } // Disable sorting on 'Actions' column
            ]
        });
    });
</script>
@endpush