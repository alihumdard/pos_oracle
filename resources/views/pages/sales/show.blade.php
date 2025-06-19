@extends('index')

@section('content')

<div class="row mt-4">
    <div class="col-12 justify-content-end align-items-center d-flex">
        <button class="btn btn-primary" onclick="history.back()">← Back</button>
    </div>
</div>

@php
    $editing = isset($editTransaction);
@endphp

<form id="productForm" action="{{ $editing ? route('transaction.update', $editTransaction->id) : route('transaction.sales') }}" method="POST">
    @csrf
   <input type="hidden" name="_method" id="formMethod" value="POST">

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="productSelect">Products</label>
                    <select id="productSelect" name="product_id" class="form-control select2" multiple="multiple" style="width: 100%;">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                {{ ($editing && $editTransaction->product_id == $product->id) ? 'selected' : '' }}>
                                Item Code({{ $product->item_code }}) -- Product Name ({{ $product->item_name }}) -- Original Price ({{ $product->original_price }}) -- Sale Price ({{ $product->selling_price }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="{{ $editing ? $editTransaction->quantity : old('quantity', 1) }}" class="form-control" min="1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="discount">Discount</label>
                    <input type="number" id="discount" name="discount" value="{{ $editing ? $editTransaction->discount : old('discount', 0) }}" class="form-control" min="0">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="service">Service</label>
                    <input type="text" id="service" name="service" value="{{ $editing ? $editTransaction->service_charges : old('service', 0) }}" class="form-control" min="0">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <button type="button" id="saveTransactionButton" class="btn btn-primary">
                    Save Transaction
                </button>

                <button type="button" id="submitTransactionButton" class="btn btn-warning" style="display: none;">
                    Update Transaction
                </button>
            </div>
        </div>
    </div>
</form>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body" style="overflow-x: scroll;">
       <form id="saleForm" action="{{ isset($sale) ? route('sale.update', $sale->id) : route('sale.store') }}" method="POST">
        @csrf
        <input type="hidden" id="saleMode" value="{{ isset($sale) ? 'edit' : 'create' }}">
        @if(isset($sale))
            <input type="hidden" id="currentSaleId" value="{{ $sale->id }}">
        @endif
          <table class="table table-bordered w-100 " id="example1">
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
                @if(isset($transactions))
                    @foreach($transactions as $transaction)
                    <tr data-id="{{ $transaction->id }}">
                        <td>{{ $loop->iteration }}
                            <input type="hidden" value="{{ $transaction->id }}" name="transaction_id[]" id="transaction_id_{{ $transaction->id }}">
                        </td>
                        <td>{{ $transaction->products->item_name ?? ' '}}</td>
                        <td>{{ $transaction->products->selling_price ?? ' ' }}</td>
                        <td>{{ $transaction->quantity ?? ' ' }}</td>
                        <td>{{ $transaction->discount ?? ' ' }}</td>
                        <td>{{ $transaction->service_charges ?? ' ' }}</td>
                        <td>{{ $transaction->total_amount ?? ' ' }}</td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-sm btn-warning edit-sale"
                                data-id="{{ $transaction->id }}"
                                data-product="{{ $transaction->product_id }}"
                                data-quantity="{{ $transaction->quantity }}"
                                data-discount="{{ $transaction->discount }}"
                                data-service="{{ $transaction->service_charges }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-sale"
                                data-id="{{ $transaction->id }}">
                                <i class="fas fa-trash"></i> Cancel
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="8"><input type="hidden" name="qty" value="{{$transactions->sum('quantity')}}"></td>
                    </tr>
                @endif
              <tr>
                <th colspan="7" style="text-align: right; font-weight: bold;">Total Discount:</th>
                <td colspan="1" id="totalDiscountText" style="text-align: right; font-weight: bold;">{{ isset($transactions) ? $transactions->sum('discount') : 0 }}
                    <input type="hidden" value="{{ isset($transactions) ? $transactions->sum('discount') : 0 }}" name="total_discount"
                        id="total_discount">
                </td>
              </tr>
              <tr>
                <th colspan="7" style="text-align: right; font-weight: bold;">Total Amount:</th>
                <td colspan="1" id="totalAmountText" style="text-align: right; font-weight: bold;">{{ isset($transactions) ? $transactions->sum('total_amount') : 0 }}
                    <input type="hidden" value="{{ isset($transactions) ? $transactions->sum('total_amount') : 0 }}" name="total_amount"
                        id="total_amount">
                </td>
              </tr>
              <tr>
                <th colspan="8" style="text-align: center; font-weight: bold;" class="bg-primary">Customer Detail</th>
              </tr>

             <input type="hidden" class="form-control" id="customerId" name="customer_id" value="{{ old('customer_id', $customer->id ?? '') }}">
              <tr>
                <td colspan="8">
                    <div class="form-group">
                        <label for="customerMobile">Mobile Number</label>
                        <input type="text" class="form-control" id="customerMobile" name="mobile_number"
                            placeholder="Enter mobile number" value="{{ old('mobile_number', $customer->mobile_number ?? '') }}">
                        @error('mobile_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="customerName">Name</label>
                        <input type="text" class="form-control" id="customerName" name="name"
                            placeholder="Enter customer name" value="{{ old('name', $customer->name ?? '') }}">
                        <ul id="customerList"></ul>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if(!isset($sale))
                        <div class="form-group">
                            <label for="customerCNIC">CNIC</label>
                            <input type="text" class="form-control" id="customerCNIC" name="cnic"
                                placeholder="Enter CNIC (e.g., 12345-6789012-3)" value="{{ old('cnic', $customer->cnic ?? '') }}">
                            @error('cnic')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </td>
              </tr>
              <tr>
                <td colspan="8">
                    <div class="form-group">
                        <label for="customerAddress">Address</label>
                        <textarea class="form-control" id="customerAddress" rows="3" name="address"
                            placeholder="Enter address">{{ old('address', $customer->address ?? '') }}</textarea>
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </td>
              </tr>
              <tr>
                <td colspan="8">
                    <div class="form-group">
                        <label for="Cash">Cash</label>
                        <input type="number" class="form-control" id="Cash" name="cash" value="{{ old('cash', $sale->cash ?? '') }}"
                            placeholder="Enter the cash" min="0" required>
                        @error('cash')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="d-flex justify-content-center mt-1">
            <button type="button" id="updateSaleBtn" class="btn {{ isset($sale) ? 'btn-warning' : 'btn-primary' }} w-50">
              {{ isset($sale) ? 'Update' : 'Save' }} Sale
            </button>
          </div>
        </form>
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
      "scrollY": false,
      "scrollX": true,
      "buttons": ["excel", "pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });

  $(function() {
    $('.select2').select2()
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  });

  $(document).ready(function() {

    $(document).on('click', '#saveTransactionButton', function (e) {
        e.preventDefault();

        const productSelected = $('#productSelect').val();
        const quantity = $('#quantity').val();
        const discount = $('#discount').val();
        const service = $('#service').val().trim();
        const form = $('#productForm');
        const actionUrl = form.attr('action');

        if (!productSelected || productSelected.length === 0) {
            Swal.fire("Missing Product", "Please select at least one product.", "warning");
            return;
        }
        if (!quantity || parseFloat(quantity) <= 0) {
            Swal.fire("Invalid Quantity", "Please enter a valid quantity.", "warning");
            return;
        }
        if (discount === '' || parseFloat(discount) < 0) {
            Swal.fire("Invalid Discount", "Please enter a valid discount (0 or more).", "warning");
            return;
        }
        if (service === '' || parseFloat(service) < 0) {
            Swal.fire("Missing or Invalid Service Charge", "Please enter a valid service charge (0 or more).", "warning");
            return;
        }

        let ajaxData = form.serializeArray();
        const saleMode = $('#saleMode').val();
        const genericAddTransactionUrl = "{{ route('transaction.sales') }}";

        if (saleMode === 'edit' && actionUrl === genericAddTransactionUrl) {
            const currentSaleId = $('#currentSaleId').val();

            if (currentSaleId) {
                let saleIdExists = ajaxData.some(item => item.name === 'sale_id');
                if (!saleIdExists) {
                    ajaxData.push({ name: "sale_id", value: currentSaleId });
                }
            } else {
                console.error("Could not determine sale_id in edit mode for new transaction using #currentSaleId input.");
                const saleFormAction = $('#saleForm').attr('action');
                const saleIdMatch = saleFormAction.match(/\/(\d+)(?:\/?)$/);
                if (saleIdMatch && saleIdMatch[1]) {
                    const saleId = saleIdMatch[1];
                    let saleIdExists = ajaxData.some(item => item.name === 'sale_id');
                    if (!saleIdExists) {
                        ajaxData.push({ name: "sale_id", value: saleId });
                    }
                } else {
                     console.error("Fallback: Could not determine sale_id from saleFormAction:", saleFormAction);
                }
            }
        }

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: $.param(ajaxData),
            success: function (response) {
                if (response.status === 'error') {
                    Swal.fire("Save Failed", response.message, "error");
                    return;
                }

                const t = response.transaction_data || response;

                if (!t || typeof t.id === 'undefined') {
                    console.error("Received invalid transaction data from server:", response);
                    Swal.fire("Save Error", "Received incomplete data from server. The page will reload.", "error").then(() => {
                        location.reload();
                    });
                    return;
                }
                
                const currentSrNo = $('#tableHolder tr[data-id]').length;
                const newRowHtml = `
                    <tr data-id="${t.id}">
                        <td>${currentSrNo + 1}
                            <input type="hidden" value="${t.id}" name="transaction_id[]" id="transaction_id_${t.id}">
                        </td>
                        <td>${t.products?.item_name || t.product_name || 'N/A'}</td>
                        <td>${t.products?.selling_price || t.amount || 'N/A'}</td>
                        <td>${t.quantity}</td>
                        <td>${t.discount}</td>
                        <td>${t.service_charges}</td>
                        <td>${t.total_amount}</td>
                        <td>
                            <a href="javascript:void(0)" class="btn btn-sm btn-warning edit-sale"
                                data-id="${t.id}"
                                data-product="${t.product_id}"
                                data-quantity="${t.quantity}"
                                data-discount="${t.discount}"
                                data-service="${t.service_charges}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-sale"
                                data-id="${t.id}">
                                <i class="fas fa-trash"></i> Cancel
                            </a>
                        </td>
                    </tr>`;
                
                if ($('#tableHolder').find('tr:has(th):contains("Total Discount")').length > 0) {
                    $('#tableHolder').find('tr:has(th):contains("Total Discount")').before(newRowHtml);
                } else if ($('#tableHolder').find('tr:has(th):contains("Customer Detail")').length > 0) {
                     $('#tableHolder').find('tr:has(th):contains("Customer Detail")').before(newRowHtml);
                }
                else {
                    $('#tableHolder').append(newRowHtml);
                }

                let totalDiscount = 0;
                let totalAmount = 0;
                let totalQuantity = 0;

                $('#tableHolder tr[data-id]').each(function () {
                    const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
                    const d = parseFloat($(this).find('td:eq(4)').text()) || 0;
                    const a = parseFloat($(this).find('td:eq(6)').text()) || 0;
                    
                    totalQuantity += qty;
                    totalDiscount += d;
                    totalAmount += a;
                });

                $('#total_discount').val(totalDiscount.toFixed(2));
                $('#totalDiscountText').contents().filter(function () { return this.nodeType === 3; }).first().replaceWith(totalDiscount.toFixed(2) + ' ');
                $('#total_amount').val(totalAmount.toFixed(2));
                $('#totalAmountText').contents().filter(function () { return this.nodeType === 3; }).first().replaceWith(totalAmount.toFixed(2) + ' ');
                $('input[name="qty"]').val(totalQuantity);

                $('#productForm')[0].reset();
                $('#productSelect').val(null).trigger('change');
                $('#submitTransactionButton').hide();
                $('#saveTransactionButton').show();
                $('#formMethod').val('POST');
                $('#productForm').attr('action', genericAddTransactionUrl);

                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'Transaction saved successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function (xhr) {
                let message = 'An error occurred while saving the transaction.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try { const err = JSON.parse(xhr.responseText); if (err && err.message) message = err.message; } catch (e) {}
                }
                Swal.fire("Error", message, "error");
            }
        });
    });

    $(document).on('click', '.edit-sale', function () {
        const id = $(this).data('id');
        const productId = $(this).data('product');

        $('#productSelect').val(productId).trigger('change');
        $('#quantity').val($(this).data('quantity'));
        $('#discount').val($(this).data('discount'));
        $('#service').val($(this).data('service'));

        let updateUrl = "{{ route('transaction.update', ['id' => ':id_placeholder']) }}".replace(':id_placeholder', id);
        $('#productForm').attr('action', updateUrl);
        $('#formMethod').val('PUT');

        $('#saveTransactionButton').hide();
        $('#submitTransactionButton').show();
    });

    $(document).on('click', '#submitTransactionButton', function (e) {
        e.preventDefault();

        const productSelected = $('#productSelect').val();
        const quantity = $('#quantity').val();
        const discount = $('#discount').val();
        const service = $('#service').val().trim();
        const form = $('#productForm');
        const formData = form.serialize();
        const actionUrl = form.attr('action');

        if (!productSelected || productSelected.length === 0) { Swal.fire("Missing Product", "Please select a product.", "warning"); return; }
        if (!quantity || parseFloat(quantity) <= 0) { Swal.fire("Invalid Quantity", "Please enter a valid quantity.", "warning"); return; }
        if (discount === '' || parseFloat(discount) < 0) { Swal.fire("Invalid Discount", "Please enter a valid discount.", "warning"); return; }
        if (service === '' || parseFloat(service) < 0) { Swal.fire("Missing Service Charge", "Please enter the service charge.", "warning"); return; }

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'error') {
                    Swal.fire("Update Failed", response.message, "error");
                    return;
                }

                const t = response.transaction_data || response.transaction || response;
                const row = $(`#tableHolder tr[data-id="${t.id}"]`);

                if (row.length) {
                    row.find('td:eq(1)').text(t.products?.item_name || t.product_name || 'N/A');
                    row.find('td:eq(2)').text(t.products?.selling_price || t.amount || 'N/A');
                    row.find('td:eq(3)').text(t.quantity);
                    row.find('td:eq(4)').text(t.discount);
                    row.find('td:eq(5)').text(t.service_charges);
                    row.find('td:eq(6)').text(t.total_amount);

                    const editBtn = row.find('.edit-sale');
                    editBtn.data('product', t.product_id);
                    editBtn.data('quantity', t.quantity);
                    editBtn.data('discount', t.discount);
                    editBtn.data('service', t.service_charges);
                }

                let totalDiscount = 0;
                let totalAmount = 0;
                let totalQuantity = 0;
                $('#tableHolder tr[data-id]').each(function () {
                    const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
                    const d = parseFloat($(this).find('td:eq(4)').text()) || 0;
                    const a = parseFloat($(this).find('td:eq(6)').text()) || 0;
                    totalQuantity += qty;
                    totalDiscount += d;
                    totalAmount += a;
                });
                $('#total_discount').val(totalDiscount.toFixed(2));
                $('#totalDiscountText').contents().filter(function () { return this.nodeType === 3; }).first().replaceWith(totalDiscount.toFixed(2) + ' ');
                $('#total_amount').val(totalAmount.toFixed(2));
                $('#totalAmountText').contents().filter(function () { return this.nodeType === 3; }).first().replaceWith(totalAmount.toFixed(2) + ' ');
                $('input[name="qty"]').val(totalQuantity);
                
                $('#productForm')[0].reset();
                $('#formMethod').val('POST');
                $('#productForm').attr('action', "{{ route('transaction.sales') }}");
                $('#productSelect').val(null).trigger('change');
                $('#submitTransactionButton').hide();
                $('#saveTransactionButton').show();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Transaction Updated',
                    text: response.message || 'Transaction updated successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                let message = 'An error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) { message = xhr.responseJSON.message; }
                Swal.fire("Error", message, "error");
            }
        });
    });

     $(document).on('click', '.delete-sale', function() {
        const transactionId = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this Sale line item?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!"
        }).then((result) => {
            if (result.isConfirmed) {
                let deleteUrl = "{{ route('transaction.destroy', ['id' => ':id_placeholder']) }}".replace(':id_placeholder', transactionId);

                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Deleted!",
                            text: response.message || "Sale line item deleted successfully!",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        row.remove();

                        let totalDiscount = 0;
                        let totalAmount = 0;
                        let totalQuantity = 0;
                        $('#tableHolder tr[data-id]').each(function () {
                             const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
                             const d = parseFloat($(this).find('td:eq(4)').text()) || 0;
                             const a = parseFloat($(this).find('td:eq(6)').text()) || 0;
                             totalQuantity += qty;
                             totalDiscount += d;
                             totalAmount += a;
                        });
                        $('#total_discount').val(totalDiscount.toFixed(2));
                        $('#totalDiscountText').contents().filter(function () { return this.nodeType === 3; }).first().replaceWith(totalDiscount.toFixed(2) + ' ');
                        $('#total_amount').val(totalAmount.toFixed(2));
                        $('#totalAmountText').contents().filter(function () { return this.nodeType === 3; }).first().replaceWith(totalAmount.toFixed(2) + ' ');
                        $('input[name="qty"]').val(totalQuantity);

                        $('#tableHolder tr[data-id]').each(function(index) {
                            let currentTransactionId = $(this).find('input[name="transaction_id[]"]').val();
                            $(this).find('td:first').html(`${index + 1}<input type="hidden" value="${currentTransactionId}" name="transaction_id[]" id="transaction_id_${currentTransactionId}">`);
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong while deleting!",
                            footer: xhr.responseJSON?.message || 'Please try again.'
                        });
                    }
                });
            }
        });
    });


    $('#customerMobile').on('input', function() {
        var customerMobile = $(this).val();
        if (customerMobile.length < 4) {
            $('#customerName, #customerCNIC, #customerAddress, #customerId').val('');
            $('#customerList').empty().hide();
            return;
        }

        if (customerMobile.length >= 4) {
            $.ajax({
                url: "{{ route('search.customer') }}",
                method: 'GET',
                data: {
                    mobile_number: customerMobile
                },
                success: function(response) {
                    if (response.status === 'success' && response.data && response.data.length > 0) {
                        const customer = response.data[0];
                        $('#customerName').val(customer.name);
                        $('#customerCNIC').val(customer.cnic);
                        $('#customerAddress').val(customer.address);
                        $('#customerId').val(customer.id);
                        $('#customerList').empty().hide();
                    } else {
                        $('#customerList').empty().hide();
                    }
                },
                error: function() {
                    $('#customerName, #customerCNIC, #customerAddress, #customerId').val('');
                    $('#customerList').empty().hide();
                }
            });
        }
    });

    $('#customerName').on('keyup', function() {
        var query = $(this).val();
        if (query.length > 2) {
            $.ajax({
                url: "{{ route('search.customer') }}",
                method: "GET",
                data: { name: query },
                success: function(response) {
                    $('#customerList').empty().show();
                    if (response.status === 'success' && response.data && response.data.length > 0) {
                        response.data.forEach(function(customer) {
                            $('#customerList').append(
                                `<li class="customer-item" data-id="${customer.id}" data-name="${customer.name}" data-mobile="${customer.mobile_number}" data-cnic="${customer.cnic}" data-address="${customer.address}">${customer.name} (${customer.mobile_number})</li>`
                            );
                        });
                    } else {
                        $('#customerList').append('<li>No customers found</li>');
                    }
                }
            });
        } else {
            $('#customerList').empty().hide();
        }
    });

    $(document).on('click', '.customer-item', function() {
        $('#customerId').val($(this).data('id'));
        $('#customerName').val($(this).data('name'));
        $('#customerMobile').val($(this).data('mobile'));
        $('#customerCNIC').val($(this).data('cnic'));
        $('#customerAddress').val($(this).data('address'));
        $('#customerList').empty().hide();
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#customerName, #customerList').length) {
            $('#customerList').empty().hide();
        }
    });
});

$(document).ready(function() {
    $(document).on('click', '#updateSaleBtn', function (e) {
        e.preventDefault();

        const form = $('#saleForm');
        const formData = form.serialize();
        const mode = $('#saleMode').val();
        const actionUrl = form.attr('action');

        const cashAmount = $('#Cash').val();
        if (cashAmount === '' || parseFloat(cashAmount) < 0) {
            Swal.fire("Invalid Cash", "Please enter a valid cash amount.", "warning");
            return;
        }
        if ($('#customerName').val().trim() === '' && $('#customerMobile').val().trim() === '') {
             Swal.fire("Missing Customer", "Please enter customer name or mobile number.", "warning");
            return;
        }


        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: mode === 'edit' ? 'Sale Updated' : 'Sale Saved',
                    text: response.message || 'Sale has been processed successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });

                if (response.redirect_url) {
                    setTimeout(function () {
                        window.location.href = response.redirect_url;
                    }, 2000);
                } else {
                    if (mode === 'create') {
                         setTimeout(function () {
                            window.location.href = "{{ route('sale.index') }}";
                        }, 2000);
                    } else {
                         setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                }
            },
            error: function (xhr) {
                let message = 'An error occurred while processing the sale.';
                 let errorsList = '';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    if (xhr.responseJSON.errors) {
                        errorsList = '<ul style="text-align:left;">';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorsList += '<li>' + value + '</li>';
                        });
                        errorsList += '</ul>';
                        message += errorsList;
                    }
                }
                Swal.fire("Error", message, "error");
            }
        });
    });
});
</script>
@endPushOnce
