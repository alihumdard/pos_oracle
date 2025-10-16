@extends('index')

@section('content')
<style>
    /* YOUR CSS REMAINS EXACTLY THE SAME - NO CHANGES NEEDED HERE */
    :root {
        --primary-color: #4361ee;
        --primary-hover: #3a56d4;
        --text-color: #2b2d42;
        --label-color: #495057;
        --light-gray: #dee2e6;
        --very-light-gray: #f8f9fa;
        --border-radius: 8px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
        --input-height: 48px;
    }

    body {
        background-color: #f4f7f9;
    }

    .form-container {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 2.5rem;
        margin-top: 2rem;
        border: 1px solid #e9ecef;
    }

    .input-group-with-icon {
        position: relative;
    }

    .input-group-with-icon .input-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #adb5bd;
        transition: color 0.2s ease-in-out;
    }

    .input-group-with-icon .form-control,
    .input-group-with-icon .select2-container--default .select2-selection--single {
        padding-left: 45px !important;
    }

    .input-group-with-icon .form-control:focus~.input-icon,
    .input-group-with-icon .select2-container--open~.input-icon {
        color: var(--primary-color);
    }

    .form-container .form-control,
    .form-container .select2-container--default .select2-selection--single {
        height: var(--input-height) !important;
        border-radius: var(--border-radius) !important;
        border: 1px solid var(--light-gray) !important;
        background-color: var(--very-light-gray);
        display: flex;
        align-items: center;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-container .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal !important;
        color: var(--text-color);
    }

    .form-container .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(100% - 2px) !important;
        top: 1px !important;
        right: 8px !important;
    }

    .form-container .form-control:focus,
    .form-container .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--primary-color) !important;
        background-color: #fff;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.2) !important;
    }

    .form-control:read-only,
    .form-control[readonly] {
        background-color: #e9ecef !important;
        cursor: not-allowed;
        opacity: 0.8;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--label-color);
        font-size: 0.9rem;
    }

    .text-danger-small {
        font-size: 0.875em;
        color: #dc3545;
    }

    .btn-primary-submit {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        height: 50px;
        font-weight: 600;
        color: white;
        border-radius: var(--border-radius);
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
    }

    .btn-primary-submit:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(67, 97, 238, 0.25);
    }

    .balance-info {
        font-size: 0.9em;
        margin-top: 8px;
        padding: 5px 0;
    }

    .balance-positive {
        color: #198754;
        font-weight: bold;
    }

    .balance-negative {
        color: #dc3545;
        font-weight: bold;
    }

    .balance-zero {
        color: #6c757d;
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
        border-bottom: none;
    }

    .modal-header .modal-title {
        font-weight: 600;
    }

    .modal-header .close {
        color: white;
        opacity: 0.9;
        text-shadow: none;
    }

    .modal-header .close:hover {
        opacity: 1;
    }
</style>

{{-- Modal for adding a new product --}}
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    {{-- MODAL CONTENT IS UNCHANGED --}}
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 col-md-12 mx-auto">
            <div class="form-container">
                <div class="text-center mb-4">
                    <h2 class="mb-2" style="color: var(--primary-color); font-weight: 700;">Record New Purchase</h2>
                    <p class="text-muted">Fill out the details below to add a new purchase record.</p>
                </div>
                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-primary-submit" id="addCategoryBtn">
                        <i class="fa fa-plus me-2"></i>Add New Product
                    </button>
                </div>

                <form id="purchaseForm">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6 mb-4">
                            <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                            <div class="input-group-with-icon">
                                <i class="fa-solid fa-user-tie input-icon"></i>
                                <select name="supplier_id" id="supplier_id" class="form-control select2-single" style="width: 100%;">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" data-debit="{{ $supplier->debit }}" data-credit="{{ $supplier->credit }}">
                                        {{ $supplier->supplier }} {{ $supplier->contact_person ? '(' . $supplier->contact_person . ')' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="supplier_balance_info" class="balance-info"></div>
                            <small class="text-danger-small d-none" id="supplier_idError"></small>
                        </div>

                        <div class="form-group col-md-6 mb-4">
                            <label for="product_id">Product <span class="text-danger">*</span></label>
                            <div class="input-group-with-icon">
                                <i class="fa-solid fa-box-archive input-icon"></i>
                                {{-- MODIFICATION: Removed the @foreach loop and added 'disabled' attribute --}}
                                <select name="product_id" id="product_id" class="form-control select2-single" style="width: 100%;" disabled>
                                    <option value="">Select a supplier first</option>
                                </select>
                            </div>
                            <small class="text-danger-small d-none" id="product_idError"></small>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="form-group col-md-4 mb-4">
                            <label for="product_original_price_display">Product Original Price</label>
                            <div class="input-group-with-icon">
                                <i class="fa-solid fa-tag input-icon"></i>
                                <input type="text" id="product_original_price_display" class="form-control" placeholder="0.00" readonly>
                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-4">
                            <label for="product_current_qty_display">Current Stock</label>
                            <div class="input-group-with-icon">
                                <i class="fa-solid fa-cubes input-icon"></i>
                                <input type="text" id="product_current_qty_display" class="form-control" placeholder="0" readonly>
                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-4">
                            <label for="purchase_quantity">Purchase Quantity <span class="text-danger">*</span></label>
                            <div class="input-group-with-icon">
                                <i class="fa-solid fa-hashtag input-icon"></i>
                                <input type="number" name="purchase_quantity" id="purchase_quantity" class="form-control" placeholder="Enter quantity" min="1">
                            </div>
                            <small class="text-danger-small d-none" id="purchase_quantityError"></small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="form-group col-md-4 mb-4">
                            <label for="total_payable_display">Total Purchase Amount</label>
                            <div class="input-group-with-icon">
                                <i class="fa-solid fa-file-invoice-dollar input-icon"></i>
                                <input type="text" id="total_payable_display" class="form-control" readonly placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-4">
                            <label for="cash_paid">Cash Paid <span class="text-danger">*</span></label>
                            <div class="input-group-with-icon">
                                <i class="fa-solid fa-money-bill-wave input-icon"></i>
                                <input type="number" name="cash_paid" id="cash_paid" class="form-control" placeholder="Enter cash paid" min="0" value="0">
                            </div>
                            <small class="text-danger-small d-none" id="cash_paidError"></small>
                        </div>
                        <div class="form-group col-md-4 mb-4">
                            <label for="purchase_date">Purchase Date <span class="text-danger">*</span></label>
                            <div class="input-group-with-icon">
                                <i class="fa-solid fa-calendar-day input-icon"></i>
                                <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <small class="text-danger-small d-none" id="purchase_dateError"></small>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="notes">Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any notes for this purchase..."></textarea>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary-submit px-5 py-3" id="submitPurchaseBtn">
                            <i class="fas fa-save me-2"></i>SAVE PURCHASE RECORD
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@pushOnce('scripts')
<script>
    $(document).ready(function() {

        // Initialize select2 for the modal
        $('#addProductModal .select2').select2({
            dropdownParent: $('#addProductModal'), // Important for modals
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });

        $('#addCategoryBtn').click(function() {
            $('#addProductModalLabel').text('Add New Product');
            $('#productForm')[0].reset();
            $('.text-danger-small').addClass('d-none');
            $('#category_id').val('').trigger('change');
            $('#supplier_id_modal').val('').trigger('change');

            $('#submitBtn').text('Save Product').data('action', 'add');
            $('#addProductModal').modal('show');
        });

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

        // === MAJOR JAVASCRIPT MODIFICATION START ===

        $('#supplier_id').on('change', function() {
            displaySupplierBalance(); // Keep this function call
            const supplierId = $(this).val();
            const productSelect = $('#product_id');

            // Reset product dropdown and related fields whenever supplier changes
            productSelect.empty().append('<option value="">Select a supplier first</option>').prop('disabled', true).trigger('change');
            $('#product_original_price_display').val('0.00');
            $('#product_current_qty_display').val('0');

            if (supplierId) {
                // A supplier is selected, so fetch their products
                productSelect.empty().append('<option value="">Loading products...</option>');

                let productsUrl = `{{ route('purchase.products.by.supplier', ['supplier' => ':id']) }}`;
                productsUrl = productsUrl.replace(':id', supplierId);

                $.ajax({
                    url: productsUrl,
                    type: 'GET',
                    success: function(products) {
                        productSelect.empty().append('<option value="">Select Product</option>');
                        if (products.length > 0) {
                            products.forEach(function(product) {
                                let optionText = product.item_name + (product.item_code ? ` (Code: ${product.item_code})` : '');
                                productSelect.append(new Option(optionText, product.id));
                            });
                            productSelect.prop('disabled', false); // Enable the dropdown
                        } else {
                            productSelect.append('<option value="">No products found for this supplier</option>');
                        }
                        productSelect.trigger('change.select2'); // Notify select2 of the changes
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to fetch products for this supplier.', 'error');
                        productSelect.empty().append('<option value="">Error loading products</option>').prop('disabled', true);
                    }
                });
            }
        });

        // === MAJOR JAVASCRIPT MODIFICATION END ===

        $('#product_id').on('change', function() {
            const productId = $(this).val();
            selectedProductOriginalPrice = 0;
            $('#product_original_price_display').val('0.00');
            $('#product_current_qty_display').val('0');

            if (productId) {
                $('#submitPurchaseBtn').prop('disabled', true);
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

        // Form submission logic remains unchanged
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
                        $('#supplier_id').val('').trigger('change'); // This will now also reset the product dropdown
                        $('#purchase_date').val("{{ date('Y-m-d') }}");
                        $('#supplier_balance_info').empty();
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
                        Swal.fire({
                            title: "Validation Error",
                            html: errorMessages,
                            icon: "error"
                        });
                    } else {
                        Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON.message) || 'An error occurred.', 'error');
                    }
                },
                complete: function() {
                    $('#submitPurchaseBtn').prop('disabled', false).html('<i class="fas fa-save me-2"></i>SAVE PURCHASE RECORD');
                }
            });
        });

        displaySupplierBalance();
        calculateTotalPayable();
    });
</script>
@endPushOnce