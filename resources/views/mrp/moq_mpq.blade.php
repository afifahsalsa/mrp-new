@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <a href="{{ route('mrp.moq-mpq') }}"
            style="text-decoration: none; color: {{ request()->routeIs('mrp.moq-mpq') ? 'purple' : 'blue' }}">MOQ & MPQ |
        </a>
        <a href="{{ route('mrp.keb-material') }}"
            style="text-decoration: none; color: {{ request()->routeIs('mrp.keb-material') ? 'purple' : 'blue' }}">Kebutuhan Material
            |
        </a>
        <a href="{{ route('mrp.keb-production') }}"
            style="text-decoration: none; color: {{ request()->routeIs('mrp.keb-production') ? 'purple' : 'blue' }}">Kebutuhan Produksi
            |
        </a>
        <a href="{{ route('mrp.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('mrp.index') ? 'purple' : 'blue' }}"> Rencana
            Pembelian</a>

        <div class="col-lg-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="page-header ms-4 mt-3">
                    <h3>Choose Month to <span class="text-primary">View</span> or <span class="text-primary">Edit</span>
                        MOQ and MPQ</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><button type="button" class="btn btn-gradient-danger btn-rounded"
                                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                    onmouseover="this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.transform='scale(1)';" data-bs-toggle="modal"
                                    data-bs-target="#modalImportMOQMPQ">Add New</button></li>
                            <li>
                                <form action="{{ route('mrp.format') }}" enctype="multipart/form-data" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-gradient-info btn-rounded ms-2"
                                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                        onmouseover="this.style.transform='scale(1.05)';"
                                        onmouseout="this.style.transform='scale(1)';"><i class="mdi mdi-download"></i>
                                        Download Format</button>
                            </li>
                            </form>
                        </ol>
                    </nav>
                </div>

                {{-- Modal Import --}}
                <div class="modal fade" id="modalImportMOQMPQ" tabindex="-1" aria-labelledby="modalLabelMOQMPQ"
                    aria-hidden="true" onsubmit="showLoading()">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalLabelMOQMPQ">Import File MOQ & MPQ</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('mrp.import') }}" enctype="multipart/form-data" method="POST"
                                id="importMOQMPQ">
                                @csrf
                                <div class="modal-body">
                                    <label for="date" class="form-label"><strong>Date</strong></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                    <label for="file" class="form-label mt-2"><strong>Choose File</strong></label>
                                    <input class="form-control" type="file" id="file" name="file" accept=".xlsx"
                                        required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal"
                                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);">Close</button>
                                    <button type="submit" class="btn btn-primary" id="submitButton"
                                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Update -->
                <div class="modal fade" id="modalUpdateMOQ" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Data MOQ & MPQ</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('mrp.import') }}" enctype="multipart/form-data" method="POST"
                                id="importMOQMPQ">
                                @csrf
                                <div class="modal-body">
                                    <label for="date" class="form-label"><strong>Date</strong></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                    <label for="file" class="form-label mt-2"><strong>Choose File</strong></label>
                                    <input class="form-control" type="file" id="file" name="file"
                                        accept=".xlsx" required>
                                    <p class="ms-1 text-danger">Upload file dengan format yang sesuai saat import!</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal"
                                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">Close</button>
                                    <button type="submit" class="btn btn-primary" id="submitButton"
                                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="margin-top: -2%;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Month and Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($monthMoq as $index => $mm)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::create($mm->year, $mm->month)->format('F Y') }}
                                    </td>
                                    <td>
                                        @if (auth()->user()->role == 'staff' || auth()->user()->role == 'superuser')
                                            <a href="{{ route('buffer.index-edit', ['year' => $mm->year, 'month' => $mm->month]) }}"
                                                class="btn btn-inverse-dark px-4"
                                                style="color: black; text-decoration: none;"
                                                onmouseover="this.style.color='white'; this.querySelector('i').style.color='white';"
                                                onmouseout="this.style.color='black'; this.querySelector('i').style.color='black';">
                                                <i class="mdi mdi-table-edit" style="color: black;"></i> Edit | Delete
                                            </a>
                                        @endif
                                        <button class="btn btn-inverse-success px-4" data-bs-toggle="modal"
                                            data-bs-target="#modalUpdateMOQ">
                                            <i class="mdi mdi-cloud-sync"></i> Update
                                        </button>
                                        <button class="btn btn-inverse-primary px-4" data-bs-toggle="modal"
                                            data-bs-target="#viewModal" data-year="{{ $mm->year }}"
                                            data-month="{{ $mm->month }}">
                                            <i class="mdi mdi-magnify"></i> View
                                        </button>
                                        <div class="modal fade" id="viewModal" tabindex="-1"
                                            aria-labelledby="viewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
                                                style="width: 100rem;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="viewModalLabel">MOQ & MPQ in :
                                                            <span id="modalMonthYear" class="text-danger"></span>
                                                        </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table display" id="moqTable"
                                                                style="width: 100%">
                                                                <div class="d-flex mb-4">
                                                                    <h4 class="mt-3">Filter: </h4>
                                                                    <select id="filter-lt" class="form-select mt-2"
                                                                        style="width: 20%; margin-left: 20px;">
                                                                        <option value="">Filter LT</option>
                                                                    </select>
                                                                    @if (auth()->user()->role == 'staff' || auth()->user()->role == 'superuser')
                                                                        {{-- Button Delete --}}
                                                                        <form action="{{ route('buffer.delete') }}"
                                                                            method="DELETE" id="delBuff"
                                                                            class="ms-auto d-flex">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <button class="btn btn-danger px-3"
                                                                                type="button"
                                                                                style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform:scale(1);"
                                                                                onmouseover="this.style.transform='scale(1.05)';"
                                                                                onmouseout="this.style.transform='scale(1)';"
                                                                                onclick="deleteConfirm('delBuff')">
                                                                                <i class="mdi mdi-delete"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>

                                                                <thead>
                                                                    <tr>
                                                                        <th><input type="checkbox" class="select-checkbox"
                                                                                id="selectAll"></th>
                                                                        <th>Item Number</th>
                                                                        <th>Part Number</th>
                                                                        <th>Part Name</th>
                                                                        <th>LT</th>
                                                                        <th>Supplier</th>
                                                                        <th>Lokal / Import</th>
                                                                        <th>Type</th>
                                                                        <th>MOQ</th>
                                                                        <th>MPQ</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark"
                                                            data-bs-dismiss="modal"
                                                            style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No Data Available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('scriptMoq')
        <script>
            $(document).ready(function() {
                $('#viewModal').on('show.bs.modal', function(event) {
                    const userRole = "{{ auth()->user()->role }}";
                    let button = $(event.relatedTarget);
                    let year = button.data('year');
                    let month = button.data('month');

                    const table = $('#moqTable').DataTable({
                        "lengthMenu": [10, 25, 50, 100],
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: `/mrp/moq-mpq/load-data/${year}/${month}`,
                            type: 'GET',
                            data: function(d) {
                                var ltValue = $('#filter-lt').val();
                                if (ltValue) {
                                    d.lt = ltValue;
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
                                data: 'item_number',
                                name: 'item_number'
                            },
                            {
                                data: 'part_number',
                                name: 'part_number'
                            },
                            {
                                data: 'part_name',
                                name: 'part_name'
                            },
                            {
                                data: 'lt',
                                name: 'lt'
                            },
                            {
                                data: 'spl',
                                name: 'spl'
                            },
                            {
                                data: 'li',
                                name: 'li'
                            },
                            {
                                data: 'type',
                                name: 'type'
                            },
                            {
                                data: 'moq',
                                name: 'moq',
                                render: function(data, type, row) {
                                    data = data || '';
                                    if (type === 'display') {
                                        if (userRole === 'superuser' || userRole === 'staff') {
                                            return `
                                                <div class="edit-container">
                                                    <input type="number" style="width: 50%; display: inline; padding-top: 12px; padding-bottom: 10px;"
                                                        class="form-control moq-input float-start px-1"
                                                        data-id="${row.id}" value="${data}">
                                                    <button class="btn btn-success save-btn" data-id="${row.id}" data-item_number="${row.item_number}"
                                                        style="display: none;">
                                                        <i class="mdi mdi-content-save"></i>
                                                    </button>
                                                </div>`;
                                        } else {
                                            return `
                                            <div class="edit-container">
                                                <input type="number" style="width: 50%; display: inline; padding-top: 12px; padding-bottom: 10px;"
                                                    class="form-control moq-input float-start px-1"
                                                    data-id="${row.id}" value="${data}" readonly>
                                            </div>`;
                                        }
                                    }
                                    return data;
                                }
                            },
                            {
                                data: 'mpq',
                                name: 'mpq',
                                render: function(data, type, row) {
                                    data = data || '';
                                    if (type === 'display') {
                                        if (userRole === 'superuser' || userRole === 'staff') {
                                            return `
                                                <div class="edit-container">
                                                    <input type="number" style="width: 50%; display: inline; padding-top: 12px; padding-bottom: 10px;"
                                                        class="form-control mpq-input float-start px-1"
                                                        data-id="${row.id}" value="${data}">
                                                    <button class="btn btn-success save-btn" data-id="${row.id}" data-item_number="${row.item_number}"
                                                        style="display: none;">
                                                        <i class="mdi mdi-content-save"></i>
                                                    </button>
                                                </div>`;
                                        } else {
                                            return `
                                            <div class="edit-container">
                                                <input type="number" style="width: 50%; display: inline; padding-top: 12px; padding-bottom: 10px;"
                                                    class="form-control mpq-input float-start px-1"
                                                    data-id="${row.id}" value="${data}" readonly>
                                            </div>`;
                                        }
                                    }
                                    return data;
                                }
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
                        responsive: true,
                        autoWidth: false
                    });
                });
                $('#viewModal').on('hidden.bs.modal', function() {
                    if ($.fn.DataTable.isDataTable('#moqTable')) {
                        $('#moqTable').DataTable().destroy();
                    }
                });
            });

            $('#moqTable').on('click', '.moq-input', function() {
                $(this).siblings('.save-btn').css({
                    'display': 'inline-block',
                    'opacity': '1',
                    'transition': 'opacity 0.3s ease',
                    'width': 'auto',
                    'padding': '10px 13px',
                    'margin-end': '60px',
                    'box-shadow': '0px 4px 8px rgba(0, 0, 0, 0.2)',
                });
            });

            $('#moqTable').on('click', '.mpq-input', function() {
                $(this).siblings('.save-btn').css({
                    'display': 'inline-block',
                    'opacity': '1',
                    'transition': 'opacity 0.3s ease',
                    'width': 'auto',
                    'padding': '10px 13px',
                    'margin-end': '60px',
                    'box-shadow': '0px 4px 8px rgba(0, 0, 0, 0.2)',
                });
            });

            $('#moqTable').on('click', '.save-btn', function() {
                const id = $(this).data('id');
                const row = $(this).closest('tr');
                const newMOQ = row.find('.moq-input').val();
                const newMPQ = row.find('.mpq-input').val();
                const itemNumber = $(this).data('item_number');
                const table = $('#moqTable').DataTable();

                Swal.fire({
                    title: `Konfirmasi Pembaruan`,
                    text: `Apakah Anda yakin ingin memperbarui MOQ dan MPQ untuk Item Number ${itemNumber}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Perbarui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/mrp/moq-mpq/update/${id}`,
                            type: 'PUT',
                            data: {
                                _token: "{{ csrf_token() }}",
                                moq: newMOQ,
                                mpq: newMPQ,
                                id: id
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: response.swal.type,
                                    title: response.swal.title,
                                    text: response.swal.message,
                                    html: response.swal.html,
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
            });

            function deleteConfirm(formId) {
                const selectedRows = [];
                const table = $('#moqTable').DataTable();

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
                                        $('#moqTable').DataTable().ajax.reload();
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
                const table = $('#moqTable').DataTable();
                table.$('input[type="checkbox"]').prop('checked', isChecked);
            });

            document.addEventListener('DOMContentLoaded', function() {
                const viewModal = document.getElementById('viewModal');
                const modalMonthYear = document.getElementById('modalMonthYear');

                viewModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const year = button.getAttribute('data-year');
                    const month = button.getAttribute('data-month');
                    const monthName = new Date(year, month - 1).toLocaleString('default', {
                        month: 'long'
                    });
                    modalMonthYear.textContent = `${monthName}, ${year}`;
                });
            });
        </script>
    @endpush
@endsection
