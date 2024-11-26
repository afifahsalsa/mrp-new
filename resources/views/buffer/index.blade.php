@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            {{-- <h3 class="page-title">Buffer Table</h3> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <a href="{{ route('buffer.index') }}">
                        <button class="btn btn-inverse-dark px-3" style="margin-left: -15px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                        onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                        <i class="mdi mdi-arrow-left-bold-circle"></i>
                        </button>
                    </a>
                    <h3 class="ms-2 mt-2">Edit Buffer in : <span class="text-danger">{{ $monthName . ',  ' . $year }}</span></h3>
                </ol>
            </nav>
            {{-- Button Import | Export --}}
            <div class="d-flex">
                {{-- <button id="dropdownButton" type="button" class="btn btn-gradient-primary" data-bs-toggle="dropdown"
                    aria-expanded="false" onclick="toggleArrow()"
                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                    onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    Import | Export <span id="dropdownArrow" class="arrow">&#9656;</span>
                </button>

                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="{{ route('buffer.format-import') }}">
                        <i class="mdi mdi-cloud-download me-2 text-success"></i> Download Format </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('buffer.import') }}" data-bs-toggle="modal"
                        data-bs-target="#modalImportBuffer">
                        <i class="mdi mdi-cloud-upload me-2 text-danger"></i> Import File Buffer </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <i class="mdi mdi-cloud-download me-2 text-primary"></i> Download Hasil Excel </a>
                </div> --}}

                {{-- Button Delete --}}
                <form action="{{ route('buffer.delete') }}" method="DELETE" id="delBuff">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger ms-2 px-3" type="button"
                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform:scale(1);"
                        onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';"
                        onclick="deleteConfirm('delBuff')"><i class="mdi mdi-delete"></i></button>
                </form>
            </div>
        </div>

        {{-- Modal Import File Buffer --}}
        <div class="modal fade" id="modalImportBuffer" tabindex="-1" aria-labelledby="modalLabelBuffer" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalLabelBuffer">Import File Buffer</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('buffer.import') }}" enctype="multipart/form-data" method="POST"
                        id="importBuffer">
                        @csrf
                        <div class="modal-body">
                            <label for="date" class="form-label"><strong>Date</strong></label>
                            <input type="date" name="date" id="date" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" required>
                            <label for="date" class="form-label mt-2"><strong>Choose File</strong></label>
                            <input class="form-control" type="file" id="file" name="file" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal"
                                style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">Close</button>
                            <button type="submit" class="btn btn-primary" id="submitButton"
                                style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    {{-- <p mb-0 ms-3>*Click form input quantity to edit value quantity.</p> --}}
                    <div class="card-body">
                        {{-- <h4 class="card-title">Buffer</h4>
                        <p class="card-description"> Add class <code>.table-striped</code></p> --}}
                        <div class="table-responsive">
                            <table class="table display" id="bufferTable" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select-checkbox" id="selectAll"></th>
                                        <th>Item Number</th>
                                        <th>Part Number</th>
                                        <th>Product Name</th>
                                        <th>LT</th>
                                        <th>Supplier</th>
                                        <th style="float: left;">Quantity</th>
                                        <th>Date</th>
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
    @push('scriptBuffer')
        <script>
            $(document).ready(function() {
                const year = {{ $year }};
                const month = {{ $month }};

                $('#bufferTable').DataTable({
                    "lengthMenu": [10, 25, 50, 100, 500, 1000],
                    processing: true,
                    serverSide: true,
                    searching: true,
                    scrollX: true,
                    ajax: {
                        url: `/ppic/buffer/load-data/${year}/${month}`,
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
                            data: 'item_number',
                            name: 'item_number'
                        },
                        {
                            data: 'part_number',
                            name: 'part_number'
                        },
                        {
                            data: 'product_name',
                            name: 'product_name'
                        },
                        {
                            data: 'lt',
                            name: 'lt'
                        },
                        {
                            data: 'supplier',
                            name: 'supplier'
                        },
                        {
                            data: 'qty',
                            name: 'qty',
                            render: function(data, type, row) {
                                data = data || '';
                                return type === 'display' ?
                                    `<div class="edit-container">
                            <input type="number" style="width: 50%; display: inline;" class="form-control qty-input float-start px-1" data-id="${row.id}" value="${data}">
                            <button class="btn btn-success save-btn" data-id="${row.id}" data-item_number="${row.item_number}" style="display: none;"><i class="mdi mdi-content-save"></i></button>
                            </div>` : data;
                            }
                        },
                        {
                            data: 'date',
                            name: 'date'
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
                    }
                });
            });

            $('#bufferTable').on('click', '.qty-input', function() {
                $(this).siblings('.save-btn').css({
                    'display': 'inline-block',
                    'opacity': '1',
                    'transition': 'opacity 0.3s ease',
                    'width': '15%',
                    'padding-start': '23px',
                    'margin-right': '1rem',
                    'box-shadow': '0px 4px 8px rgba(0, 0, 0, 0.2)',
                });
            });

            // Event listener for save button click
            $('#bufferTable').on('click', '.save-btn', function() {
                const id = $(this).data('id');
                const newQty = $(this).siblings('.qty-input').val();
                const itemNumber = $(this).data('item_number');
                const button = $(this);
                const table = $('#bufferTable').DataTable();

                Swal.fire({
                    title: `Konfirmasi Pembaruan`,
                    text: `Apakah Anda yakin ingin memperbarui Quantity untuk Item Number ${itemNumber}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Perbarui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/ppic/buffer/update/${id}`,
                            type: 'PUT',
                            data: {
                                _token: "{{ csrf_token() }}",
                                qty: newQty,
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
                                    text: 'Terjadi kesalahan saat memperbarui data!',
                                    footer: xhr.responseJSON?.message ||
                                        'Silakan coba lagi nanti.'
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
                // button.siblings('.qty-input').prop('enable', true);
            });

            function deleteConfirm(formId) {
                const selectedRows = [];
                const table = $('#bufferTable').DataTable();

                table.$('input[type="checkbox"]:checked').each(function() {
                    const row = table.row($(this).closest('tr'));
                    const itemNumber = row.data().item_number;
                    selectedRows.push(itemNumber);
                });

                if (selectedRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silahkan pilih data yang akan dihapus!',
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
                            url: "{{ route('buffer.delete') }}",
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                item_numbers: selectedRows
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
                                        $('#bufferTable').DataTable().ajax.reload();
                                        $('#selectAllRows').prop('checked', false);
                                    });
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 400) {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.itemsInStok) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error on Delete!',
                                            html: `Item Number dengan nomor:<br><strong>${response.itemsInStok.join(', ')}</strong><br>masih ada pada table stok!`,
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'OK'
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Terjadi kesalahan saat menghapus data!'
                                        });
                                    }
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Terjadi kesalahan saat menghapus data!'
                                    });
                                }
                            }
                        });
                    }
                });
            }

            $('#selectAll').on('change', function() {
                const isChecked = $(this).prop('checked');
                const table = $('#bufferTable').DataTable();
                table.$('input[type="checkbox"]').prop('checked', isChecked);
            });
        </script>
    @endpush
@endsection
