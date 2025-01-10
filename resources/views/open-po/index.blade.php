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
                                    <div class="dropdown" style="position: relative;">
                                        <input type="text" class="form-control dropdown-toggle" id="searchDropdown"
                                            data-bs-toggle="dropdown" aria-expanded="false"
                                            placeholder="Select Purchase Order" readonly
                                            style=" background-color: white; cursor: pointer; border: 1px solid #ced4da; border-radius: 0.375rem;">
                                        <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="searchDropdown"
                                            style="width: 100%; max-height: 350px; overflow-y: auto;
                                                    padding: 0; margin-top: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e0e0e0; border-radius: 0.375rem;">
                                            <div class="search-container"
                                                style="padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #e0e0e0;
                                                        position: sticky; top: 0; z-index: 10;">
                                                <input type="text" class="form-control" id="filterInput"
                                                    placeholder="Search in list..." onkeyup="filterList()"
                                                    style="border-radius: 0.25rem; border: 1px solid #ced4da; padding: 0.25rem 0.5rem;">
                                            </div>
                                            <ul class="list-group list-group-flush" id="dropdownMenuItems"
                                                style="max-height: 250px; overflow-y: auto;">
                                                <li class="list-group-item list-group-item-action"
                                                    style="cursor: pointer; padding: 0.5rem 1rem; transition: background-color 0.2s;"
                                                    data-value="All">
                                                    <span class="text-muted">All Purchase Orders</span>
                                                </li>
                                                @foreach ($uniquePOs as $po)
                                                    <li class="list-group-item list-group-item-action"
                                                        style="cursor: pointer; padding: 0.5rem 1rem; transition: background-color 0.2s;"
                                                        data-value="{{ $po }}">
                                                        {{ $po }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select-checkbox" onclick="selectAll()"
                                                id="selectAllRows">
                                        </th>
                                        <th>Vendor Account</th>
                                        <th>Item Number</th>
                                        <th>Name</th>
                                        <th>Purchase Order</th>
                                        <th>Line Number</th>
                                        <th>Purchase Requisition</th>
                                        <th>Product Name</th>
                                        <th>Deliver Reminder</th>
                                        <th>Delivery Date</th>
                                        <th>Part Name</th>
                                        <th>Part Number</th>
                                        <th>Procurement Category</th>
                                        <th>Site</th>
                                        <th>Warehouse</th>
                                        <th>Location</th>
                                        <th>Qty</th>
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
                        url: `/purchase-order/load-data/${year}/${month}`,
                        type: 'GET',
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
                            data: 'vendor_account',
                            name: 'vendor_account'
                        },
                        {
                            data: 'item_number',
                            name: 'item_number'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'purchase_order',
                            name: 'purchase_order'
                        },
                        {
                            data: 'line_number',
                            name: 'line_number'
                        },
                        {
                            data: 'purchase_requisition',
                            name: 'purchase_requisition'
                        },
                        {
                            data: 'product_name',
                            name: 'product_name',
                        },
                        {
                            data: 'deliver_reminder',
                            name: 'deliver_reminder',
                            render: function(data, type, row) {
                                data = data || '';
                                return type === 'display' ?
                                    `<div class = "edit-container">
                            <input type = "number" style = "width: 100%; display: inline;" class="form-control deliver-reminder-input float-start px-1" data-id="${row.id}"
                            value="${data}">
                            <button class="btn btn-success save-btn" data-id="${row.id}" data-purchase_order="${row.purchase_order}" style="display: none;">
                            <i class="mdi mdi-content-save"></i></button>
                            </div>` : data;
                            }
                        },
                        {
                            data: 'delivery_date',
                            name: 'delivery_date',
                        },
                        {
                            data: 'part_name',
                            name: 'part_name',
                        },
                        {
                            data: 'part_number',
                            name: 'part_number',
                        },
                        {
                            data: 'procurement_category',
                            name: 'procurement_category',
                        },
                        {
                            data: 'site',
                            name: 'site',
                        },
                        {
                            data: 'warehouse',
                            name: 'warehouse',
                        },
                        {
                            data: 'location',
                            name: 'location',
                        },
                        {
                            data: 'qty',
                            name: 'qty',
                        },
                    ],
                    responsive: true,
                    autoWidth: false
                });
            });

            function filterList() {
                var input = document.getElementById('filterInput');
                var filter = input.value.toUpperCase();
                var dropdownItems = document.getElementById('dropdownMenuItems').getElementsByTagName('li');

                for (var i = 0; i < dropdownItems.length; i++) {
                    var txtValue = dropdownItems[i].textContent || dropdownItems[i].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        dropdownItems[i].style.display = "";
                    } else {
                        dropdownItems[i].style.display = "none";
                    }
                }
            }

            document.getElementById('dropdownMenuItems').addEventListener('click', function(e) {
                var listItem = e.target.closest('li');
                if (listItem) {
                    var selectedValue = listItem.getAttribute('data-value');
                    var searchDropdown = document.getElementById('searchDropdown');
                    searchDropdown.value = selectedValue === 'All' ? 'Select Purchase Order' : selectedValue;
                    if ($.fn.DataTable.isDataTable('#poTable')) {
                        var table = $('#poTable').DataTable();
                        table.column(1).search(selectedValue === 'All' ? '' : selectedValue).draw();
                    }
                    var dropdownMenu = listItem.closest('.dropdown-menu');
                    var dropdown = bootstrap.Dropdown.getInstance(searchDropdown);
                    dropdown.hide();
                }
            });

            document.getElementById('dropdownMenuItems').addEventListener('mouseover', function(e) {
                var listItem = e.target.closest('li');
                if (listItem) {
                    listItem.style.backgroundColor = '#f1f3f5';
                }
            });

            document.getElementById('dropdownMenuItems').addEventListener('mouseout', function(e) {
                var listItem = e.target.closest('li');
                if (listItem) {
                    listItem.style.backgroundColor = '';
                }
            });

            $('#poTable').on('click', '.deliver-reminder-input', function() {
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
                const newQty = $(this).siblings('.deliver-reminder-input').val();
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
                            url: `/purchase-order/update/${id}`,
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
