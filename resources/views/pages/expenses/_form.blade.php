{{-- resources/views/pages/expenses/_form.blade.php --}}

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please correct the errors below:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="form-group col-md-6 mb-3">
        <label for="expense_date">Expense Date <span class="text-danger">*</span></label>
        <input type="date" name="expense_date" id="expense_date" class="form-control"
               value="{{ old('expense_date', (isset($expense) && $expense->expense_date) ? $expense->expense_date->format('Y-m-d') : date('Y-m-d')) }}" required>
        @error('expense_date')
            <div class="text-danger-small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group col-md-6 mb-3">
        <label for="amount">Amount <span class="text-danger">*</span></label>
        <input type="number" name="amount" id="amount" class="form-control" placeholder="0.00" step="0.01" min="0.01"
               value="{{ old('amount', isset($expense) ? $expense->amount : '') }}" required>
        @error('amount')
            <div class="text-danger-small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-group mb-3">
    <label for="expense_category_id">Expense Category</label>
    <select name="expense_category_id" id="expense_category_id" class="form-control select2-basic" style="width: 100%;">
        <option value="">Select Category (Optional)</option>
        {{-- Ensure $categories is always passed to this partial from both create and edit views --}}
        @if(isset($categories))
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('expense_category_id', isset($expense) ? $expense->expense_category_id : null) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        @endif
    </select>
    @error('expense_category_id')
        <div class="text-danger-small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="form-group mb-3">
    <label for="description">Description <span class="text-danger">*</span></label>
    <textarea name="description" id="description" class="form-control" rows="3"
              placeholder="Describe the expense" required>{{ old('description', isset($expense) ? $expense->description : '') }}</textarea>
    @error('description')
        <div class="text-danger-small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="form-group col-md-6 mb-3">
        <label for="paid_to">Paid To (Optional)</label>
        <input type="text" name="paid_to" id="paid_to" class="form-control"
               placeholder="e.g., Vendor Name, Utility Co." value="{{ old('paid_to', isset($expense) ? $expense->paid_to : '') }}">
        @error('paid_to')
            <div class="text-danger-small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group col-md-6 mb-3">
        <label for="reference_number">Reference # (Optional)</label>
        <input type="text" name="reference_number" id="reference_number" class="form-control"
               placeholder="e.g., Invoice #, Receipt #" value="{{ old('reference_number', isset($expense) ? $expense->reference_number : '') }}">
        @error('reference_number')
            <div class="text-danger-small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>