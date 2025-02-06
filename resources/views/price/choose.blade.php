@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="page-header ms-4 mt-3">
                    <h3>Choose Month to <span class="text-primary">View</span> or <span class="text-primary">Edit</span>
                        Data Price</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><button type="button" class="btn btn-gradient-danger btn-rounded"
                                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                    onmouseover="this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.transform='scale(1)';" data-bs-toggle="modal"
                                    data-bs-target="#modalImportPrice">Add New</button></li>
                            <form action="{{ route('price.format') }}" enctype="multipart/form-data" method="GET">
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
                <div class="modal fade" id="modalImportPrice" tabindex="-1" aria-labelledby="modalLabelPrice"
                    aria-hidden="true" onsubmit="showLoading()">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalLabelPrice">Import File Price</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('price.import') }}" enctype="multipart/form-data" method="POST"
                                id="importPrice">
                                @csrf
                                <div class="modal-body">
                                    <label for="date" class="form-label"><strong>Date</strong></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                    <label for="file" class="form-label mt-2"><strong>Choose File</strong></label>
                                    <input class="form-control" type="file" id="file" name="file" accept=".xlsx" required>
                                    {{-- <p style="font-size: 12px">Pastikan Item Number tidak ada yang double, price dan currency tidak ada yang blank!</p> --}}
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

                <!-- Modal Update -->
                <div class="modal fade" id="modalUpdatePrice" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Data Price</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('price.import') }}" enctype="multipart/form-data" method="POST"
                                id="importPrice">
                                @csrf
                                <div class="modal-body">
                                    <label for="date" class="form-label"><strong>Date</strong></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                    <label for="date" class="form-label mt-2"><strong>Choose File</strong></label>
                                    <input class="form-control" type="file" id="file" name="file" accept=".xlsx" required>
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
                            @forelse ($monthPrice as $index => $mp)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::create($mp->year, $mp->month)->format('F Y') }}
                                    </td>
                                    <td>
                                        {{-- <a
                                            href="{{ route('open-pr.index-edit', ['year' => $mp->year, 'month' => $mp->month]) }}">
                                            <button class="btn btn-inverse-dark px-4">
                                                <i class="mdi mdi-table-edit"></i> Edit
                                            </button>
                                            <input type="hidden" id="year" name="year">
                                            <input type="hidden" id="month" name="month">
                                        </a> --}}
                                        <button class="btn btn-inverse-success px-4" data-bs-toggle="modal"
                                            data-bs-target="#modalUpdatePrice">
                                            <i class="mdi mdi-cloud-sync"></i> Update
                                        </button>
                                        <button class="btn btn-inverse-primary px-4" data-bs-toggle="modal"
                                            data-bs-target="#viewModal" data-year="{{ $mp->year }}"
                                            data-month="{{ $mp->month }}">
                                            <i class="mdi mdi-magnify"></i> View
                                        </button>
                                        <div class="modal fade" id="viewModal" tabindex="-1"
                                            aria-labelledby="viewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
                                                style="width: 100rem;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="viewModalLabel">Price
                                                            in : <span id="modalMonthYear" class="text-danger"></span>
                                                        </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table display" id="priceTable"
                                                                style="width: 100%">
                                                                <div class="d-flex mb-4">
                                                                    <h4 class="mt-3">Filter: </h4>
                                                                    <select id="filter-currency" class="form-select mt-2"
                                                                        style="width: 20%; margin-left: 20px;">
                                                                        <option value="">Currency</option>
                                                                    </select>
                                                                    <form
                                                                            action="{{ route('price.delete') }}"
                                                                            method="DELETE" id="delPrice">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <button
                                                                                class="btn btn-danger ms-2 px-3 float-start"
                                                                                type="button"
                                                                                style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform:scale(1);"
                                                                                onmouseover="this.style.transform='scale(1.05)';"
                                                                                onmouseout="this.style.transform='scale(1)';"
                                                                                onclick="deleteConfirm('delPrice')"><i
                                                                                    class="mdi mdi-delete"></i></button>
                                                                        </form>
                                                                </div>
                                                                <thead>
                                                                    <tr>
                                                                        <th><input type="checkbox" name="select-checkbox"
                                                                            id="selectAll"></th>
                                                                        <th>Item ID</th>
                                                                        <th>Category Item</th>
                                                                        <th>Part Name</th>
                                                                        <th>Part Number</th>
                                                                        <th>Search Name</th>
                                                                        <th>Satuan</th>
                                                                        <th>Price</th>
                                                                        <th>Currency</th>
                                                                        <th>Value Currency</th>
                                                                        <th>Price IDR</th>
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

    @push('scriptPrice')
        <script>
            $(document).ready(function() {
                $('#viewModal').on('show.bs.modal', function(event) {
                    let button = $(event.relatedTarget);
                    let year = button.data('year');
                    let month = button.data('month');

                    const table = $('#priceTable').DataTable({
                        "lengthMenu": [10, 25, 50, 100],
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: `/price/load-data/${year}/${month}`,
                            type: 'GET',
                            data: function(d) {
                                var currencyValue = $('#filter-currency').val();
                                if (currencyValue) {
                                    d.currency = currencyValue;
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
                                data: 'item_id'
                            },
                            {
                                data: 'category_item'
                            },
                            {
                                data: 'part_name'
                            },
                            {
                                data: 'part_number'
                            },
                            {
                                data: 'search_name'
                            },
                            {
                                data: 'satuan'
                            },
                            {
                                data: 'price'
                            },
                            {
                                data: 'currency'
                            },
                            {
                                data: 'val_currency'
                            },
                            {
                                data: 'price_idr'
                            }
                        ],
                        initComplete: function() {
                            $.get(`/price/get-unique-currency/${year}/${month}`, function(
                                data) {
                                var select = $('#filter-currency');
                                select.empty().append(
                                    '<option value="">Filter Currency</option>');
                                $.each(data, function(index, value) {
                                    select.append(
                                        `<option value="${value}">${value}</option>`
                                    );
                                });
                            });
                        },
                        responsive: true,
                        autoWidth: false
                    });
                    $('#filter-currency').on('change', function() {
                        table.ajax.reload();
                    });
                });
                $('#viewModal').on('hidden.bs.modal', function() {
                    if ($.fn.DataTable.isDataTable('#priceTable')) {
                        $('#priceTable').DataTable().destroy();
                    }
                });
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

            function deleteConfirm(formId) {
                const selectedRows = [];
                const table = $('#priceTable').DataTable();

                table.$('input[type="checkbox"]:checked').each(function() {
                    const row = table.row($(this).closest('tr'));
                    const selectedID = row.data().id;
                    selectedRows.push(selectedID);
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
                            url: "{{ route('price.delete') }}",
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
                                        $('#priceTable').DataTable().ajax.reload();
                                        $('#selectAllRows').prop('checked', false);
                                    });
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 400) {
                                    const response = JSON.parse(xhr.responseText);
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
                const table = $('#priceTable').DataTable();
                table.$('input[type="checkbox"]').prop('checked', isChecked);
            });
        </script>
    @endpush
@endsection
