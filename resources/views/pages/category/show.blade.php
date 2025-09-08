@extends('index')

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
<!-- Button to Open Modal -->

<!-- Modal -->

<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
    aria-hidden="true">
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
                        <input type="text" name="name" class="form-control" id="categoryName"
                            placeholder="Enter category name">
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
        <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
            <!-- Card Header -->
            <div
                class=" d-flex justify-content-between align-items-center flex-wrap bg-gradient-to-r from-blue-500 to-blue-700 p-3">
                <h3 class="card-title text-white text-lg font-semibold m-0">
                    <i class="fa fa-list me-2"></i> All Categories
                </h3>
                <button type="button" class="btn btn-light d-flex align-items-center gap-2 shadow-sm" id="addCategoryBtn">
                    <i class="fa fa-plus text-blue-600"></i> Add Category
                </button>
            </div>

            <!-- Card Body -->
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="example1">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 80px;">#Sr.No</th>
                                <th>Category Name</th>
                                <th class="text-center" style="width: 200px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableHolder">
                            @foreach($categories as $category)
                            <tr>
                                <td class="fw-semibold">{{ $loop->iteration }}</td>
                                <td class="text-dark fw-medium">{{ $category->name }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="javascript:void(0)" 
                                           class="btn btn-sm btn-outline-primary edit-category d-flex align-items-center gap-1"
                                           data-id="{{ $category->id }}">
                                           <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a href="javascript:void(0)" 
                                           class="btn btn-sm btn-outline-danger delete-category d-flex align-items-center gap-1"
                                           data-id="{{ $category->id }}">
                                           <i class="fa fa-trash"></i> Delete
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


<script>
$(function() {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": true,
        "scrollY": true,
        "scrollX": false,
        "buttons": ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});
$(document).ready(function() {
    // Open modal for adding a new category
    $('#addCategoryBtn').click(function() {
        $('#addCategoryModalLabel').text('Add New Category');
        $('#categoryForm')[0].reset();
        $('#nameError').addClass('d-none');
        $('#duplicateError').addClass('d-none');
        $('#submitBtn').text('Save Category').data('action', 'add');
        $('#addCategoryModal').modal('show');
    });

    // Form submit handler
    $('#categoryForm').submit(function(e) {
        e.preventDefault();

        const actionType = $('#submitBtn').data('action');
        const categoryName = $('#categoryName').val().trim();
        const url = actionType === 'add' ? '{{ route("add.categories") }}' : '/categories/' + $(
            '#submitBtn').data('id'); // Dynamic URL for add or update

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

            success: function(response) {
                Swal.fire({
                    title: "Good job!",
                    text: actionType === 'add' ? 'Category added successfully!' :
                        'Category updated successfully!',
                    icon: "success",
                });
                $('#addCategoryModal').modal('hide');
                let url = "{{ route('show.categories') }}?t=" + new Date().getTime();
                refreshtble(url)
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $('#duplicateError').removeClass('d-none');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                        footer: '<a href="#">Validation error occurred. Please check your inputs</a>'
                    });
                } else {
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

    // Edit category logic
    $(document).on('click', '.edit-category', function() {
        const categoryId = $(this).data('id');
        $.ajax({
            url: '/categories/' + categoryId,
            type: 'GET',
            success: function(response) {
                $('#addCategoryModalLabel').text('Edit Category');
                $('#categoryName').val(response.name);
                $('#nameError').addClass('d-none');
                $('#duplicateError').addClass('d-none');
                $('#submitBtn').text('Update Category').data('action', 'edit').data('id',
                    categoryId);
                $('#addCategoryModal').modal('show');

                function refreshtble(url) {
                    $("#tableHolder").load(url + " #tableHolder > *");
                }
            },
            error: function() {
                alert('Error fetching category details.');
            },
        });
    });
    $(document).on('click', '.delete-category', function() {
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
                    success: function(response) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Product deleted successfully!",
                            icon: "success",
                        });

                        // Refresh the table after deletion
                        let url = "{{ route('show.categories') }}?t=" + new Date()
                            .getTime();
                        refreshtble(url);
                    },
                    error: function(xhr) {
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