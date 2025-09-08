@extends('index')

@section('title', 'Sales Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="flex items-center justify-between py-10">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-chart-line text-blue-600 mr-2"></i> Sales Reports
        </h1>
    </div>

    <!-- Filter Form -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden mb-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 py-3">
            <h5 class="text-white font-semibold flex items-center">
                <i class="fas fa-filter mr-2"></i> Filter Sales Data
            </h5>
        </div>

        <!-- Body -->
        <div class="p-6 bg-gray-50">
            <form action="{{ route('reports.sales') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

                    <!-- From Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-600 mb-1">From Date</label>
                        <input type="date" id="start_date" name="start_date"
                            value="{{ $reports['filter_start_date'] ?? request('start_date') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 text-sm p-2.5">
                    </div>

                    <!-- To Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-600 mb-1">To Date</label>
                        <input type="date" id="end_date" name="end_date"
                            value="{{ $reports['filter_end_date'] ?? request('end_date') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 text-sm p-2.5">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2 md:justify-end">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                            <i class="fas fa-filter mr-2"></i> Apply
                        </button>
                        <a href="{{ route('reports.sales') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-lg shadow-sm transition">
                            <i class="fas fa-times mr-2"></i> Clear
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>


<div class="bg-white shadow-lg rounded-xl overflow-hidden mb-6">
  <!-- Header -->
  <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 py-3">
    <h6 class="text-white font-semibold text-lg flex items-center">
      <i class="fas fa-chart-bar mr-2"></i> Sales Over Time (Filtered)
    </h6>
  </div>

  <!-- Body -->
  <div class="p-6">
    <div class="overflow-x-auto rounded-lg">
      <table class="min-w-full text-sm text-gray-700 border border-gray-200 rounded-lg shadow-sm">
        <thead class="bg-blue-50 text-blue-700 text-left text-xs uppercase tracking-wider">
          <tr>
            <th class="px-4 py-3 border-b">Date</th>
            <th class="px-4 py-3 border-b">Revenue</th>
            <th class="px-4 py-3 border-b">Profit</th>
            <th class="px-4 py-3 border-b">No. of Sales</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse ($reports['salesOverTime'] as $saleData)
          <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-3">{{ $saleData['date'] }}</td>
            <td class="px-4 py-3 font-medium text-green-600">
              ${{ number_format($saleData['revenue'], 2) }}
            </td>
            <td class="px-4 py-3 font-medium text-indigo-600">
              ${{ number_format($saleData['profit'], 2) }}
            </td>
            <td class="px-4 py-3 text-center">{{ $saleData['sales_count'] ?? 0 }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-4 py-6 text-center text-gray-500 italic">
              No sales data available for the selected period.
            </td>
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