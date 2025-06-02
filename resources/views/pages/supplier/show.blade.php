@extends('index')

@section('content')

<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> {{-- Added modal-lg for a larger modal on wider screens --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="supplierForm">
                    <div class="form-group">
                        <label for="supplier">Supplier</label>
                        <input type="text" name="supplier" class="form-control" id="supplier" placeholder="Enter Supplier">
                        <small class="text-danger d-none" id="supplierError">Supplier is required.</small>
                    </div>
                    <div class="form-group">
                        <label for="contact_person">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" id="contact_person" placeholder="Enter Contact Person">
                        <small class="text-danger d-none" id="contactPersonError">Contact person is required.</small>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="Enter Address">
                        <small class="text-danger d-none" id="addressError">Address is required.</small>
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact No</label>
                        <input type="text" name="contact_no" class="form-control" id="contact_no" placeholder="Enter Contact No">
                        <small class="text-danger d-none" id="contactNoError">Contact number is required.</small>
                    </div>
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea name="note" class="form-control" id="note" placeholder="Enter Note"></textarea>
                        <small class="text-danger d-none" id="noteError">Note is required.</small>
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
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap"> {{-- Added flex-wrap for smaller screens --}}
                <div class="d-flex col-md-6 col-12 justify-content-start"> {{-- Added col-12 for full width on small screens --}}
                    <h3 class="card-title">All Suppliers</h3>
                </div>
                <div class="d-flex col-md-6 col-12 justify-content-end mt-2 mt-md-0"> {{-- Added col-12 and margin for smaller screens --}}
                    <button type="button" class="btn btn-primary" id="addSupplierBtn">
                        Add Supplier
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive"> {{-- Essential for horizontal scrolling on small devices --}}
                    <table class="table table-hover w-100" id="example1">
                        <thead>
                            <tr>
                                <th>#Sr.No</th>
                                <th>Supplier Name</th>
                                <th>Contact Person</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableHolder">
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->supplier }}</td>
                                <td>{{ $supplier->contact_person }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary edit-supplier" data-id="{{ $supplier->id }}">Edit</a>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-supplier" data-id="{{ $supplier->id }}">Delete</a>
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
    $(function () {
        // Initialize DataTables
        $("#example1").DataTable({
            "responsive": false, // Disable DataTables' built-in responsive features
            "lengthChange": true,
            "autoWidth": false, // Prevents DataTables from setting fixed widths, allows Bootstrap to manage
            "scrollY": false,    // Let CSS/Bootstrap handle vertical scrolling if needed
            "scrollX": false,    // Let Bootstrap's .table-responsive handle horizontal scrolling
            "buttons": ["excel", "pdf"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    $(document).ready(function () {
        // Open modal for adding a new supplier
        $('#addSupplierBtn').click(function () {
            $('#addSupplierModalLabel').text('Add New Supplier');
            $('#supplierForm')[0].reset(); // Reset form fields
            $('.text-danger').addClass('d-none'); // Hide all validation errors
            $('#submitBtn').text('Save Supplier').data('action', 'add'); // Set button action for 'add'
            $('#addSupplierModal').modal('show');
        });

        // Form submit handler for add/edit
        $('#supplierForm').submit(function (e) {
            e.preventDefault();

            const actionType = $('#submitBtn').data('action');
            const url = actionType === 'add' ? '{{ route("add.suppliers") }}' : '/suppliers/' + $('#submitBtn').data('id');
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
            function getErrorElementId(key) {
                return key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase()) + 'Error';
            }

            Object.keys(formData).forEach(function (key) {
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
                success: function (response) {
                    Swal.fire({
                        title: "Good job!",
                        text: actionType === 'add' ? 'Supplier added successfully!' : 'Supplier updated successfully!',
                        icon: "success",
                    });
                    $('#addSupplierModal').modal('hide');
                    let refreshUrl = "{{ route('show.suppliers') }}?t=" + new Date().getTime();
                    refreshtble(refreshUrl);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            const errorElement = $('#' + getErrorElementId(key));
                            if (errorElement.length) {
                                errorElement.text(errors[key][0]).removeClass('d-none');
                            }
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

        // Function to refresh the table content and reinitialize DataTables
        function refreshtble(url){
            // Use a temporary element to load the new table content
            $('body').append('<div id="tempTableContent" style="display:none;"></div>');
            $('#tempTableContent').load(url + " #tableHolder > *", function() {
                // Destroy the old DataTable instance if it exists
                if ($.fn.DataTable.isDataTable('#example1')) {
                    $('#example1').DataTable().destroy();
                }

                // Replace the old table body with the new one
                $('#tableHolder').html($('#tempTableContent #tableHolder').html());
                $('#tempTableContent').remove(); // Remove the temporary element

                // Reinitialize the DataTable with responsive settings
                $("#example1").DataTable({
                    "responsive": false,
                    "lengthChange": true,
                    "autoWidth": false,
                    "scrollY": false,
                    "scrollX": false,
                    "buttons": ["excel", "pdf"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            });
        }

        // Edit supplier logic
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
                    $('.text-danger').addClass('d-none'); // Hide all validation errors
                    $('#submitBtn').text('Update Supplier').data('action', 'edit').data('id', supplierId);
                    $('#addSupplierModal').modal('show');
                },
                error: function () {
                    alert('Error fetching Supplier details.');
                },
            });
        });

        // Delete supplier logic
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
                        url: '/supplier/' + supplierId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Supplier deleted successfully!",
                                icon: "success",
                            });
                            let refreshUrl = "{{ route('show.suppliers') }}?t=" + new Date().getTime();
                            refreshtble(refreshUrl);
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                                footer: '<a href="#">An error occurred while deleting the supplier. Please try again.</a>'
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Cancelled",
                        text: "The supplier is safe.",
                        icon: "info"
                    });
                }
            });
        });
    });
</script>

@stop