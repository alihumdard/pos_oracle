@extends('index')

@section('content')

<!-- Button to Open Modal -->

<!-- Modal -->

<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Category Form -->
        <form id="categoryForm">
          <div class="form-group">
            <label for="categoryName">Category Name</label>
            <input type="text" name="name" class="form-control" id="categoryName" placeholder="Enter category name">
            <small class="text-danger d-none" id="nameError">Category name is required.</small>
            <small class="text-danger d-none" id="duplicateError">Category name already exists.</small>
          </div>
          <button type="submit" class="btn btn-primary" id="submitBtn">Save Category</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row mt-5">
  <div class="col-12">
    <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="d-flex col-6 justify-content-start" >
        <h3 class="card-title">All Categories</h3>
      </div>
      <div class="d-flex col-6 justify-content-end" >

        <button type="button" class="btn btn-primary" id="addCategoryBtn">
            Add Category
        </button>
</div>
    </div>

      <!-- /.card-header -->
      <div class="card-body">
        <table class="table table-hover text-nowrap" id="example1">
          <thead class="bg-primary">
            <tr>
              <th>#Sr.No</th>
              <th>Name</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tableHolder">
            @foreach($categories as $category)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $category->name }}</td>
              <td>
                <a href="javascript:void(0)" class="btn btn-primary edit-category" data-id="{{ $category->id }}">Edit</a>
                <a href="javascript:void(0)" class="btn btn-danger delete-category" data-id="{{ $category->id }}">Delete</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>

<script>

$(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": true,"scrollY": true,"scrollX": false, 
      "buttons": [ "excel", "pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
$(document).ready(function () {
  // Open modal for adding a new category
  $('#addCategoryBtn').click(function () {
    $('#addCategoryModalLabel').text('Add New Category');
    $('#categoryForm')[0].reset(); // Reset form fields
    $('#nameError').addClass('d-none'); // Hide validation errors
    $('#duplicateError').addClass('d-none');
    $('#submitBtn').text('Save Category').data('action', 'add'); // Set button action
    $('#addCategoryModal').modal('show');
  });

  // Form submit handler
  $('#categoryForm').submit(function (e) {
    e.preventDefault();

    const actionType = $('#submitBtn').data('action');
    const categoryName = $('#categoryName').val().trim();
    const url = actionType === 'add' ? '{{ route('add.categories') }}' : '/categories/' + $('#submitBtn').data('id'); // Dynamic URL for add or update

    // Frontend validation
    if (!categoryName) {
      $('#nameError').removeClass('d-none');
      return;
    } else {
      $('#nameError').addClass('d-none');
    }

    // AJAX request for add or update
    $.ajax({
      url: url,
      type: actionType === 'add' ? 'POST' : 'PUT',
      data: {
        _token: '{{ csrf_token() }}',
        name: categoryName,
      },

      success: function (response) {
        Swal.fire({
            title: "Good job!",
            text: actionType === 'add' ? 'Category added successfully!' : 'Category updated successfully!',
            icon: "success",
        });
        $('#addCategoryModal').modal('hide');
        let url = "{{ route('show.categories') }}?t=" + new Date().getTime();
        refreshtble(url)
      },
      error: function (xhr) {
        if(xhr.status === 422) { 
          $('#duplicateError').removeClass('d-none');
          Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Something went wrong!",
      footer: '<a href="#">Validation error occurred. Please check your inputs</a>'
    });
        } 
        else{
          Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Something went wrong!",
          footer: '<a href="#"> An error occurred. Please try again.</a>'
        });
        }
      },
    });
  });

  function refreshtble(url){
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

  // Edit category logic
  $(document).on('click', '.edit-category', function () {
    const categoryId = $(this).data('id');
    $.ajax({
      url: '/categories/' + categoryId,
      type: 'GET',
      success: function (response) {
        $('#addCategoryModalLabel').text('Edit Category');
        $('#categoryName').val(response.name);
        $('#nameError').addClass('d-none');
        $('#duplicateError').addClass('d-none');
        $('#submitBtn').text('Update Category').data('action', 'edit').data('id', categoryId);
        $('#addCategoryModal').modal('show');
        function refreshtble(url){
      $("#tableHolder").load(url + " #tableHolder > *");
  }
      },
      error: function () {
        alert('Error fetching category details.');
      },
    });
  });
  $(document).on('click', '.delete-category', function () {
    const categoryId = $(this).data('id');

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
                url: '/categories/' + categoryId, // Endpoint for deletion
                type: 'DELETE', // HTTP DELETE method
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function (response) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Product deleted successfully!",
                        icon: "success",
                    });

                    // Refresh the table after deletion
                    let url = "{{ route('show.categories') }}?t=" + new Date().getTime();
                    refreshtble(url);
                },
                  error: function (xhr) {
                      // Handle errors (e.g., if the product could not be deleted)
                      Swal.fire({
                          icon: "error",
                          title: "Oops...",
                          text: "Something went wrong!",
                          footer: '<a href="#">An error occurred while deleting the category. Please try again.</a>'
                      });
                  }
              });
                } else {
                    // Optional: Handle cancellation (e.g., do nothing)
                    Swal.fire({
                        title: "Cancelled",
                        text: "The Category is safe.",
                        icon: "info"
                    });
                }
            });

        });

// Function to refresh the table
function refreshtble(url) {
    $("#tableHolder").load(url + " #tableHolder > *");
}


});
</script>

@stop
