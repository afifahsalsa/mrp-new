@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Tables</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Basic tables</li>
                </ol>
            </nav>
            <div class="d-flex">
                <button id="dropdownButton" type="button" class="btn btn-gradient-primary" data-bs-toggle="dropdown"
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
                </div>
                <form action="{{ route('open-po.delete') }}" method="DELETE" id="deleteOpenPo">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger ms-2 px-3" type="button"
                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                    onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';" onclick="deleteConfirm('deleteOpenPo')"><i
                            class="mdi mdi-delete"></i></button>
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
                        <h4 class="card-title">Striped Table</h4>
                        <p class="card-description"> Add class <code>.table-striped</code>
                        </p>
                        <div class="table-responsive">
                            <table class="table display" id="poTable" style="width: 100%">
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
                                        <th>Delivery Reminder</th>
                                        <th>Line Status</th>
                                        <th>Old Number Format</th>
                                        <th>LT</th>
                                        <th>Standar Datang</th>
                                        <th>Bulan Datang</th>
                                        <th>Ket Late</th>
                                        <th>Ket LT</th>
                                        <th>Price</th>
                                        <th>Amount</th>
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
            let poTable = $('#poTable').DataTable({
                "lengthMenu": [10, 25, 50, 100, 500, 1000],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('open-po.data') }}",
                    type: 'GET'
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
                        name: 'delivery_reminder'
                    },
                    {
                        data: 'line_status',
                        name: 'line_status'
                    },
                    {
                        data: 'old_number_format',
                        name: 'old_number_format'
                    },
                    {
                        data: 'lt',
                        name: 'lt'
                    },
                    {
                        data: 'standar_datang',
                        name: 'standar_datang'
                    },
                    {
                        data: 'bulan_datang',
                        name: 'bulan_datang'
                    },
                    {
                        data: 'ket_late',
                        name: 'ket_late'
                    },
                    {
                        data: 'ket_lt',
                        name: 'ket_lt'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    }
                ],
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
                },
            });

            @if (session('status'))
                @if (session('status') === 'warning')
                    let emptyPRItems = @json(session('emptyPRItems'));
                    let itemNumbers = emptyPRItems.join(', ');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Import Success with Noted!',
                        html: `Import success with ${@json(session('rowCountPo'))} data imported.<br><br>
                        Purchase Requisition (PR) blank pada Item Number: ${itemNumbers}`,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#poTable').DataTable().ajax.reload();
                        }
                    });
                @else
                    Swal.fire({
                        icon: 'success',
                        title: 'Import Successfull!',
                        text: `${@json(session('rowCountPo'))} data success imported!`,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#poTable').DataTable().ajax.reload();
                    });
                @endif
            @endif

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
