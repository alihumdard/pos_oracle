@extends('index')

@section('content')

<!-- Button to Open Modal -->

<!-- Modal -->

<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSupplierModalLabel">Add New Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Category Form -->
        <form id="supplierForm">
    <div class="form-group">
        <label for="supplier">Customer Name</label>
        <input type="text" name="name" class="form-control" id="name" placeholder="Enter fullname">
        <small class="text-danger d-none" id="nameError">Name is required.</small>
    </div>
   
   
    <div class="form-group">
        <label for="mobile_no">Mobile No</label>
        <input type="number" name="mobile_no" class="form-control" id="mobile_no" placeholder="Enter Mobile No">
        <small class="text-danger d-none" id="mobileNoError">Mobile number is required.</small>
    </div>
    <div class="form-group">
        <label for="cnic">CNIC</label>
        <input type="number" name="cnic" class="form-control" id="cnic" placeholder="Enter Contact No">
        <small class="text-danger d-none" id="cnicError">CNIC number is required.</small>
    </div>
    <div class="form-group">
        <label for="cnic">Debit</label>
        <input type="number" name="cnic" class="form-control" id="cnic" placeholder="Enter Contact No">
        <small class="text-danger d-none" id="cnicError">CNIC number is required.</small>
    </div>

    <button type="submit" class="btn btn-primary" id="submitBtn" data-action="add">Save Supplier</button>
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
        <h3 class="card-title">All Suppliers</h3>
      </div>
      <div class="d-flex col-6 justify-content-end" >

        <button type="button" class="btn btn-primary" id="addSupplierBtn">
            Add Supplier
        </button>
</div>
    </div>

      <!-- /.card-header -->
      <div class="card-body">
        <table class="table table-hover text-nowrap" id="example1">
          <thead>
            <tr>
              <th>#Sr.No</th>
              <th>Supplier Name</th>
              <th>Contact Person</th>
              <th>Address</th>
             
              <!-- <th>Contact No</th> -->
             
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tableHolder">
            @foreach($customers as $customer)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $customer->supplier }}</td>
              <td>{{ $customer->contact_person }}</td>
              <td>{{ $customer->address }}</td>
              
             
              <td>
                <a href="javascript:void(0)" class="btn btn-primary edit-supplier" data-id="{{ $supplier->id }}">Edit</a>
                <a href="javascript:void(0)" class="btn btn-danger delete-supplier" data-id="{{ $supplier->id }}">Delete</a>
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
  $('#addSupplierBtn').click(function () {
    $('#addSupplierModalLabel').text('Add New Supplier');
    $('#supplierForm')[0].reset(); // Reset form fields
    $('#nameError').addClass('d-none'); // Hide validation errors
    $('#duplicateError').addClass('d-none');
    $('#submitBtn').text('Save Supplier').data('action', 'add'); // Set button action
    $('#addSupplierModal').modal('show');
  });

  // Form submit handler
  $('#supplierForm').submit(function (e) {
    e.preventDefault();

    const actionType = $('#submitBtn').data('action');
    const url = actionType === 'add' ? '{{ route("add.suppliers") }}' : '/suppliers/' + $('#submitBtn').data('id'); // Dynamic URL for add or update
    const formData = {
    _token: '{{ csrf_token() }}',
    supplier: $('#supplier').val().trim(),
    contact_person: $('#contact_person').val().trim(),
    address: $('#address').val().trim(),
    contact_no: $('#contact_no').val().trim(),
    note: $('#note').val().trim(),
  };
  // Frontend validation
  let hasError = false;

// Helper function to map field names to error message IDs
 function getErrorElementId(key) {
    // Convert snake_case to camelCase for error IDs
    return key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase()) + 'Error';
}

 Object.keys(formData).forEach(function (key) {
    const value = formData[key];
    const errorElement = $('#' + getErrorElementId(key)); // Correctly map to error ID
   console.log(errorElement);
    if (!value && key !== '_token') {
        errorElement.removeClass('d-none');
        hasError = true;
    } else {
        errorElement.addClass('d-none');
    }
});


if (hasError) return; 
    // AJAX request for add or update
    $.ajax({
    url: url,
    type: actionType === 'add' ? 'POST' : 'PUT',
    data: formData,
    success: function (response) {
      Swal.fire({
            title: "Good job!",
            text: actionType === 'add' ? 'Supplier added successfully!' : 'Supplier updated successfully!',
            icon: "success",
        });
      $('#addSupplierModal').modal('hide');
      let url = "{{ route('show.suppliers') }}?t=" + new Date().getTime();
      refreshtble(url);
    },
    error: function (xhr) {
      if (xhr.status === 422) {
        alert('Validation error occurred. Please check your inputs.');
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
  $(document).on('click', '.edit-supplier', function () {
    const supplierId = $(this).data('id');
    $.ajax({
      url: '/suppliers/' + supplierId,
      type: 'GET',
      success: function (response) {
        $('#addSupplierModalLabel').text('Edit Supplier');
      
        $('#supplier').val(response.supplier);
        $('#contact_person').val(response.contact_person);
        $('#address').val(response.address);
        $('#contact_no').val(response.contact_no);
        $('#note').val(response.note);
        $('#nameError').addClass('d-none');
        $('#duplicateError').addClass('d-none');
        $('#submitBtn').text('Update Supplier').data('action', 'edit').data('id', supplierId);
        $('#addSupplierModal').modal('show');
        function refreshtble(url){
      $("#tableHolder").load(url + " #tableHolder > *");
  }
      },
      error: function () {
        alert('Error fetching Supplier details.');
      },
    });
  });
  $(document).on('click', '.delete-supplier', function () {
    const supplierId = $(this).data('id');

    Swal.fire({
    title: "Are you sure?",
    text: "Do you really want to delete this supplier?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "No, cancel!"
    }).then((result) => {
    if (result.isConfirmed) {
        $.ajax({
            url: '/supplier/' + supplierId, // Endpoint for deletion
            type: 'DELETE', // HTTP DELETE method
            data: {
                _token: '{{ csrf_token() }}' // Include CSRF token for security
            },
            success: function (response) {
                Swal.fire({
                    title: "Good job!",
                    text: "Supplier deleted successfully!",
                    icon: "success",
                });

                // Refresh the table after deletion
                let url = "{{ route('show.suppliers') }}?t=" + new Date().getTime();
                refreshtble(url);
            },
            error: function (xhr) {
                // Handle errors (e.g., if the supplier could not be deleted)
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                    footer: '<a href="#">An error occurred while deleting the supplier. Please try again.</a>'
                });
            }
        });
       } else {
        // Optional: Handle cancellation (e.g., do nothing)
        Swal.fire({
            title: "Cancelled",
            text: "The supplier is safe.",
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
