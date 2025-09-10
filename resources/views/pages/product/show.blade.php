@extends('index')
<style>
:root {
    --primary-color: #4361ee;
    --primary-hover: #3a56d4;
    --secondary-color: #f8f9fa;
    --text-color: #2b2d42;
    --light-gray: #e9ecef;
    --border-radius: 8px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8fafc;
    color: var(--text-color);
    /* Ensure no global overflow-x: hidden; here or on html */
    /* If you have it, remove it or find a more specific element to hide overflow */
}

/* Filter Form Styles */
.filter-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--light-gray);
}

.filter-container .form-control,
.filter-container .select2-selection {
    height: 45px;
    border-radius: var(--border-radius);
    border: 1px solid var(--light-gray);
    transition: var(--transition);
}

.filter-container .form-control:focus,
.filter-container .select2-selection:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

.filter-container .btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    height: 45px;
    font-weight: 500;
    transition: var(--transition);
}

.filter-container .btn-primary:hover {
    background-color: var(--primary-hover);
    border-color: var(--primary-hover);
    transform: translateY(-2px);
}

/* Card Styles */
.card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    /* This is fine for hiding content that overflows the card itself */
}

.card-header {
    background-color: white;
    border-bottom: 1px solid var(--light-gray);
    padding: 1.25rem 1.5rem;
}

.card-title {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0;
}

/* Table Styles */
.table-responsive {
    border-radius: var(--border-radius);
    /* THIS IS THE CRITICAL LINE FOR DEBUGGING */
    overflow-x: auto !important;
    /* Force horizontal scrolling, use !important for debugging */
    -webkit-overflow-scrolling: touch;
    /* Improves scrolling performance on iOS */
    /* End of critical line */
    overflow-y: hidden;
    /* Prevent vertical scrollbar if not needed, or set to auto */
}

.table {
    margin-bottom: 0;
    width: 100%;
    /* Ensure table takes full width of its parent */
    min-width: 768px;
    /* Optional: Set a minimum width for the table if you want it to always be wide enough to scroll on small devices. Adjust as needed. */
}

.table thead th {
    background-color: var(--primary-color);
    color: white;
    font-weight: 500;
    border: none;
    padding: 1rem;
    white-space: nowrap;
    /* Keep headers on one line */
}

.table tbody tr {
    transition: var(--transition);
}

.table tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-top: 1px solid var(--light-gray);
    white-space: nowrap;
    /* Keep cell content on one line to force horizontal overflow */
}

/* Button Styles */
.btn {
    border-radius: var(--border-radius);
    padding: 0.5rem 1.25rem;
    font-weight: 500;
    transition: var(--transition);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: var(--primary-hover);
    border-color: var(--primary-hover);
    transform: translateY(-2px);
}

.btn-success {
    background-color: #2e7d32;
    border-color: #2e7d32;
}

.btn-success:hover {
    background-color: #276a2b;
    border-color: #276a2b;
}

.btn-danger {
    background-color: #d32f2f;
    border-color: #d32f2f;
}

.btn-danger:hover {
    background-color: #b71c1c;
    border-color: #b71c1c;
}

.btn-sm {
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
    border-bottom: 1px solid var(--light-gray);
    padding: 1.25rem 1.5rem;
}

.modal-title {
    font-weight: 600;
    color: var(--text-color);
}

.modal-body {
    padding: 1.5rem;
}

.form-group label {
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: var(--text-color);
}

.form-control {
    height: 45px;
    border-radius: var(--border-radius);
    border: 1px solid var(--light-gray);
    transition: var(--transition);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

.text-danger {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    flex-wrap: wrap;
    /* Allow buttons to wrap on smaller screens */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .filter-container .form-row>div {
        margin-bottom: 1rem;
    }

    .action-buttons {
        flex-direction: column;
        gap: 0.5rem;
    }

    .btn {
        width: 100%;
    }

    /* Ensure select2 dropdowns are fully responsive inside modals/forms */
    .select2-container {
        width: 100% !important;
    }

    /* Make sure table is wide enough to scroll */
    .table {
        min-width: 768px;
        /* Example: Ensures table is always at least 768px wide, forcing scroll if needed */
    }
}

.btn-gradient-primary {
    background: linear-gradient(90deg, #2563eb, #1d4ed8);
    border: none;
    color: #fff;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.btn-gradient-primary:hover {
    background: linear-gradient(90deg, #1d4ed8, #1e40af);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
}

.form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.2);
}


.card-header {
    padding: 1rem 1.25rem;
}

.table thead th {
    font-weight: 600;
}

.table tbody tr:hover {
    background-color: #f9fafb !important;
    transition: background-color 0.2s ease-in-out;
}

.btn-outline-primary:hover {
    background-color: #2563eb;
    color: #fff;
}

.btn-outline-danger:hover {
    background-color: #dc2626;
    color: #fff;
}
</style>
@section('content')
<div class="flex justify-end py-10">
    <button onclick="history.back()"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-400 to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Back
    </button>
</div>
<!-- Add Product Modal -->
<div id="addProductModal"
    class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h5 class="text-lg font-semibold">Add New Product</h5>
            <button type="button" class="text-gray-500 hover:text-red-600" onclick="closeModal()">
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="productForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Item Code -->
                <div>
                    <label for="item_code" class="block text-sm font-medium">Item Code</label>
                    <input type="text" name="item_code" id="item_code"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter Item Code" />
                    <small id="itemCodeError" class="hidden text-red-500 text-xs">Item code is required.</small>
                </div>

                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium">Item Name</label>
                    <input type="text" name="item_name" id="item_name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter Item Name" />
                    <small id="itemNameError" class="hidden text-red-500 text-xs">Item name is required.</small>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium">Category</label>
                    <select id="category_id" name="category_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <small id="categoryIdError" class="hidden text-red-500 text-xs">Category is required.</small>
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier_id" class="block text-sm font-medium">Supplier</label>
                    <select id="supplier_id" name="supplier_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" disabled selected>Select Supplier</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                        @endforeach
                    </select>
                    <small id="supplierIdError" class="hidden text-red-500 text-xs">Supplier is required.</small>
                </div>

                <!-- Selling Price -->
                <div>
                    <label for="selling_price" class="block text-sm font-medium">Selling Price</label>
                    <input type="number" name="selling_price" id="selling_price"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter Selling Price" />
                    <small id="sellingPriceError" class="hidden text-red-500 text-xs">Selling price is required.</small>
                </div>

                <!-- Original Price -->
                <div>
                    <label for="original_price" class="block text-sm font-medium">Original Price</label>
                    <input type="number" name="original_price" id="original_price"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter Original Price" />
                    <small id="originalPriceError" class="hidden text-red-500 text-xs">Original price is
                        required.</small>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="qty" class="block text-sm font-medium">Quantity</label>
                    <input type="number" name="qty" id="qty"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter Quantity" />
                    <small id="qtyError" class="hidden text-red-500 text-xs">Quantity is required.</small>
                </div>

                <!-- Save Button -->
                <div class="col-span-1 md:col-span-2 flex justify-center mt-4">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Filter Section -->
<div class="container mx-auto mt-6 px-4">
    <form action="{{ route('supplier_category.filter') }}" method="GET"
        class="bg-white p-6 rounded-lg shadow-lg grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Supplier -->
        <div>
            <label for="filter_supplier_id" class="block font-medium mb-2">
                <i class="fas fa-truck text-blue-600 mr-1"></i> Supplier
            </label>
            <select id="filter_supplier_id" name="supplier_id"
                class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Select Supplier</option>
                @foreach($suppliers ?? [] as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                @endforeach
            </select>
        </div>

        <!-- Category -->
        <div>
            <label for="filter_category_id" class="block font-medium mb-2">
                <i class="fas fa-tags text-green-600 mr-1"></i> Category
            </label>
            <select id="filter_category_id" name="category_id"
                class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Select Category</option>
                @foreach($categories ?? [] as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Button -->
        <div class="flex items-end">
            <button type="submit"
                class="w-full py-2 px-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-md shadow hover:opacity-90">
                <i class="fas fa-filter mr-2"></i> Apply Filter
            </button>
        </div>
    </form>
</div>


<!-- Products Table -->
<div class="container mx-auto mt-6 px-4">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">

        <!-- Header -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 p-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
            <h3 class="text-lg font-semibold flex items-center">
                <i class="fa fa-box-open mr-2"></i> All Products
            </h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('product_all') }}"
                    class="px-4 py-2 bg-white text-blue-700 rounded-md shadow hover:bg-gray-100 flex items-center">
                    <i class="fa fa-box mr-2"></i> All Products
                </a>
                <button type="button" id="addCategoryBtn"
                    class="px-4 py-2 bg-yellow-400 text-gray-900 rounded-md shadow hover:bg-yellow-500 flex items-center">
                    <i class="fa fa-plus mr-2"></i> Add Product
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-4 overflow-x-auto">
            <table id="example1" class="w-full border overflow-x-auto border-gray-200 rounded-lg text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Item Name</th>
                        <th class="px-4 py-2">Item Code</th>
                        <th class="px-4 py-2">Original Price</th>
                        <th class="px-4 py-2">Selling Price</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 font-medium">{{ $product->item_name ?? '' }}</td>
                        <td class="px-4 py-2">{{ $product->item_code ?? '' }}</td>
                        <td class="px-4 py-2">Rs. {{ $product->original_price ?? '' }}</td>
                        <td class="px-4 py-2 text-green-600 font-semibold">Rs. {{ $product->selling_price ?? '' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                {{ $product->qty ?? '' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex justify-center gap-2">
                                <a href="javascript:void(0)"
                                    class="p-2 border border-blue-500 text-blue-500 rounded hover:bg-blue-50"
                                    title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0)"
                                    class="p-2 border border-red-500 text-red-500 rounded hover:bg-red-50"
                                    title="Delete">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@stop

@pushOnce('scripts')
<script>
$(document).ready(function() {
    // Initial DataTables setup
    $('#example1').DataTable({
    dom: '<"dt-toolbar overflow-x-auto"Bfrtip>', // ✅ Toolbar ko scrollable banaya
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
    ],
    paging: true,
    searching: true,
    ordering: true,
    info: true,
    columnDefs: [
        {
            orderable: false,
            targets: 6
        }
    ],
    initComplete: function () {
        // Flex row force + spacing
        $('.dt-buttons').addClass('flex flex-nowrap gap-2 w-[40%]');
        $('.dt-buttons button').addClass('bg-blue-600 text-white px-3 py-1 rounded-md shadow hover:bg-blue-700 transition');
    }
});

    // Initialize Select2 for filter dropdowns
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true // Allows clearing the selection
    });

    // Open modal for adding a new product
    $('#addCategoryBtn').click(function() {
        $('#addProductModalLabel').text('Add New Product');
        $('#productForm')[0].reset(); // Reset form fields
        $('.text-danger').addClass('d-none'); // Hide all validation errors
        // Reset select2 dropdowns to placeholder
        $('#category_id').val('').trigger('change');
        $('#supplier_id').val('').trigger('change');

        $('#submitBtn').text('Save Product').data('action', 'add');
        $('#addProductModal').modal('show');
    });

    // Form submit handler for add/edit
    $('#productForm').submit(function(e) {
        e.preventDefault();

        const actionType = $('#submitBtn').data('action');
        const productId = $('#submitBtn').data('id');
        const url = actionType === 'add' ? "{{ route('add.products') }}" : '/products/' + productId;

        const formData = {
            _token: '{{ csrf_token() }}',
            item_code: $('#item_code').val().trim(),
            item_name: $('#item_name').val().trim(),
            selling_price: $('#selling_price').val().trim(),
            original_price: $('#original_price').val().trim(),
            category_id: $('#category_id').val(),
            supplier_id: $('#supplier_id').val(),
            qty: $('#qty').val().trim(),
        };

        // Frontend validation
        let hasError = false;
        $('.text-danger').addClass('d-none'); // Hide previous errors

        function getErrorElementId(key) {
            // Converts item_code to itemCodeError
            return key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase()) + 'Error';
        }

        // Validate each field
        Object.keys(formData).forEach(function(key) {
            const value = formData[key];
            const errorElement = $('#' + getErrorElementId(key));

            if (!value && key !== '_token') { // _token doesn't need validation
                errorElement.removeClass('d-none');
                hasError = true;
            }
        });

        if (hasError) return; // Stop if there are frontend validation errors

        // AJAX request for add or update
        $.ajax({
            url: url,
            type: actionType === 'add' ? 'POST' : 'PUT',
            data: formData,
            success: function(response) {
                Swal.fire({
                    title: "Success!",
                    text: response.message || (actionType === 'add' ?
                        'Product added successfully!' :
                        'Product updated successfully!'),
                    icon: "success",
                });

                $('#addProductModal').modal('hide');
                $('#productForm')[0].reset(); // Reset form fields
                $('#category_id').val('').trigger('change'); // Reset Select2
                $('#supplier_id').val('').trigger('change'); // Reset Select2

                let refreshUrl = "{{ route('show.products') }}?t=" + new Date().getTime();
                refreshtble(refreshUrl);
            },
            error: function(xhr) {
                if (xhr.status === 422) { // Validation errors from backend
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        const errorElementId = getErrorElementId(key);
                        $('#' + errorElementId).text(errors[key][0]).removeClass(
                            'd-none');
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: "Please correct the highlighted fields.",
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                        footer: '<a href="#">An error occurred. Please try again.</a>'
                    });
                }
            },
        });
    });

    // Function to refresh the table content and reinitialize DataTables
    function refreshtble(url) {
        // Use a temporary element to load the new table content to avoid issues during reinitialization
        $('body').append('<div id="tempTableContent" style="display:none;"></div>');
        $('#tempTableContent').load(url + " #tableHolder > *", function() {
            // Destroy the old DataTable instance if it exists
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }

            // Replace the old table body with the new one
            $('#tableHolder').html($('#tempTableContent #tableHolder').html());
            $('#tempTableContent').remove(); // Remove the temporary element

            // Reinitialize the DataTable with consistent settings
            $("#example1").DataTable({
                "responsive": false,
                "lengthChange": true,
                "autoWidth": false,
                "scrollY": false,
                "scrollX": false,
                "buttons": ["excel", "pdf"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    }

    // Edit product logic
    $(document).on('click', '.edit-product', function() {
        const productId = $(this).data('id');
        $.ajax({
            url: '/products/' + productId,
            type: 'GET',
            success: function(response) {
                $('#addProductModalLabel').text('Edit Product');
                $('#qty').val(response.qty);
                $('#category_id').val(response.category_id).trigger(
                    'change'); // Update Select2
                $('#supplier_id').val(response.supplier_id).trigger(
                    'change'); // Update Select2
                $('#original_price').val(response.original_price);
                $('#selling_price').val(response.selling_price);
                $('#item_name').val(response.item_name);
                $('#item_code').val(response.item_code);

                $('.text-danger').addClass('d-none'); // Hide any previous validation errors
                $('#submitBtn').text('Update Product').data('action', 'edit').data('id',
                    productId);
                $('#addProductModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Could not fetch product details. Please try again.",
                });
            },
        });
    });

    // Delete product logic
    $(document).on('click', '.delete-product', function() {
        const productId = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this product?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/products/' + productId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Product deleted successfully!",
                            icon: "success",
                        });
                        let refreshUrl = "{{ route('show.products') }}?t=" +
                            new Date().getTime();
                        refreshtble(refreshUrl);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="#">An error occurred while deleting the product. Please try again.</a>'
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: "Cancelled",
                    text: "The product is safe.",
                    icon: "info"
                });
            }
        });
    });
});
</script>

@endPushOnce