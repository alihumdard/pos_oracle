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
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background-color: var(--primary-color);
    color: white;
    font-weight: 500;
    border: none;
    padding: 1rem;
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
}
</style>
@section('content')

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <div class="row">
                        <div class="form-group col-lg-6 col-md-6 col-12">
                            <label for="item_code">Item Code</label>
                            <input type="text" name="item_code" class="form-control" id="item_code"
                                placeholder="Enter Item Code">
                            <small class="text-danger d-none" id="itemCodeError">Item code is required.</small>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-12">
                            <label for="item_name">Item Name</label>
                            <input type="text" name="item_name" class="form-control" id="item_name"
                                placeholder="Enter Item Name">
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-12">
                            <label for="category_id">Category</label>
                            <select name="category_id" class="form-control" id="category_id">
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="categoryIdError">Category is required.</small>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-12">
                            <label for="supplier_id">Supplier</label>
                            <select name="supplier_id" class="form-control" id="supplier_id">
                                <option value="" disabled selected>Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="supplierIdError">Supplier is required.</small>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-12">
                            <label for="selling_price">Selling Price</label>
                            <input type="number" name="selling_price" class="form-control" id="selling_price"
                                placeholder="Enter Selling Price">
                            <small class="text-danger d-none" id="sellingPriceError">Selling price is required.</small>
                        </div>
                        <div class="form-group col-lg-6 col-md-6 col-12">
                            <label for="original_price">Original Price</label>
                            <input type="number" name="original_price" class="form-control" id="original_price"
                                placeholder="Enter Original Price">
                            <small class="text-danger d-none" id="originalPriceError">Original price is
                                required.</small>
                        </div>

                        <div class="form-group col-lg-6 col-md-6 col-12">
                            <label for="qty">Quantity</label>
                            <input type="number" name="qty" class="form-control" id="qty" placeholder="Enter Quantity">
                            <small class="text-danger d-none" id="qtyError">Quantity is required.</small>
                        </div>

                        <div class="col-12 mx-auto d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn" data-action="add">Save
                                Product</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-3">
    <!-- Filter Form -->
 <div class="row">
    <div class="col-12">
        <form action="{{ route('supplier_category.filter') }}" method="GET" class="filter-form p-3 bg-white rounded shadow-sm">
            <div class="row g-3 align-items-end">
                <!-- Supplier Dropdown -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="supplier_id" class="form-label fw-medium text-secondary mb-1">Supplier</label>
                        <select name="supplier_id"
                                id="supplier_id"
                                class="select2"
                                style="
                                    width: 100%;
                                    padding: 8px 12px;
                                    font-size: 14px;
                                    line-height: 1.5;
                                    color: #495057;
                                    background-color: #fff;
                                    background-clip: padding-box;
                                    border: 1px solid #ced4da;
                                    border-radius: 4px;
                                    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                                    box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
                                ">
                            <option value=""></option>
                            @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <!-- Category Dropdown -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="category_id" class="form-label fw-medium text-secondary mb-1">Category</label>
                        <select name="category_id" class="form-select select2" id="category_id" style="
                                    width: 100%;
                                    padding: 8px 12px;
                                    font-size: 14px;
                                    line-height: 1.5;
                                    color: #495057;
                                    background-color: #fff;
                                    background-clip: padding-box;
                                    border: 1px solid #ced4da;
                                    border-radius: 4px;
                                    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                                    box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
                                ">
                            <option value=""></option>
                            @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="d-grid" style="margin-bottom: 10px;">
                        <button type="submit" class="btn btn-primary py-2">
                            <i class="fas fa-filter me-2"></i>Apply Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-2 mb-md-0">
                        <h3 class="card-title">All Products</h3>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{route('product_all')}}" class="btn btn-success">
                            <i class="fa fa-box mr-2"></i>All Products
                        </a>
                        <button type="button" class="btn btn-primary" id="addCategoryBtn">
                            <i class="fa fa-plus mr-2"></i>Add Product
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" style="width: 100%;" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th>Item Code</th>
                                    <th>Original Price</th>
                                    <th>Selling Price</th>
                                    <th>Quantity</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableHolder">
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->item_name ?? '' }}</td>
                                    <td>{{ $product->item_code ?? ''}}</td>
                                    <td>{{ $product->original_price ?? ''}}</td>
                                    <td>{{ $product->selling_price ?? ''}}</td>
                                    <td>{{ $product->qty ?? ''}}</td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary edit-product"
                                                data-id="{{ $product->id }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-product"
                                                data-id="{{ $product->id }}">
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
        </div>
    </div>
</div>

@stop

@pushOnce('scripts')
<script>
$(function() {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": true,
        "scrollY": true,
        "scrollX": true,
        "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});


$(document).ready(function() {

    $('#addCategoryBtn').click(function() {
        $('#addProductModalLabel').text('Add New Product');

        $('#nameError').addClass('d-none');
        $('#duplicateError').addClass('d-none');
        $('#submitBtn').text('Save Product').data('action', 'add');
        $('#addProductModal').modal('show');
    });
    $('#productForm').submit(function(e) {
        e.preventDefault();

        const actionType = $('#submitBtn').data('action');
        const url = actionType === 'add' ? "{{ route('add.products') }}" : '/products/' + $(
            '#submitBtn').data('id');
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
        let hasError = false;

        function getErrorElementId(key) {
            return key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase()) + 'Error';
        }

        Object.keys(formData).forEach(function(key) {
            const value = formData[key];
            const errorElement = $('#' + getErrorElementId(key));
            console.log(errorElement);
            if (!value && key !== '_token') {
                errorElement.removeClass('d-none');
                hasError = true;
            } else {
                errorElement.addClass('d-none');
            }
        });


        if (hasError) return;
        $.ajax({
            url: url,
            type: actionType === 'add' ? 'POST' : 'PUT',
            data: formData,
            success: function(response) {

                Swal.fire({
                    title: "Good job!",
                    text: actionType === 'add' ? 'Product added successfully!' :
                        'Product updated successfully!',
                    icon: "success",
                });

                $('#addProductModal').modal('hide');
                $('#productForm')[0].reset();

                let url = "{{ route('show.products') }}?t=" + new Date().getTime();
                refreshtble(url);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        const errorElementId = getErrorElementId(key);
                        $('#' + errorElementId).text(errors[key][0]).removeClass(
                            'd-none');
                    });


                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                        footer: '<a href="#">Validation error occurred. Please check your inputs.</a>'
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


    function refreshtble(url) {
        $("#tableHolder").load(url + " #tableHolder > *");

        if ($.fn.DataTable.isDataTable('#example1')) {
            $('#example1').DataTable().destroy();
        }

        // Reinitialize the DataTable
        $("#example1").DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            scrollY: true,
            scrollX: true,
            buttons: ["excel", "pdf"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    }
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });

    // Edit category logic
    $(document).on('click', '.edit-product', function() {
        const productId = $(this).data('id');
        $.ajax({
            url: '/products/' + productId,
            type: 'GET',
            success: function(response) {
                console.log(response);
                $('#addProductModalLabel').text('Edit Product');
                $('#qty').val(response.qty);
                $('#category_id').val(response.category_id);
                $('#supplier_id').val(response.supplier_id);
                $('#original_price').val(response.original_price);
                $('#selling_price').val(response.selling_price);
                $('#item_name').val(response.item_name);
                $('#item_code').val(response.item_code);
                $('#nameError').addClass('d-none');
                $('#duplicateError').addClass('d-none');
                $('#submitBtn').text('Update Product').data('action', 'edit').data('id',
                    productId);
                $('#addProductModal').modal('show');

                function refreshtble(url) {
                    $("#tableHolder").load(url + " #tableHolder > *");
                }
            },
            error: function() {
                alert('Error fetching category details.');
            },
        });
    });
    $(document).on('click', '.delete-product', function() {
        const productId = $(this).data('id');
        // Show a confirmation dialog before deletion
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
                        let url = "{{ route('show.products') }}?t=" + new Date()
                            .getTime();
                        refreshtble(url);
                    },
                    error: function(xhr) {
                        console.log(xhr);
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
    $(document).on('click', '.view-product', function() {
        // alert();
        $.ajax({
            url: '/all/products',
            type: 'get',
            data: {
                _token: '{{ csrf_token() }}'
            },
        });

        function refreshtble(url) {
            $("#tableHolder").load(url + " #tableHolder > *");
        }


    });
});
</script>

@endPushOnce