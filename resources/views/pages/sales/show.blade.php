@extends('index')

@section('content')

 <div class="py-10">
    <div class="flex justify-end mb-4">
        <button onclick="history.back()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-400 to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </button>
    </div>

    @php
        $editing = isset($editTransaction);
    @endphp

    <!-- Product Form Card -->
    <div class="bg-white shadow-lg rounded-lg mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-400 to-blue-600">
            <h3 class="text-lg font-medium text-white">{{ $editing ? 'Edit Transaction' : 'Add New Transaction' }}</h3>
        </div>
        
        <form id="productForm" action="{{ $editing ? route('transaction.update', $editTransaction->id) : route('transaction.sales') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="productSelect" class="block text-sm font-medium text-gray-700 mb-1">Products</label>
                    <select id="productSelect" name="product_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm" multiple="multiple">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                {{ ($editing && $editTransaction->product_id == $product->id) ? 'selected' : '' }}>
                                Item Code({{ $product->item_code }}) -- Product Name ({{ $product->item_name }}) -- Original Price ({{ $product->original_price }}) -- Sale Price ({{ $product->selling_price }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="{{ $editing ? $editTransaction->quantity : old('quantity', 1) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" min="1">
                </div>
                
                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Discount</label>
                    <input type="number" id="discount" name="discount" value="{{ $editing ? $editTransaction->discount : old('discount', 0) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" min="0">
                </div>
                
                <div>
                    <label for="service" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <input type="text" id="service" name="service" value="{{ $editing ? $editTransaction->service_charges : old('service', 0) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" min="0">
                </div>
            </div>
            
            <div class="mt-6 flex space-x-3">
                <button type="button" id="saveTransactionButton" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Transaction
                </button>
                
                <button type="button" id="submitTransactionButton" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200" style="display: none;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Update Transaction
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions Table Card -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-400 to-blue-600">
            <h3 class="text-lg font-medium text-white">Sale Details</h3>
        </div>
        
        <div class="p-6">
            <form id="saleForm" action="{{ isset($sale) ? route('sale.update', $sale->id) : route('sale.store') }}" method="POST">
                @csrf
                <input type="hidden" id="saleMode" value="{{ isset($sale) ? 'edit' : 'create' }}">
                @if(isset($sale))
                    <input type="hidden" id="currentSaleId" value="{{ $sale->id }}">
                @endif
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="example1">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#Sr.No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Charges</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tableHolder">
                            @if(isset($transactions))
                                @foreach($transactions as $transaction)
                                <tr data-id="{{ $transaction->id }}" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $loop->iteration }}
                                        <input type="hidden" value="{{ $transaction->id }}" name="transaction_id[]" id="transaction_id_{{ $transaction->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->products->item_name ?? ' '}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->products->selling_price ?? ' ' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->quantity ?? ' ' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->discount ?? ' ' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->service_charges ?? ' ' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaction->total_amount ?? ' ' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="javascript:void(0)" class="text-yellow-600 hover:text-yellow-900 mr-3 edit-sale"
                                            data-id="{{ $transaction->id }}"
                                            data-product="{{ $transaction->product_id }}"
                                            data-quantity="{{ $transaction->quantity }}"
                                            data-discount="{{ $transaction->discount }}"
                                            data-service="{{ $transaction->service_charges }}">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <a href="javascript:void(0)" class="text-red-600 hover:text-red-900 delete-sale"
                                            data-id="{{ $transaction->id }}">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Cancel
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="8"><input type="hidden" name="qty" value="{{$transactions->sum('quantity')}}"></td>
                                </tr>
                            @endif
                            
                            <!-- Total Discount Row -->
                            <tr class="bg-gray-50">
                                <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total Discount:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" id="totalDiscountText">
                                    {{ isset($transactions) ? $transactions->sum('discount') : 0 }}
                                    <input type="hidden" value="{{ isset($transactions) ? $transactions->sum('discount') : 0 }}" name="total_discount" id="total_discount">
                                </td>
                                <td></td>
                            </tr>
                            
                            <!-- Total Amount Row -->
                            <tr class="bg-gray-50">
                                <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total Amount:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-700" id="totalAmountText">
                                    {{ isset($transactions) ? $transactions->sum('total_amount') : 0 }}
                                    <input type="hidden" value="{{ isset($transactions) ? $transactions->sum('total_amount') : 0 }}" name="total_amount" id="total_amount">
                                </td>
                                <td></td>
                            </tr>
                            
                            <!-- Customer Details Header -->
                            <tr>
                                <td colspan="8" class="px-6 py-3 bg-gradient-to-r from-blue-400 to-blue-600 text-center text-sm font-medium text-white">Customer Detail</td>
                            </tr>
                            
                            <input type="hidden" class="form-control" id="customerId" name="customer_id" value="{{ old('customer_id', $customer->id ?? '') }}">
                            
                            <!-- Customer Details Form -->
                            <tr>
                                <td colspan="8" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="customerMobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                                            <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" id="customerMobile" name="mobile_number" placeholder="Enter mobile number" value="{{ old('mobile_number', $customer->mobile_number ?? '') }}">
                                            @error('mobile_number')
                                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="customerName" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                            <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" id="customerName" name="name" placeholder="Enter customer name" value="{{ old('name', $customer->name ?? '') }}">
                                            <ul id="customerList" class="mt-1"></ul>
                                            @error('name')
                                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        @if(!isset($sale))
                                            <div>
                                                <label for="customerCNIC" class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
                                                <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" id="customerCNIC" name="cnic" placeholder="Enter CNIC (e.g., 12345-6789012-3)" value="{{ old('cnic', $customer->cnic ?? '') }}">
                                                @error('cnic')
                                                <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <label for="Cash" class="block text-sm font-medium text-gray-700 mb-1">Cash</label>
                                            <input type="number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" id="Cash" name="cash" value="{{ old('cash', $sale->cash ?? '') }}" placeholder="Enter the cash" min="0" required>
                                            @error('cash')
                                            <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6">
                                        <label for="customerAddress" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                        <textarea class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" id="customerAddress" rows="3" name="address" placeholder="Enter address">{{ old('address', $customer->address ?? '') }}</textarea>
                                        @error('address')
                                        <span class="mt-1 text-sm text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6 flex justify-center">
                    <button type="button" id="updateSaleBtn" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white {{ isset($sale) ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-indigo-600 hover:bg-indigo-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            @if(isset($sale))
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @endif
                        </svg>
                        {{ isset($sale) ? 'Update' : 'Save' }} Sale
                    </button>
                </div>
            </form>
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
        if (customerMobile.length < 11) {
            $('#customerName, #customerCNIC, #customerAddress, #customerId').val('');
            $('#customerList').empty().hide();
            return;
        }

        if (customerMobile.length == 11) {
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
