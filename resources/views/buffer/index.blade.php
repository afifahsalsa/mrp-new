@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            {{-- <h3 class="page-title">Buffer Table</h3> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Tables</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Basic tables</li>
                </ol>
            </nav>
            {{-- Button Import | Export --}}
            <div class="d-flex">
                <button id="dropdownButton" type="button" class="btn btn-gradient-primary" data-bs-toggle="dropdown"
                    aria-expanded="false" onclick="toggleArrow()"
                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                    onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                    Import | Export <span id="dropdownArrow" class="arrow">&#9656;</span>
                </button>

                {{-- Dropdown File --}}
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
                </div>

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
                        {{-- <h4 class="card-title">Striped Table</h4>
                        <p class="card-description"> Add class <code>.table-striped</code>
                        </p> --}}
                        <div class="table-responsive">
                            <table class="table display" id="bufferTable" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select-checkbox" id="selectAll"></th>
                                        <th>Item Number</th>
                                        <th>Part Number</th>
                                        <th>Product Name</th>
                                        <th>Usage</th>
                                        <th>LT</th>
                                        <th>Kode</th>
                                        <th>Quantity</th>
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

            let bufferTable = $('#bufferTable').DataTable({
                "lengthMenu": [10, 25, 50, 100, 500, 1000],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('buffer.data') }}",
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
                        data: 'usage',
                        name: 'usage'
                    },
                    {
                        data: 'lt',
                        name: 'lt'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
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
                },
            });

            document.addEventListener("DOMContentLoaded", function() {
                const rowCount = {{ session('rowCount') ?? 0 }};
                if (rowCount > 0) {
                    Swal.fire({
                        title: "Your sheet successfully imported!",
                        text: `Total rows imported: ${rowCount}`,
                        icon: "success"
                    });
                }
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

                // Toggle all checkboxes in the current page
                table.$('input[type="checkbox"]').prop('checked', isChecked);
            });
        </script>
    @endpush
@endsection
