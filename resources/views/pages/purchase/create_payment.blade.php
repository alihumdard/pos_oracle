@extends('index')
@section('content')

<div class="container-fluid pt-16 sm:pt-6">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Supplier Payment</h3>
                </div>
                
                <form action="{{ route('payment.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        
                        <div class="form-group mb-3">
                            <label for="supplier_id">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-control select2-filter" style="width: 100%;" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="payment_date">Payment Date</label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount">Amount Paid</label>
                            <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount" step="0.01" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="notes">Notes (Optional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="e.g., Bank Transfer, Cash Payment"></textarea>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('purchase.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom">Save Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@pushOnce('scripts')
<script>
    // Select2 initialize karein
    $(document).ready(function() {
        $('#supplier_id').select2({
            placeholder: "Select a supplier",
            width: '100%'
        });
    });
</script>
@endpushOnce