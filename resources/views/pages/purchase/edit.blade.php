@extends('index')

@section('content')
<style>
    /* You can re-use the styles from your index, or link to a central CSS file */
    :root {
        --primary-color: #4361ee;
        --light-gray: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        --text-color: #2b2d42;
    }
    .card { border: none; border-radius: var(--border-radius); box-shadow: var(--box-shadow); overflow: hidden; }
    .card-header { background-color: white; border-bottom: 1px solid var(--light-gray); padding: 1.25rem 1.5rem; }
    .card-title { font-weight: 600; color: var(--text-color); margin-bottom: 0; }
    .form-label { font-weight: 500; }
    .btn-primary-custom { background-color: var(--primary-color); border-color: var(--primary-color); color: white; }
    .btn-primary-custom:hover { background-color: #3a56d4; border-color: #3a56d4; }
    .select2-container--default .select2-selection--single { height: 40px !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: calc(40px - 2px - (.375rem * 2)) !important; padding-left: .75rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: calc(40px - 2px) !important; }
</style>

<div class="container-fluid mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Purchase</h3>
                </div>
                
                {{-- Display validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger m-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger m-3">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card-body">
                    {{-- This form submits to the 'update' route --}}
                    <form action="{{ route('purchase.update', $purchase->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- <-- IMPORTANT: Tells Laravel this is an UPDATE --}}

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="purchase_date" name="purchase_date" 
                                       value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                                <select class="form-control select2-form" id="supplier_id" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                            {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->supplier }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                                <select class="form-control select2-form" id="product_id" name="product_id" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                            {{ old('product_id', $purchase->product_id) == $product->id ? 'selected' : '' }}>
                                            {{ $product->item_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="purchase_quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="purchase_quantity" name="purchase_quantity" 
                                       value="{{ old('purchase_quantity', $purchase->quantity) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="purchase_price" class="form-label">Unit Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" 
                                       value="{{ old('purchase_price', $purchase->unit_price) }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="cash_paid" class="form-label">Cash Paid (This Purchase)</label>
                                <input type="number" step="0.01" class="form-control" id="cash_paid" name="cash_paid"
                                       value="{{ old('cash_paid', $purchase->cash_paid_at_purchase) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="new_selling_price" class="form-label">New Selling Price (Optional)</label>
                                <input type="number" step="0.01" class="form-control" id="new_selling_price" name="new_selling_price"
                                       placeholder="Current: {{ $purchase->product->selling_price ?? 'N/A' }}">
                                <small class="form-text text-muted">Leave blank to keep the current selling price.</small>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $purchase->notes) }}</textarea>
                            </div>

                            <div class="col-12 text-end">
                                <a href="{{ route('purchase.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="fas fa-save me-2"></i>Update Purchase
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@pushOnce('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for the form dropdowns
    $('.select2-form').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endPushOnce