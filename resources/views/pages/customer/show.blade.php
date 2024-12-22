@extends('index')

@section('content')

<div class="modal fade" id="addCutomerModal" tabindex="-1" role="dialog" aria-labelledby="addCutomerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCutomerModalLabel">Add New Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Category Form -->
        <form id="customerForm">
          <div class="form-group">
            <label for="name">Customer Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
            <small class="text-danger d-none" id="nameError">Name is required.</small>
          </div>
          <div class="form-group">
            <label for="cnic">CNIC</label>
            <input type="text" name="cnic" class="form-control" id="cnic" placeholder="Enter the CNIC">
            <small class="text-danger d-none" id="cnicError">CNIC is required.</small>
          </div>
          <div class="form-group">
            <label for="mobile_no">Mobile No</label>
            <input type="text" name="mobile_no" class="form-control" id="mobile_no" placeholder="Enter Mobile No">
            <small class="text-danger d-none" id="mobileNoError">Mobile number is required.</small>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" name="address" class="form-control" id="address" placeholder="Enter Address">
            <small class="text-danger d-none" id="addressError">Address is required.</small>
          </div>
        
          <button type="submit" class="btn btn-primary" id="submitBtn" data-action="add">Save Customer</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="row mt-5">
  <div class="col-12">
    <div class="card">
      <div>
        <h2 class="text-center mt-3">Cutomer Detail</h2>
      </div>
      <div class="row">
        <div class="col text-right">
          <button type="button" class="btn btn-primary" id="addCustomerBtn">
            Add Customer
          </button>
        </div>
      </div>
      <div class="card-body">

        <table class="table table-hover w-100" id="example1">
          <thead class="bg-primary">
            <tr>
              <th>#Sr.No</th>
              <th>Customer Name</th>
              <th>Mobile Number</th>
              <th>Address</th>
              <th>CNIC</th>
              <th>Debit</th>
              <th>Credit</th>
              <th colspan="3">Actions</th>
            </tr>
          </thead>
          <tbody id="tableHolder">
            @foreach($customers as $customer)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $customer->name }}</td>
              <td>{{ $customer->mobile_number }}</td>
              <td>{{ $customer->address }}</td>
              <td>{{ $customer->cnic }}</td>
              <td>{{ $customer->debit }}</td>
              <td>{{ $customer->credit }}</td>

              <td>
                <a href="{{ route('customer.view', ['id' => $customer->id]) }}" class="btn btn-sm btn-primary">
                  <i class="fa fa-eye"></i> View
                </a>
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
  $(function() {
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": true,
      "autoWidth": true,
      "scrollY": true,
      "scrollX": false,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
  $(document).ready(function() {
    // Open modal for adding a new category
    $('#addCustomerBtn').click(function() {
      $('#addCutomerModalLabel').text('Add New Customer');
      $('#customerForm')[0].reset(); // Reset form fields
      $('#nameError').addClass('d-none'); // Hide validation errors
      $('#duplicateError').addClass('d-none');
      $('#submitBtn').text('Add Customer').data('action', 'add'); // Set button action
      $('#addCutomerModal').modal('show');
    });

    // Form submit handler
    $('#customerForm').submit(function(e) {
      e.preventDefault();

      const actionType = $('#submitBtn').data('action');
      const url = actionType === 'add' ? '{{ route("add.customers") }}' : '/suppliers/' + $('#submitBtn').data('id'); // Dynamic URL for add or update
      const formData = {
        _token: '{{ csrf_token() }}',
        name: $('#name').val().trim(),
        mobile_no: $('#mobile_no').val().trim(),
        address: $('#address').val().trim(),
        cnic: $('#cnic').val().trim(),
      };
      // Frontend validation
      let hasError = false;

      // Helper function to map field names to error message IDs
      function getErrorElementId(key) {
        // Convert snake_case to camelCase for error IDs
        return key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase()) + 'Error';
      }

      Object.keys(formData).forEach(function(key) {
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
        success: function(response) {
          if(response.status=='success')
        {
          Swal.fire({
            title: "Good job!",
            text: actionType === 'add' ? 'Customer added successfully!' : 'Supplier updated successfully!',
            icon: "success",
          });
          $('#addCutomerModal').modal('hide');
          let url = "{{ route('show.customers') }}?t=" + new Date().getTime();
          refreshtble(url);
        }
        },
        error: function(xhr) {
          if (xhr.status === 'error') {
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
    $(document).on('click', '.edit-customer', function() {
      const supplierId = $(this).data('id');
      $.ajax({
        url: '/suppliers/' + supplierId,
        type: 'GET',
        success: function(response) {
          $('#addCutomerModalLabel').text('Edit Supplier');

          $('#customer').val(response.customer);
          $('#contact_person').val(response.contact_person);
          $('#address').val(response.address);
          $('#contact_no').val(response.contact_no);
          $('#note').val(response.note);
          $('#nameError').addClass('d-none');
          $('#duplicateError').addClass('d-none');
          $('#submitBtn').text('Update Supplier').data('action', 'edit').data('id', supplierId);
          $('#addCutomerModal').modal('show');

          function refreshtble(url) {
            $("#tableHolder").load(url + " #tableHolder > *");
          }
        },
        error: function() {
          alert('Error fetching Supplier details.');
        },
      });
    });
    $(document).on('click', '.delete-customer', function() {
      const supplierId = $(this).data('id');

      Swal.fire({
        title: "Are you sure?",
        text: "Do you really want to delete this customer?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '/customer/' + supplierId, // Endpoint for deletion
            type: 'DELETE', // HTTP DELETE method
            data: {
              _token: '{{ csrf_token() }}' // Include CSRF token for security
            },
            success: function(response) {
              Swal.fire({
                title: "Good job!",
                text: "Supplier deleted successfully!",
                icon: "success",
              });

              // Refresh the table after deletion
              let url = "{{ route('show.suppliers') }}?t=" + new Date().getTime();
              refreshtble(url);
            },
            error: function(xhr) {
              // Handle errors (e.g., if the customer could not be deleted)
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong!",
                footer: '<a href="#">An error occurred while deleting the customer. Please try again.</a>'
              });
            }
          });
        } else {
          // Optional: Handle cancellation (e.g., do nothing)
          Swal.fire({
            title: "Cancelled",
            text: "The customer is safe.",
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