  <!-- Table displaying transaction details -->
  <table class="table table-bordered">
      <thead>
          <tr>
              <th>Item Name</th>
              <th>Item Code</th>
              <th>Quantity</th>
              <th>Discount</th>
              <th>Service Charges</th>
              <th>Total Amount</th>
          </tr>
      </thead>
      <tbody>
          @foreach($transactions as $transaction)
          <tr>
              <td>{{ $transaction->products->item_name ?? 'N/A' }}</td>
              <td>{{ $transaction->products->item_code ?? 'N/A' }}</td>
              <td>{{ $transaction->quantity ?? 'N/A' }}</td>
              <td>{{ $transaction->discount ?? 'N/A' }}</td>
              <td>{{ $transaction->service_charges ?? 'N/A' }}</td>
              <td>{{ $transaction->total_amount ?? 'N/A' }}</td>
          </tr>
          @endforeach
      </tbody>
  </table>