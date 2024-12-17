@extends('index')

@section('content')

<!-- Button to Open Modal -->

<!-- Modal -->

<form id="productForm" action="{{route('transaction.sales')}}" method="POST">
  @csrf <!-- Include CSRF token for Laravel -->
  <div class="card-body">
    <div class="row">
      <!-- Products Dropdown -->
      <div class="col-md-12">
        <div class="form-group">
          <label for="productSelect">Products</label>
          <select id="productSelect" name="product_id" class="form-control select2" style="width: 100%;">
            @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->item_name }} -- Product Price {{ $product->selling_price }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- Quantity Input -->
      <div class="col-md-4">
        <div class="form-group">
          <label for="quantity">Quantity</label>
          <input type="number" id="quantity" name="quantity" value="1" class="form-control" placeholder="Enter Quantity" min="1">
        </div>
      </div>

      <!-- Discount Input -->
      <div class="col-md-4">
        <div class="form-group">
          <label for="discount">Discount</label>
          <input type="number" id="discount" name="discount" value="0" class="form-control" placeholder="Enter Discount" min="0">
        </div>
      </div>

      <!-- Service Input -->
      <div class="col-md-4">
        <div class="form-group">
          <label for="service">Service</label>
          <input type="text" id="service" name="service" value="0" class="form-control" placeholder="Enter Service" min="0">
        </div>
      </div>
    </div>
    <div class="row">
      <!-- Save Button -->
      <div class="col-md-6">
        <div class="form-group">
          <a href="javascript:void(0);" id="saveButton" class="btn btn-primary w-50">Save</a>
        </div>
      </div>
    </div>
  </div>
</form>


<div class="row">
  <div class="col-12">
    <div class="card">


      <!-- /.card-header -->
      <div class="card-body">
        <form action="{{route('sale.store')}}" method="post">
          @csrf
          <table class="table table-bordered w-100">
            <thead class="bg-primary text-white">
                  <tr>
                        <th>#Sr.No</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Quantity</th>
                        <th>Discount</th>
                        <th>Service Charges</th>
                        <th>Amount</th>
                        <th>Actions</th>
                  </tr>
            </thead>
            <tbody id="tableHolder">
              @foreach($transactions as $transaction)
              <tr>
                     <td>{{ $loop->iteration }}
                      <input type="hidden" value="{{ $transaction->id }}" name="transaction_id[]" id="transaction_id">
                     </td>
                      <td>{{ $transaction->products->item_name }}</td>
                      <td>{{ $transaction->products->selling_price }}</td>
                      <td>{{ $transaction->quantity }}</td>
                      <td>{{ $transaction->discount }}</td>
                      <td>{{ $transaction->service_charges }}</td>
                      <td>{{ $transaction->total_amount }}</td>
                       <td>
                        <a href="javascript:void(0)" class="btn btn-mini btn-warning delete-sale" data-id="{{ $transaction->id }}">
                          <i class="fas fa-trash"></i> Cancel
                        </a>
                    </td>
              </tr>
              @endforeach

            <tr>
            <th colspan="7" style="text-align: right; font-weight: bold;">Total Discount:</th>
            <td colspan="5" style="text-align: right; font-weight: bold;">{{ $transactions->sum('discount') }}
              <input type="hidden" value="{{ $transactions->sum('discount') }}" name="total_discount" id="total_discount">
            </td>
            </tr>
            <tr>
              <th colspan="7" style="text-align: right; font-weight: bold;">Total Amount:</th>
              <td colspan="6" style="text-align: right; font-weight: bold;">{{ $transactions->sum('total_amount') }}
                <input type="hidden" value="{{ $transactions->sum('total_amount') }}" name="total_amount" id="total_amount">
              </td>
            </tr>
            <tr>
              <th colspan="8" style=" text-align: center; font-weight: bold;" class="bg-primary"> Customer Detail</th>
            </tr>

            <input type="hidden" class="form-control" id="customerId" name="customer_id">

            <tr>
              <td colspan="8">
                <div class="form-group ">
                  <label for="customerName">Name</label>
                  <input type="text" class="form-control" id="customerName" name="name" placeholder="Enter customer name">
                  <ul id="customerList" class="list-group"></ul>
                </div>


                <div class="form-group">
                  <label for="customerCNIC">CNIC</label>
                  <input type="text" class="form-control" id="customerCNIC" name="cnic" placeholder="Enter CNIC (e.g., 12345-6789012-3)">
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="8">
                <div class="form-group">
                  <label for="customerMobile">Mobile Number</label>
                  <input type="text" class="form-control" id="customerMobile" name="mobile_number" placeholder="Enter mobile number">
                </div>


                <div class="form-group">
                  <label for="customerAddress">Address</label>
                  <textarea class="form-control" id="customerAddress" rows="3" name="address" placeholder="Enter address"></textarea>
                </div>
              </td>
            </tr>
            </tbody>

          </table>
          <div class="form-group">
            <label for="Cash">Cash</label>
            <input type="text" class="form-control" id="Cash" name="cash" placeholder="Enter the cash">
          </div>
          <div class="d-flex justify-content-center mt-1">
            <button type="submit" class="btn btn-primary w-50" data-toggle="modal" data-target="#customerFormModal">Save</button>
          </div>
        </form>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
</div>

<script>
  $(document).ready(function() {


    $(document).on('click', '#saveButton', function(e) {
      e.preventDefault();

      // Serialize form data
      const formData = $('#productForm').serialize();

      // Send data to the server via AJAX
      $.ajax({
        url: $('#productForm').attr('action'), // Form action URL
        method: 'POST',
        data: formData,
        success: function(response) {
          let newRow = `
                    <tr>
                        <td>${response.id}</td>
                        <td>${response.product_name}</td>
                        <td>${response.quantity}</td>
                        <td>${response.discount}</td>
                        <td>${response.service}</td>
                    </tr>
                `;
          $('#tableHolder').append(newRow);

          $('#productForm')[0].reset();

          let url = "{{ route('show.transaction') }}?t=" + new Date().getTime();
          refreshtble(url);
        },
        error: function(xhr) {
          alert('An error occurred: ' + xhr.responseText);
        }
      });
    });
    $(document).on('click', '.delete-sale', function() {
      const transactionId = $(this).data('id');

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
            url: '/transaction/' + transactionId, // Endpoint for deletion
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
              let url = "{{ route('show.transaction') }}?t=" + new Date().getTime();
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

    function refreshtble(url) {
      $("#tableHolder").load(url + " #tableHolder > *");
    }



    $('#customerName').on('input', function() {
      var customerName = $(this).val();

      if (customerName) {
        $.ajax({
          url: "{{ route('search.customer') }}",
          method: 'GET',
          data: {
            name: customerName
          },
          success: function(response) {
            if (response.status === 'success' && response.data ) {
              var customerListHTML = '';
              response.data.forEach(function(customer) {
                customerListHTML += `
                            <li class="list-group-item customer-item"
                                data-cnic="${customer.cnic}"
                                data-mobile="${customer.mobile_number}"
                                data-cash="${customer.cash}"
                                data-address="${customer.address}" data-id="${customer.id}" >
                                ${customer.name}
                            </li>`;
              });

              $('#customerList').html(customerListHTML).show();
            } else {
              // No matching customers - clear fields
              $('#customerCNIC, #customerMobile, #customerCash, #customerAddress').val('');
              $('#customerList').hide();
            }
          },
          error: function() {
            // Error in fetching - clear fields
            $('#customerCNIC, #customerMobile, #customerCash, #customerAddress,#customerId').val('');
            $('#customerList').hide();
            alert('Error fetching customer data.');
          }
        });
      } else {
        // Empty input - clear all fields
        $('#customerCNIC, #customerMobile, #customerCash, #customerAddress,#customerId').val('');
        $('#customerList').hide();
      }
    });

    // Select customer from list
    $(document).on('click', '.customer-item', function() {
      $('#customerName').val($(this).text());
      $('#customerCNIC').val($(this).data('cnic'));
      $('#customerMobile').val($(this).data('mobile'));
      $('#customerCash').val($(this).data('cash'));
      $('#customerAddress').val($(this).data('address'));
      $('#customerId').val($(this).data('id'));

      $('#customerList').hide();
    });

    // Hide list when clicking outside
    $(document).on('click', function(event) {
      if (!$(event.target).closest('#customerName, #customerList').length) {
        $('#customerList').hide();
      }
    });


  });
</script>

@stop