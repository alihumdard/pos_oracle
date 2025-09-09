@extends('index')

@section('content')
<style>
    :root {
        --primary-color: #4361ee;
        --primary-hover: #3a56d4;
        --text-color: #2b2d42;
        --light-gray: #e9ecef;
        --border-radius: 8px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .form-container { background: white; border-radius: var(--border-radius); box-shadow: var(--box-shadow); padding: 2rem; margin-top: 2rem; border: 1px solid var(--light-gray); }
    /* Updated select2 styling for better consistency */
    .form-container .form-control,
    .form-container .select2-container--default .select2-selection--single {
        height: 45px !important;
        border-radius: var(--border-radius) !important;
        border: 1px solid var(--light-gray) !important;
        display: flex;
        align-items: center;
    }
    .form-container .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal !important; /* Use normal for flex alignment */
        padding-left: .75rem !important;
        padding-right: 20px; /* Ensure space for arrow */
    }
    .form-container .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(100% - 2px) !important; /* Full height within border */
        top: 1px !important; /* Center arrow */
        right: 5px !important;
    }
    .form-container .form-control:focus,
    .form-container .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25) !important;
    }
    .form-group label { font-weight: 500; margin-bottom: 0.5rem; color: var(--text-color); }
    .text-danger-small { font-size: 0.875em; color: #dc3545; }
    .btn-primary-submit { background-color: var(--primary-color); border-color: var(--primary-color); height: 45px; font-weight: 500; color: white; }
    .btn-primary-submit:hover { background-color: var(--primary-hover); border-color: var(--primary-hover); }

    .balance-info { font-size: 0.9em; margin-top: 5px; padding: 5px 0; }
    .balance-positive { color: green; font-weight: bold; }
    .balance-negative { color: red; font-weight: bold; }
    .balance-zero { color: #6c757d; }
</style>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-2xl p-8">
        <!-- Heading -->
        <h3 class="text-2xl font-bold text-center mb-6 text-primary-600">
            Record New Purchase
        </h3>

        <form id="purchaseForm" class="space-y-6">
            @csrf
            <!-- Supplier & Product -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="supplier_id" class="block text-sm font-semibold text-gray-700 mb-1">
                        Supplier <span class="text-red-500">*</span>
                    </label>
                    <select name="supplier_id" id="supplier_id"
                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" data-debit="{{ $supplier->debit }}" data-credit="{{ $supplier->credit }}">
                            {{ $supplier->supplier }} {{ $supplier->contact_person ? '(' . $supplier->contact_person . ')' : '' }}
                        </option>
                        @endforeach
                    </select>
                    <p id="supplier_balance_info" class="text-sm text-gray-500 mt-1"></p>
                    <small class="text-red-500 hidden" id="supplier_idError"></small>
                </div>

                <div>
                    <label for="product_id" class="block text-sm font-semibold text-gray-700 mb-1">
                        Product <span class="text-red-500">*</span>
                    </label>
                    <select name="product_id" id="product_id"
                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->item_name }} {{ $product->item_code ? '(Code: ' . $product->item_code . ')' : '' }}
                        </option>
                        @endforeach
                    </select>
                    <small class="text-red-500 hidden" id="product_idError"></small>
                </div>
            </div>

            <!-- Price, Stock & Quantity -->
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Product Original Price</label>
                    <input type="text" id="product_original_price_display"
                        class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 text-sm" readonly placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Current Stock</label>
                    <input type="text" id="product_current_qty_display"
                        class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 text-sm" readonly placeholder="0">
                </div>
                <div>
                    <label for="purchase_quantity" class="block text-sm font-semibold text-gray-700 mb-1">
                        Purchase Quantity <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="purchase_quantity" id="purchase_quantity" min="1"
                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter quantity">
                    <small class="text-red-500 hidden" id="purchase_quantityError"></small>
                </div>
            </div>

            <!-- Amount, Cash & Date -->
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Total Purchase Amount</label>
                    <input type="text" id="total_payable_display"
                        class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 text-sm" readonly placeholder="0.00">
                </div>
                <div>
                    <label for="cash_paid" class="block text-sm font-semibold text-gray-700 mb-1">
                        Cash Paid <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="cash_paid" id="cash_paid" min="0" value="0"
                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Enter cash paid">
                    <small class="text-red-500 hidden" id="cash_paidError"></small>
                </div>
                <div>
                    <label for="purchase_date" class="block text-sm font-semibold text-gray-700 mb-1">
                        Purchase Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="purchase_date" id="purchase_date"
                        value="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-primary-500 focus:border-primary-500">
                    <small class="text-red-500 hidden" id="purchase_dateError"></small>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Any notes for this purchase..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-center pt-4">
                <button type="submit"
                    class="bg-primary text-white font-semibold px-6 py-2.5 rounded-lg shadow transition duration-300">
                    <i class="fas fa-save mr-2"></i> Save Purchase
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@pushOnce('scripts')
<script>
$(document).ready(function() {
    $('.select2-single').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });

    let selectedProductOriginalPrice = 0;

    function calculateTotalPayable() {
        const quantity = parseFloat($('#purchase_quantity').val()) || 0;
        const totalPayable = quantity * selectedProductOriginalPrice;
        $('#total_payable_display').val(totalPayable.toFixed(2));
    }

    function displaySupplierBalance() {
        const selectedOption = $('#supplier_id').find('option:selected');
        const balanceInfoDiv = $('#supplier_balance_info');
        balanceInfoDiv.empty();

        if ($('#supplier_id').val() && selectedOption.length > 0 && selectedOption.val() !== "") {
            const debit = parseFloat(selectedOption.data('debit')) || 0;
            const credit = parseFloat(selectedOption.data('credit')) || 0;
            let balance = debit - credit;
            let balanceText = '';
            let balanceClass = 'balance-zero';

            if (balance > 0) {
                balanceText = `Current Balance: ${balance.toFixed(2)} (Supplier owes you)`;
                balanceClass = 'balance-positive';
            } else if (balance < 0) {
                balanceText = `Current Balance: ${Math.abs(balance).toFixed(2)} (You owe supplier)`;
                balanceClass = 'balance-negative';
            } else {
                balanceText = 'Current Balance: 0.00 (Settled)';
            }
            balanceInfoDiv.html(`<span class="${balanceClass}">${balanceText}</span>`);
        }
    }

    $('#supplier_id').on('change', function() {
        displaySupplierBalance();
    });

    $('#product_id').on('change', function() {
        const productId = $(this).val();
        selectedProductOriginalPrice = 0;
        $('#product_original_price_display').val('0.00');
        $('#product_current_qty_display').val('0');

        if (productId) {
            $('#submitPurchaseBtn').prop('disabled', true);
            // Ensure your route is defined correctly, the one from your provided routes is 'purchase.product.details'
            let productDetailsUrl = `{{ route('purchase.product.details', ['id' => ':id']) }}`;
            productDetailsUrl = productDetailsUrl.replace(':id', productId);

            $.ajax({
                url: productDetailsUrl,
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        selectedProductOriginalPrice = parseFloat(response.original_price) || 0;
                        $('#product_original_price_display').val(selectedProductOriginalPrice.toFixed(2));
                        $('#product_current_qty_display').val(response.current_qty || 0);
                    } else {
                        Swal.fire('Error', response.message || 'Could not fetch product details.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to fetch product details. Check network or console.', 'error');
                },
                complete: function() {
                    calculateTotalPayable();
                    $('#submitPurchaseBtn').prop('disabled', false);
                }
            });
        } else {
            calculateTotalPayable();
        }
    });

    $('#purchase_quantity').on('input change keyup', function() {
        calculateTotalPayable();
    });
    // Also calculate if cash_paid changes, though it doesn't affect total_payable_display, it's good practice
    // $('#cash_paid').on('input change keyup', function() { /* any logic related to cash paid display */ });


    $('#purchaseForm').submit(function(e) {
        e.preventDefault();
        $('#submitPurchaseBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        $('.text-danger-small').addClass('d-none').text('');
        const formData = $(this).serialize();

        $.ajax({
            url: "{{ route('purchase.store') }}",
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        timer: 2500,
                        showConfirmButton: false
                    });
                    $('#purchaseForm')[0].reset();
                    $('#supplier_id').val('').trigger('change');
                    $('#product_id').val('').trigger('change');
                    $('#product_original_price_display').val('0.00');
                    $('#product_current_qty_display').val('0');
                    $('#total_payable_display').val('0.00');
                    $('#cash_paid').val('0'); // Reset cash_paid field
                    $('#purchase_date').val("{{ date('Y-m-d') }}");
                    $('#supplier_balance_info').empty();

                    // For the dynamic supplier balance in the dropdown to update *without a page reload*,
                    // you'd need to update the data attributes of the selected supplier option here.
                    // This can be complex if the list is large. A page reload or re-fetching
                    // the supplier list on next form open is often simpler.
                    // Example of updating data attribute (if response.purchase.supplier contains updated debit/credit):
                    /*
                    if(response.purchase && response.purchase.supplier) {
                        let updatedSupplier = response.purchase.supplier;
                        $('#supplier_id option[value="' + updatedSupplier.id + '"]')
                            .data('debit', updatedSupplier.debit)
                            .data('credit', updatedSupplier.credit);
                    }
                    */
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessages = 'Please correct the following errors:<br><ul>';
                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            $('#' + key + 'Error').text(errors[key][0]).removeClass('d-none');
                            errorMessages += `<li>${errors[key][0]}</li>`;
                        }
                    }
                    errorMessages += '</ul>';
                    Swal.fire({ title: "Validation Error", html: errorMessages, icon: "error" });
                } else {
                     Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON.message) || 'An error occurred.', 'error');
                }
            },
            complete: function() {
                $('#submitPurchaseBtn').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save Purchase');
            }
        });
    });

    // Initial calls
    displaySupplierBalance();
    calculateTotalPayable();
});
</script>
@endPushOnce
