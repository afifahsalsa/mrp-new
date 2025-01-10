@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <a href="{{ route('open-pr.index') }}"><button class="btn btn-inverse-dark px-4"
                            style="margin-left: -15px;"><i class="mdi mdi-arrow-left-bold-circle"></i>
                        </button>
                    </a>
                    <h3 class="ms-2 mt-2">Purchase Requisition in : <span
                            class="text-danger">{{ $monthName . ', ' . $year }}</span>
                    </h3>
                </ol>
            </nav>
            <div class="d-flex">
                <form action="{{ route('open-pr.delete') }}" method="DELETE" id="deleteOpenPr">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger ms-2 px-3" type="button"
                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                        onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';"
                        onclick="deleteConfirm('deleteOpenPr')"><i class="mdi mdi-delete"></i></button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table display" id="prTable" style="width: 100%;">
                                <div class="d-flex mb-4">
                                    <h4 class="mt-3">Filter: </h4>
                                    <select id="filter-status" class="form-select mt-2"
                                        style="width: 20%; margin-left: 20px;">
                                        <option value="">Filter Purchase Requisition Status </option>
                                    </select>
                                </div>
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select-checkbox" onclick="selectAll()"
                                                id="selectAllRows">
                                        </th>
                                        <th>Purchase Requisiton</th>
                                        <th>Item ID</th>
                                        <th>Part Number</th>
                                        <th>Old Name</th>
                                        <th>PR Date</th>
                                        <th>Request Date</th>
                                        <th>Quantity</th>
                                        <th>PR Status</th>
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
    @push('scriptPr')
        <script>
            $(document).ready(function() {
                const year = {{ $year }};
                const month = {{ $month }};
                const table = $('#prTable').DataTable({
                    "lengthMenu": [10, 25, 50, 100, 500, 1000],
                    processing: true,
                    serverSide: true,
                    searching: true,
                    scrollX: true,
                    ajax: {
                        url: `/purchase-requisition/load-data/${year}/${month}`,
                        type: 'GET',
                        data: function(d) {
                            var statusValue = $('#filter-status').val();
                            if (statusValue) {
                                d.pr_status = statusValue;
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
                            data: 'pr_id',
                            name: 'pr_id'
                        },
                        {
                            data: 'item_id',
                            name: 'item_id'
                        },
                        {
                            data: 'part',
                            name: 'part'
                        },
                        {
                            data: 'old_name',
                            name: 'old_name'
                        },
                        {
                            data: 'pr_date',
                            name: 'pr_date'
                        },
                        {
                            data: 'request_date',
                            name: 'request_date'
                        },
                        {
                            data: 'qty',
                            name: 'qty',
                            render: function(data, type, row) {
                                data = data || '';
                                return type === 'display' ?
                                    `<div class = "edit-container">
                            <input type = "number" style = "width: 100%; display: inline;" class="form-control qty float-start px-1" data-id="${row.id}"
                            value="${data}">
                            <button class="btn btn-success save-btn" data-id="${row.id}" data-pr_id="${row.pr_id}" style="display: none;">
                            <i class="mdi mdi-content-save"></i></button>
                            </div>` : data;
                            }
                        },
                        {
                            data: 'pr_status',
                            name: 'pr_status',
                        }
                    ],
                    initComplete: function() {
                        $.get(`/purchase-requisition/get-unique-status/${year}/${month}`, function(data) {
                            var select = $('#filter-status');
                            select.empty().append(
                            '<option value="">Filter PR Status</option>');
                            $.each(data, function(index, value) {
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
                $('#filter-status').on('change', function() {
                    table.ajax.reload();
                });
            })

            $('#prTable').on('click', '.qty', function() {
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

            $('#prTable').on('click', '.save-btn', function() {
                const id = $(this).data('id');
                const newQty = $(this).siblings('.qty').val();
                const prId = $(this).data('pr_id');
                const button = $(this);
                const table = $('#prTable').DataTable();

                Swal.fire({
                    title: `Konfirmasi Pembaruan`,
                    text: `Apakah Anda yakin ingin memperbarui Quantity untuk Purchase Requisition ${prId}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Perbarui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/purchase-requisition/update/${id}`,
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
                const table = $('#prTable').DataTable();

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
                            url: "{{ route('open-pr.delete') }}",
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
                                        $('#prTable').DataTable().ajax.reload();
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
                const table = $('#prTable').DataTable();
                const selectAllCheckbox = $('#selectAllRows');
                table.$('input[type="checkbox"]').prop('checked', selectAllCheckbox.prop('checked'));
            }
        </script>
    @endpush
@endsection
