@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            {{-- <h3 class="page-title">stok Table</h3> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">First Step</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Stock on Hand</li>
                </ol>
            </nav>
            <div class="d-flex">
                <button id="dropdownButton" type="button" class="btn btn-gradient-primary" data-bs-toggle="dropdown"
                    aria-expanded="false" onclick="toggleArrow()" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
                    Import | Export <span id="dropdownArrow" class="arrow">&#9656;</span>
                </button>

                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="{{ route('stok.format') }}">
                        <i class="mdi mdi-cloud-download me-2 text-success"></i> Download Format </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('stok.import') }}" data-bs-toggle="modal"
                        data-bs-target="#modalImportStok">
                        <i class="mdi mdi-cloud-upload me-2 text-danger"></i> Import File Stock </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <i class="mdi mdi-cloud-download me-2 text-primary"></i> Download Hasil Excel </a>
                </div>
                <form action="{{ route('stok.delete') }}" method="DELETE" id="deleteStock">
                    @csrf
                    @method('delete')
                    <button class="btn btn-gradient-danger ms-2 px-3" type="button"
                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);" onclick="deleteConfirm('deleteStock')"><i
                            class="mdi mdi-delete"></i></button>
                </form>
            </div>
        </div>

        {{-- Modal Import File stok --}}
        <div class="modal fade" id="modalImportStok" tabindex="-1" aria-labelledby="modalLabelStok" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalLabelStok">Import File stok</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('stok.import') }}" enctype="multipart/form-data" method="POST" id="importstok">
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
                        <h4 class="card-title">Striped Table</h4>
                        <p class="card-description"> Add class <code>.table-striped</code>
                        </p>
                        <div class="table-responsive">
                            <table class="table display" id="stokTable" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select-checkbox" onclick="selectAll()"
                                                id="selectAllRows"></th>
                                        <th>Item Number</th>
                                        <th>Part Number</th>
                                        <th>Product Name</th>
                                        <th>LT</th>
                                        <th>Local / Impor</th>
                                        <th>Stok</th>
                                        <th>Qty Buffer</th>
                                        <th>Percentage</th>
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
    @push('scriptStok')
        <script>
            function toggleArrow() {
                const button = document.getElementById("dropdownButton");
                const arrow = document.getElementById("dropdownArrow");

                button.addEventListener("click", function() {
                    if (button.getAttribute("aria-expanded") === "true") {
                        arrow.innerHTML = "&#9662;"; // Downward arrow when open
                    } else {
                        arrow.innerHTML = "&#9656;"; // Rightward arrow when closed
                    }
                });
            }

            let stokTable = $('#stokTable').DataTable({
                "lengthMenu": [10, 25, 50, 100, 500, 1000],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('stok.data') }}",
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
                        data: 'lt',
                        name: 'lt'
                    },
                    {
                        data: 'li',
                        name: 'li'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'qty_buffer',
                        name: 'qty_buffer'
                    },
                    {
                        data: 'percentage',
                        name: 'percentage'
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

            @if (session('status'))
                @if (session('status') === 'success')
                    Swal.fire({
                        icon: 'success',
                        title: 'Import successfull!',
                        text: `${@json(session('rowCountStok'))} data success imported!`,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#poTable').DataTable().ajax.reload();
                    });
                @elseif (session('status') === 'error')
                    Swal.fire({
                        icon: 'error',
                        title: 'Item Number Stok tidak ditemukan pada database Buffer',
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif
            @endif

            function deleteConfirm(formId) {
                // Dapatkan semua checkbox yang dipilih
                const selectedRows = [];
                const table = $('#stokTable').DataTable();

                table.$('input[type="checkbox"]:checked').each(function() {
                    const row = table.row($(this).closest('tr'));
                    const itemNumber = row.data().item_number;
                    selectedRows.push(itemNumber);
                });

                // Jika tidak ada data yang dipilih
                if (selectedRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silakan pilih data yang akan dihapus!',
                    });
                    return;
                }

                // Konfirmasi penghapusan
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
                        // Kirim request delete ke server
                        $.ajax({
                            url: "{{ route('stok.delete') }}",
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                item_numbers: selectedRows
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Tampilkan pesan sukses
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Reload DataTable
                                        $('#stokTable').DataTable().ajax.reload();
                                        // Uncheck selectAll checkbox
                                        $('#selectAllRows').prop('checked', false);
                                    });
                                }
                            },
                            error: function(xhr) {
                                // Tampilkan pesan error
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

            $('#selectAll').on('change', function() {
                const isChecked = $(this).prop('checked');
                const table = $('#stokTable').DataTable();

                // Toggle all checkboxes in the current page
                table.$('input[type="checkbox"]').prop('checked', isChecked);
            });
        </script>
    @endpush
@endsection
