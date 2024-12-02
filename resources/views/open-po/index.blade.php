@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <a href="{{ route('open-po.index') }}"><button class="btn btn-inverse-dark px-4"
                            style="margin-left: -15px;"><i class="mdi mdi-arrow-left-bold-circle"></i>
                        </button>
                    </a>
                    <h3 class="ms-2 mt-2">Purchase Order in : <span class="text-danger">{{ $monthName . ', ' . $year }}</span>
                    </h3>
                </ol>
            </nav>
            <div class="d-flex">
                {{-- <button id="dropdownButton" type="button" class="btn btn-gradient-primary" data-bs-toggle="dropdown"
                    aria-expanded="false" onclick="toggleArrow()" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                    onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    Import | Export <span id="dropdownArrow" class="arrow">&#9656;</span>
                </button>

                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="{{ route('open-po.format') }}">
                        <i class="mdi mdi-cloud-download me-2 text-success"></i> Download Format </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalImportPo">
                        <i class="mdi mdi-cloud-upload me-2 text-danger"></i> Import File Outstanding PO </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <i class="mdi mdi-cloud-download me-2 text-primary"></i> Download Hasil Excel </a>
                </div> --}}
                <form action="{{ route('open-po.delete') }}" method="DELETE" id="deleteOpenPo">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger ms-2 px-3" type="button"
                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                        onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';"
                        onclick="deleteConfirm('deleteOpenPo')"><i class="mdi mdi-delete"></i></button>
                </form>
            </div>
        </div>

        {{-- Modal Import File Open PO --}}
        <div class="modal fade" id="modalImportPo" tabindex="-1" aria-labelledby="modalLabelPo" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalLabelPo">Import File Outstanding PO</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('open-po.import') }}" enctype="multipart/form-data" method="POST"
                        id="importPo">
                        @csrf
                        <div class="modal-body">
                            <label for="file" class="form-label mt-2"><strong>Choose File</strong></label>
                            <input class="form-control" type="file" id="file" name="file" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table display" id="poTable" style="width: 100%;">
                                <div class="d-flex mb-4">
                                    <h4 class="mt-3">Filter: </h4>
                                    <select id="filter-po" class="form-select mt-2" style="width: 20%; margin-left: 20px;">
                                        <option value="">Filter Purchase Order</option>
                                    </select>
                                </div>
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select-checkbox" onclick="selectAll()"
                                                id="selectAllRows">
                                        </th>
                                        <th>Purchase Order</th>
                                        <th>Item Number</th>
                                        <th>Product Name</th>
                                        <th>Purchase Requisition</th>
                                        <th>Name</th>
                                        <th>Created Date and Time</th>
                                        <th>Delivery Date</th>
                                        <th>Quantity</th>
                                        <th>Line Status</th>
                                        <th>Old Number Format</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scriptPo')
        <script>
            $(document).ready(function() {
                const year = {{ $year }};
                const month = {{ $month }};
                const table = $('#poTable').DataTable({
                    "lengthMenu": [10, 25, 50, 100, 500, 1000],
                    processing: true,
                    serverSide: true,
                    searching: true,
                    scrollX: true,
                    ajax: {
                        url: `/ppic/purchase-order/load-data/${year}/${month}`,
                        type: 'GET',
                        data: function(d) {
                            var poValue = $('#filter-po').val();
                            if (poValue){
                                d.purchase_order = poValue;
                            }
                        }
                    },
                    columns: [{
                            data: null,
                            orderable: false,
                            className: 'select-checkbox',
                            defaultContent: '',
                            render: function(data, type, row) {
                                return `<input type="checkbox" class="select-checkbox">`;
                            }
                        },
                        {
                            data: 'purchase_order',
                            name: 'purchase_order'
                        },
                        {
                            data: 'item_number',
                            name: 'item_number'
                        },
                        {
                            data: 'product_name',
                            name: 'product_name'
                        },
                        {
                            data: 'purchase_requisition',
                            name: 'purchase_requisition'
                        },
                        {
                            data: 'supplier_name',
                            name: 'supplier_name'
                        },
                        {
                            data: 'created_date_and_time',
                            name: 'created_date_and_time'
                        },
                        {
                            data: 'delivery_date',
                            name: 'delivery_date'
                        },
                        {
                            data: 'delivery_reminder',
                            name: 'delivery_reminder',
                            render: function(data, type, row) {
                                data = data || '';
                                return type === 'display' ?
                                    `<div class = "edit-container">
                            <input type = "number" style = "width: 100%; display: inline;" class="form-control delivery-reminder-input float-start px-1" data-id="${row.id}"
                            value="${data}">
                            <button class="btn btn-success save-btn" data-id="${row.id}" data-purchase_order="${row.purchase_order}" style="display: none;">
                            <i class="mdi mdi-content-save"></i></button>
                            </div>` : data;
                            }
                        },
                        {
                            data: 'line_status',
                            name: 'line_status'
                        },
                        {
                            data: 'old_number_format',
                            name: 'old_number_format'
                        },
                    ],
                    initComplete: function(){
                        $.get(`/ppic/purchase-order/get-unique-po/${year}/${month}`, function(data){
                            var select = $('#filter-po');
                            select.empty().append('<option value="">Filter Purchase Order</option>');
                            $.each(data, function(index, value){
                                select.append(`<option value="${value}">${value}</option>`);
                            });
                        });
                    },
                    columnDefs: [{
                        targets: 0,
                        orderable: false,
                        className: 'select-checkbox',
                        checkboxes: {
                            selectRow: true
                        }
                    }],
                    select: {
                        style: 'multi',
                        selector: 'td:first-child'
                    }
                });
                $('#filter-po').on('change', function(){
                    table.ajax.reload();
                });
            })

            $('#poTable').on('click', '.delivery-reminder-input', function() {
                $(this).siblings('.save-btn').css({
                    'display': 'inline-block',
                    'opacity': '1',
                    'transition': 'opacity 0.3s ease',
                    'width': '15%',
                    'padding-start': '23px',
                    'margin-right': '2.5rem',
                    'box-shadow': '0px 4px 8px rgba(0, 0, 0, 0.2)',
                });
                $(this).css({
                    'width': '70%',
                    'margin-right': '5px',
                    'border': '2px solid #28a745',
                    'transition': 'all 0.3s ease'
                });
            });

            $('#poTable').on('click', '.save-btn', function() {
                const id = $(this).data('id');
                const newQty = $(this).siblings('.delivery-reminder-input').val();
                const purchaseOrder = $(this).data('purchase_order');
                const button = $(this);
                const table = $('#poTable').DataTable();

                Swal.fire({
                    title: `Konfirmasi Pembaruan`,
                    text: `Apakah Anda yakin ingin memperbarui Quantity untuk Purchase Order ${purchaseOrder}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Perbarui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/ppic/purchase-order/update/${id}`,
                            type: 'PUT',
                            data: {
                                _token: "{{ csrf_token() }}",
                                delivery_reminder: newQty,
                                id: id
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: response.swal.type,
                                    title: response.swal.title,
                                    text: response.swal.message,
                                    timer: 1500,
                                    showConfirmButton: true
                                });
                                table.ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat memperbarui data!'
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Dibatalkan',
                            text: 'Tidak ada perubahan yang dilakukan.',
                            icon: 'info',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        table.ajax.reload(null, false);
                    }
                });
                button.hide();
            });

            function deleteConfirm(formId) {
                const selectedRows = [];
                const table = $('#poTable').DataTable();

                table.$('input[type="checkbox"]:checked').each(function() {
                    const row = table.row($(this).closest('tr'));
                    // const itemNumber = row.data().item_number;
                    const Id = row.data().id;
                    selectedRows.push(Id);
                });

                if (selectedRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silakan pilih data yang akan dihapus!',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `${selectedRows.length} data yang dipilih akan dihapus!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('open-po.delete') }}",
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                ids: selectedRows
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        $('#poTable').DataTable().ajax.reload();
                                        $('#selectAllRows').prop('checked', false);
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat menghapus data!'
                                });
                            }
                        });
                    }
                });
            }

            function selectAll() {
                const table = $('#poTable').DataTable();
                const selectAllCheckbox = $('#selectAllRows');
                table.$('input[type="checkbox"]').prop('checked', selectAllCheckbox.prop('checked'));
            }
        </script>
    @endpush
@endsection
