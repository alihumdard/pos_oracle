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
  select, input[type="checkbox"], button {
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
      background: linear-gradient( #0056b3, #004494);
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

<div class="modal fade" id="addCutomerModal" tabindex="-1" role="dialog" aria-labelledby="addCutomerModalLabel"
  aria-hidden="true">
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
    <div class="card ">
      <div>
        <h2 class="text-center mt-3">Customer Detail</h2>
      </div>
      <div class="row ">
        <form action="{{ route('customer.filter') }}" method="GET">
          @csrf
          <label>Sort By:</label>
          <select name="sort_order">
              <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Low to High</option>
              <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>High to Low</option>
          </select>
          <div class="checkbox-group">
            <label><input type="checkbox" name="filter_debit" {{ request('filter_debit') ? 'checked' : '' }}> Debit</label>
            <label><input type="checkbox" name="filter_credit" {{ request('filter_credit') ? 'checked' : '' }}> Credit</label>
            <label><input type="checkbox" name="hide_zero_balance" {{ request('hide_zero_balance') ? 'checked' : '' }}> Hide Zero Balance</label>
        </div>
          <button type="submit">Apply Filter</button>
      </form>
      
      
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
                  <th>Actions</th> <!-- Removed colspan="3" -->
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
    // Initializing DataTable
    initDataTable();

    // Open modal for adding a new customer
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
            return key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase()) + 'Error';
        }

        Object.keys(formData).forEach(function(key) {
            const value = formData[key];
            const errorElement = $('#' + getErrorElementId(key));
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
                if(response.status == 'success') {
                    Swal.fire({
                        title: "Good job!",
                        text: actionType === 'add' ? 'Customer added successfully!' : 'Customer updated successfully!',
                        icon: "success",
                    });
                    $('#addCutomerModal').modal('hide');
                    refreshTable();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.cnic) {
                        $('#cnicError').text(errors.cnic[0]).removeClass('d-none');
                    }
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

    // Refresh the table content and reinitialize DataTable
    function refreshTable() {
        $("#tableHolder").load("{{ route('show.customers') }} #tableHolder > *", function() {
            // Reinitialize DataTable after loading new content
            initDataTable();
        });
    }

    // Initialize DataTable function
    function initDataTable() {
        // Check agar table exist karti hai tab hi DataTable initialize karo
        if ($('#example1').length) {
            if ($.fn.DataTable.isDataTable('#example1')) {
                // Destroy previous instance of DataTable
                $('#example1').DataTable().clear().destroy();
            }

            // Re-initialize DataTable with options
            $('#example1').DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: true,
                scrollY: true,
                scrollX: false,
                // buttons: ["excel", "pdf"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        } else {
            console.log('Table #example1 not found in DOM, skipping initialization');
        }
    }

    // Edit customer logic
    $(document).on('click', '.edit-customer', function() {
        const customerId = $(this).data('id');
        $.ajax({
            url: '/customers/' + customerId,
            type: 'GET',
            success: function(response) {
                $('#addCutomerModalLabel').text('Edit Customer');
                $('#customerForm')[0].reset();
                $('#submitBtn').text('Update Customer').data('action', 'edit').data('id', customerId);
                $('#name').val(response.customer.name);
                $('#mobile_no').val(response.customer.mobile_number);
                $('#address').val(response.customer.address);
                $('#cnic').val(response.customer.cnic);
                $('#addCutomerModal').modal('show');
            },
            error: function() {
                alert('Error fetching customer details.');
            },
        });
    });

    // Delete customer logic
    $(document).on('click', '.delete-customer', function() {
        const customerId = $(this).data('id');
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
                    url: '/customers/' + customerId,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire({
                            title: "Good job!",
                            text: "Customer deleted successfully!",
                            icon: "success",
                        });
                        refreshTable();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong while deleting the customer. Please try again.",
                        });
                    }
                });
            }
        });
    });
});

</script>

@stop