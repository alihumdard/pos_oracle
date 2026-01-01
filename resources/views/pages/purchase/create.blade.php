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

    .form-container {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 2rem;
        margin-top: 2rem;
        border: 1px solid var(--light-gray);
    }

    .form-container .form-control,
    .form-container .select2-container--default .select2-selection--single {
        height: 45px !important;
        border-radius: var(--border-radius) !important;
        border: 1px solid var(--light-gray) !important;
        display: flex;
        align-items: center;
    }

    .form-container .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal !important;
        padding-left: .75rem !important;
        padding-right: 20px;
    }

    .form-container .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(100% - 2px) !important;
        top: 1px !important;
        right: 5px !important;
    }

    .form-container .form-control:focus,
    .form-container .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25) !important;
    }
    
    .form-container .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: #e9ecef;
        opacity: 1;
    }

    .form-group label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .text-danger-small {
        font-size: 0.875em;
        color: #dc3545;
    }

    .btn-primary-submit {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        height: 45px;
        font-weight: 500;
        color: white;
    }

    .btn-primary-submit:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    .balance-info {
        font-size: 0.9em;
        margin-top: 5px;
        padding: 5px 0;
    }

    .balance-positive {
        color: green;
        font-weight: bold;
    }

    .balance-negative {
        color: red;
        font-weight: bold;
    }

    .balance-zero {
        color: #6c757d;
    }

    .label-with-button {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* {{-- Fix for modal select2 z-index --}} */
    .modal .select2-container {
        z-index: 1056; /* Higher than modal's z-index (1055) */
    }
</style>

<div class="container-fluid pt-8">
    <div class="row">
        <div class="col-lg-10 col-md-12 mx-auto">
            <div class="form-container">
                <h3 class="mb-4 text-center" style="color: var(--primary-color);">Record New Purchase</h3>
                <form id="purchaseForm">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                           <select name="supplier_id" id="supplier_id" class="form-control select2-single" style="width: 100%;">
    <option value="">Select Supplier</option>
    @foreach($suppliers as $supplier)
        <option value="{{ $supplier->id }}" 
                data-balance="{{ $supplier->ledger_balance }}">
            {{ $supplier->supplier }}  {{ $supplier->contact_person ? '(' . $supplier->contact_person . ')' : '' }}
        </option>
    @endforeach
</select>
                            <div id="supplier_balance_info" class="balance-info"></div>
                            <small class="text-danger-small d-none" id="supplier_idError"></small>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <div class="label-with-button">
                                <label for="product_id">Product <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-success btn-sm py-1" id="showAddProductModalBtn" disabled>
                                    <i class="fas fa-plus"></i> Add New
                                </button>
                            </div>
                            <select name="product_id" id="product_id" class="form-control select2-single" style="width: 100%;" disabled>
                                <option value="">Select supplier first</option>
                            </select>
                            <small class="text-danger-small d-none" id="product_idError"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="purchase_quantity">Purchase Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="purchase_quantity" name="purchase_quantity" required>
                                <small class="text-danger-small d-none" id="purchase_quantityError"></small>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="purchase_price">Purchase Price (Per Item) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" required>
                                <small class="text-danger-small d-none" id="purchase_priceError"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4 mb-3">
                            <label for="product_cost_price_display">Product's Last Cost Price</label>
                            <input type="text" id="product_cost_price_display" class="form-control" readonly placeholder="0.00" style="background-color: #e9ecef; opacity: 1;">
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label for="product_selling_price_display">Current Selling Price</label>
                            <input type="text" id="product_selling_price_display" class="form-control" readonly placeholder="0.00" style="background-color: #e9ecef; opacity: 1;">
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label for="product_current_qty_display">Current Stock</label>
                            <input type="text" id="product_current_qty_display" class="form-control" readonly placeholder="0" style="background-color: #e9ecef; opacity: 1;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4 mb-3">
                            <label for="total_payable_display">Total Purchase Amount</label>
                            <input type="text" id="total_payable_display" class="form-control" readonly placeholder="0.00" style="background-color: #e9ecef; opacity: 1;">
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label for="cash_paid">Cash Paid <span class="text-danger">*</span></label>
                            <input type="number" name="cash_paid" id="cash_paid" class="form-control" placeholder="Enter cash paid" min="0" value="0">
                            <small class="text-danger-small d-none" id="cash_paidError"></small>
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label for="purchase_date">Purchase Date <span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ date('Y-m-d') }}">
                            <small class="text-danger-small d-none" id="purchase_dateError"></small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="new_selling_price">New Selling Price (Optional)</label>
                            <input type="number" step="0.01" class="form-control" id="new_selling_price" name="new_selling_price" placeholder="Leave blank to keep old price">
                            <small class="text-danger-small d-none" id="new_selling_priceError"></small>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="notes">Notes (Optional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="1" placeholder="Any notes for this purchase..."></textarea>
                        </div>
                    </div>


                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary-submit px-5" id="submitPurchaseBtn">
                            {{-- CHANGED: me-2 to mr-2 --}}
                            <i class="fas fa-save mr-2"></i>Save Purchase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- =================================================================================== --}}
{{-- UPDATED: ADD PRODUCT MODAL (Bootstrap 4 Syntax) --}}
{{-- =================================================================================== --}}
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- CHANGED: Updated modal header for Bootstrap 4 --}}
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <input type="hidden" id="modal_supplier_id" name="supplier_id">
                    <input type="hidden" name="qty" value="0">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="modal_item_name">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_item_name" name="item_name" required>
                                <small class="text-danger-small d-none" id="modal_item_nameError"></small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="modal_item_code">Product Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modal_item_code" name="item_code" required>
                                <small class="text-danger-small d-none" id="modal_item_codeError"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="modal_category_id">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="modal_category_id" class="form-control select2-modal" style="width: 100%;" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger-small d-none" id="modal_category_idError"></small>
                            </div>
                        </div>
                         <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="modal_original_price">Cost Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="modal_original_price" name="original_price" required>
                                <small class="text-danger-small d-none" id="modal_original_priceError"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                       <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="modal_selling_price">Selling Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="modal_selling_price" name="selling_price" required>
                                <small class="text-danger-small d-none" id="modal_selling_priceError"></small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{-- CHANGED: Updated modal footer for Bootstrap 4 --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNewProductBtn">
                    {{-- CHANGED: me-1 to mr-1 --}}
                    <i class="fas fa-save mr-1"></i> Save Product
                </button>
            </div>
        </div>
    </div>
</div>
{{-- =================================================================================== --}}
{{-- END OF MODAL --}}
{{-- =================================================================================== --}}

@endsection

@pushOnce('scripts')
<script>
    $(document).ready(function() {
        $('.select2-single').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });

        // Init modal form select2
        $('.select2-modal').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addProductModal') // This is correct, keeps select2 search in modal
        });

        let selectedProductCostPrice = 0;
        let selectedProductSellingPrice = 0;
        
        // --- JAVASCRIPT CHANGE: Removed new bootstrap.Modal() ---
        // No JS initialization needed for Bootstrap 4 modal, jQuery handles it.

        function calculateTotalPayable() {
            const quantity = parseFloat($('#purchase_quantity').val()) || 0;
            const price = parseFloat($('#purchase_price').val()) || 0; 
            const totalPayable = quantity * price; 
            $('#total_payable_display').val(totalPayable.toFixed(2));
        }

        function displaySupplierBalance() {
            const selectedOption = $('#supplier_id').find('option:selected');
            const balanceInfoDiv = $('#supplier_balance_info');
            balanceInfoDiv.empty();

            if ($('#supplier_id').val() && selectedOption.length > 0 && selectedOption.val() !== "") {
                // Model se aaya hua accurate ledger balance
                const balance = parseFloat(selectedOption.data('balance')) || 0;
                
                let balanceText = '';
                let balanceClass = 'balance-zero';

                if (balance > 0) {
                    // Positive balance means we owe money (Red color)
                    balanceText = `Current Balance: ${Math.abs(balance).toFixed(2)} (You owe supplier)`;
                    balanceClass = 'balance-negative'; 
                } else if (balance < 0) {
                    // Negative balance means we overpaid (Green color)
                    balanceText = `Current Balance: ${Math.abs(balance).toFixed(2)} (Supplier owes you)`;
                    balanceClass = 'balance-positive';
                } else {
                    balanceText = 'Current Balance: 0.00 (Settled)';
                    balanceClass = 'balance-zero';
                }
                balanceInfoDiv.html(`<span class="${balanceClass}">${balanceText}</span>`);
            }
        }
        
        function resetProductFields() {
            $('#product_id').empty().append('<option value="">Select supplier first</option>');
            $('#product_cost_price_display').val('0.00');
            $('#product_selling_price_display').val('0.00');
            $('#product_current_qty_display').val('0');
            $('#purchase_quantity').val('');
            $('#purchase_price').val('');
            $('#new_selling_price').val('');
            $('#total_payable_display').val('0.00');
            $('#product_id').prop('disabled', true).trigger('change');
            $('#showAddProductModalBtn').prop('disabled', true); // Disable 'Add New' button
        }

        $('#supplier_id').on('change', function() {
            displaySupplierBalance();
            const supplierId = $(this).val();
            const productDropdown = $('#product_id');

            resetProductFields();

            if (supplierId) {
                // Enable 'Add New' button
                $('#showAddProductModalBtn').prop('disabled', false);

                productDropdown.empty().append('<option value="">Loading products...</option>').prop('disabled', true).trigger('change');

                let productsUrl = `{{ route('purchase.products-by-supplier', ['id' => ':id']) }}`;
                productsUrl = productsUrl.replace(':id', supplierId);

                $.ajax({
                    url: productsUrl,
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            productDropdown.empty().append('<option value="">Select Product</option>');
                            
                            if (response.products.length > 0) {
                                $.each(response.products, function(index, product) {
                                    let productName = product.item_name;
                                    if(product.item_code) {
                                        productName += ` (Code: ${product.item_code})`;
                                    }
                                    productDropdown.append(new Option(productName, product.id));
                                });
                            } else {
                                productDropdown.empty().append('<option value="">No products found (Click Add New)</option>');
                            }
                            productDropdown.prop('disabled', false).trigger('change');
                        } else {
                            Swal.fire('Error', response.message || 'Could not fetch products.', 'error');
                            productDropdown.empty().append('<option value="">Error loading products</option>').prop('disabled', true).trigger('change');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to fetch products. Check network or console.', 'error');
                        productDropdown.empty().append('<option value="">Error loading products</option>').prop('disabled', true).trigger('change');
                    }
                });
            } else {
                resetProductFields();
            }
        });

        $('#product_id').on('change', function() {
            const productId = $(this).val();
            
            selectedProductCostPrice = 0;
            selectedProductSellingPrice = 0;
            $('#product_cost_price_display').val('0.00');
            $('#product_selling_price_display').val('0.00');
            $('#product_current_qty_display').val('0');
            $('#purchase_price').val('0.00'); 
            $('#new_selling_price').val('');

            if (productId) {
                // CHANGED: me-2 to mr-2
                $('#submitPurchaseBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>');
                
                let productDetailsUrl = `{{ route('purchase.product.details', ['id' => ':id']) }}`;
                productDetailsUrl = productDetailsUrl.replace(':id', productId);

                $.ajax({
                    url: productDetailsUrl,
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            selectedProductCostPrice = parseFloat(response.cost_price) || 0;
                            selectedProductSellingPrice = parseFloat(response.selling_price) || 0;
                            
                            $('#product_cost_price_display').val(selectedProductCostPrice.toFixed(2));
                            $('#product_selling_price_display').val(selectedProductSellingPrice.toFixed(2));
                            $('#product_current_qty_display').val(response.current_qty || 0);
                            $('#purchase_price').val(selectedProductCostPrice.toFixed(2));
                            $('#new_selling_price').val(selectedProductSellingPrice.toFixed(2));
                        } else {
                            Swal.fire('Error', response.message || 'Could not fetch product details.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to fetch product details. Check network or console.', 'error');
                    },
                    complete: function() {
                        calculateTotalPayable();
                        // CHANGED: me-2 to mr-2 
                        $('#submitPurchaseBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Save Purchase');
                    }
                });
            } else {
                calculateTotalPayable();
            }
        });

        $('#purchase_quantity').on('input change keyup', function() {
            calculateTotalPayable();
        });

        $('#purchase_price').on('input change keyup', function() {
            calculateTotalPayable();
        });

        // Main Purchase Form Submit
        $('#purchaseForm').submit(function(e) {
            e.preventDefault();
            // CHANGED: me-2 to mr-2
            $('#submitPurchaseBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');
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
                        $('#cash_paid').val('0'); 
                        $('#purchase_date').val("{{ date('Y-m-d') }}");
                        $('#supplier_balance_info').empty();

                        if(response.purchase && response.purchase.supplier) {
                            let updatedSupplier = response.purchase.supplier;
                            $('#supplier_id option[value="' + updatedSupplier.id + '"]')
                                .data('debit', updatedSupplier.debit)
                                .data('credit', updatedSupplier.credit);
                        }
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
                    // CHANGED: me-2 to mr-2
                    $('#submitPurchaseBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Save Purchase');
                }
            });
        });


        // ===================================================================================
        // NEW: ADD PRODUCT MODAL JAVASCRIPT (Bootstrap 4)
        // ===================================================================================

        // 1. Show the modal
        $('#showAddProductModalBtn').on('click', function() {
            const selectedSupplierId = $('#supplier_id').val();
            
            if (!selectedSupplierId) {
                Swal.fire('Error', 'Please select a supplier first.', 'error');
                return;
            }
            
            $('#addProductForm')[0].reset();
            $('#modal_category_id').val('').trigger('change');
            $('#addProductForm .text-danger-small').addClass('d-none').text('');
            $('#modal_supplier_id').val(selectedSupplierId);
            
            // CHANGED: Use jQuery modal('show')
            $('#addProductModal').modal('show');
        });

        // 2. Save the new product
        $('#saveNewProductBtn').on('click', function() {
            // CHANGED: me-1 to mr-1
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving...');
            $('#addProductForm .text-danger-small').addClass('d-none').text('');
            
            const formData = $('#addProductForm').serialize();
            
            $.ajax({
                url: "{{ route('add.products') }}", 
                type: 'POST',
                data: formData,
                headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() }, 
                success: function(response) {
                    if (response.message && response.product) {
                        toastr.success(response.message);
                        
                        const newProduct = response.product;
                        let productName = newProduct.item_name;
                        if(newProduct.item_code) {
                            productName += ` (Code: ${newProduct.item_code})`;
                        }

                        var newOption = new Option(productName, newProduct.id, true, true);
                        $('#product_id').append(newOption).trigger('change');
                        
                        // CHANGED: Use jQuery modal('hide')
                        $('#addProductModal').modal('hide');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                $('#modal_' + key + 'Error').text(errors[key][0]).removeClass('d-none');
                            }
                        }
                    } else {
                        toastr.error((xhr.responseJSON && xhr.responseJSON.message) || 'An error occurred.');
                    }
                },
                complete: function() {
                    // CHANGED: me-1 to mr-1
                    $('#saveNewProductBtn').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Product');
                }
            });
        });

        // Initial calls
        displaySupplierBalance();
        calculateTotalPayable();
    });
</script>
@endPushOnce