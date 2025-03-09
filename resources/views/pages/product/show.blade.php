@extends('index')
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f7fc;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }

  form {
    border: 2px solid blue;
    background: white;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    max-width: 100%;
    margin-left: 10%;
  }

  select,
  input[type="checkbox"],
  button {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    transition: all 0.3s ease-in-out;
  }

  select {
    cursor: pointer;
  }

  .checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  input[type="checkbox"] {
    transform: scale(1.2);
    margin-right: 5px;
    accent-color: #007bff;
  }

  button {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    cursor: pointer;
    border: none;
    padding: 10px 15px;
    font-weight: bold;
    border-radius: 6px;
    transition: transform 0.2s ease-in-out;
    text-align: center;
  }

  button:hover {
    background: linear-gradient(#0056b3, #004494);
    transform: scale(1.05);
  }

  @media (max-width: 768px) {
    form {
      flex-direction: column;
      align-items: flex-start;
    }

    button {
      width: 100%;
    }
  }
</style>
@section('content')

<!-- Button to Open Modal -->


<!-- Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
  aria-hidden="true">
  <div class=" modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Category Form -->
        <form id="productForm">
          <div class="row">
            <div class="form-group col-lg-6 ">
              <label for="item_code">Item Code</label>
              <input type="text" name="item_code" class="form-control" id="item_code" placeholder="Enter Item Code">
              <small class="text-danger d-none" id="itemCodeError">Item code is required.</small>
            </div>
            <div class="form-group col-lg-6">
              <label for="item_name">Item Name</label>
              <input type="text" name="item_name" class="form-control" id="item_name" placeholder="Enter Item Name">
            </div>
            <div class="form-group col-lg-6">
              <label for="category_id">Category</label>
              <select name="category_id" class="form-control" id="category_id">
                <option value="" disabled selected>Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
              <small class="text-danger d-none" id="categoryIdError">Category is required.</small>
            </div>
            <div class="form-group col-lg-6">
              <label for="supplier_id">Supplier</label>
              <select name="supplier_id" class="form-control" id="supplier_id">
                <option value="" disabled selected>Select Supplier</option>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                @endforeach
              </select>
              <small class="text-danger d-none" id="supplierIdError">Supplier is required.</small>
            </div>

            <div class="form-group col-lg-6">
              <label for="selling_price">Selling Price</label>
              <input type="number" name="selling_price" class="form-control" id="selling_price"
                placeholder="Enter Selling Price">
              <small class="text-danger d-none" id="sellingPriceError">Selling price is required.</small>
            </div>
            <div class="form-group col-lg-6">
              <label for="original_price">Original Price</label>
              <input type="number" name="original_price" class="form-control" id="original_price"
                placeholder="Enter Original Price">
              <small class="text-danger d-none" id="originalPriceError">Original price is required.</small>
            </div>

            <div class="form-group col-lg-6">
              <label for="qty">Quantity</label>
              <input type="number" name="qty" class="form-control" id="qty" placeholder="Enter Quantity">
              <small class="text-danger d-none" id="qtyError">Quantity is required.</small>
            </div>

            <div class="col-lg-12 mx-auto d-flex justify-content-center">
              <button type="submit" class="btn btn-primary " id="submitBtn" data-action="add">Save Product</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<div class="row mt-5">
  <form action="{{ route('supplier_category.filter') }}" method="GET">
    <div class="filter-group">
        <label>Supplier:
            <select name="supplier_id" class="select2 w-100" >
             <option value=""></option>
             @foreach($suppliers ?? [] as $supplier)
             <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
             @endforeach
            </select>
        </label>
        
        <label>Category:
            <select name="category_id" class="select2 w-100">
                <option value=""></option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" >{{ $category->name }}</option>
                @endforeach
            </select>
        </label>
    </div>
    <button type="submit">Apply Filter</button>
</form>
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex col-6 justify-content-start">
          <h3 class="card-title">All Product</h3>
        </div>
        <div class="d-flex col-6 justify-content-end">

          <button type="button" class="btn btn-primary" id="addCategoryBtn">
            Add Product
          </button>
        </div>
      </div>

      <!-- /.card-header -->
      <div class="card-body">
        <div class="container-fluid">
          <div class="row text-right">
            <a href="{{route('product_all')}}" class="btn btn-success view-product">
              <i class="fa fa-box"></i> All Product
            </a>
          </div>
          <table class="table table-hover w-100" id="example1">
            <thead class="bg-primary">
              <tr>
                <th>#Sr.No</th>
                <th>Item Name</th>
                <th>Item Code</th>
                <th>Original price</th>
                <th>Selling price</th>
                <th>Quantity</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="tableHolder">
              @foreach($products as $product)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->item_name }}</td>
                <td>{{ $product->item_code }}</td>
                <td>{{ $product->original_price }}</td>
                <td>{{ $product->selling_price }}</td>
                <td>{{ $product->qty }}</td>
                <td>
                  <a href="javascript:void(0)" class="btn btn-primary edit-product"
                    data-id="{{ $product->id }}">Edit</a>
                  <a href="javascript:void(0)" class="btn btn-danger delete-product"
                    data-id="{{ $product->id }}">Delete</a>

                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
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
      const url = actionType === 'add' ? "{{ route('add.products') }}" : '/products/' + $('#submitBtn').data('id');
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
            text: actionType === 'add' ? 'Product added successfully!' : 'Product updated successfully!',
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
              $('#' + errorElementId).text(errors[key][0]).removeClass('d-none');
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
    $(function () {
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
          $('#submitBtn').text('Update Product').data('action', 'edit').data('id', productId);
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
              let url = "{{ route('show.products') }}?t=" + new Date().getTime();
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