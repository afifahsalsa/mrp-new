@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <a href="{{ route('open-po.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('open-po.index') ? 'purple' : 'blue' }}">Purchase
            Order |</a>
        <a href="{{ route('open-pr.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('open-pr.index') ? 'purple' : 'blue' }}">
            Purchase Requisition | </a>
        <a href="#"
            style="text-decoration: none; color: {{ request()->routeIs('buffer.stok.visualisasi') ? 'purple' : 'blue' }}">
            Visualization</a>

        <div class="col-lg-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="page-header ms-4 mt-3">
                    <h3>Choose Month to <span class="text-primary">View</span> or <span class="text-primary">Edit</span>
                        Data Purchase Requisition</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><button type="button" class="btn btn-gradient-danger btn-rounded"
                                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                    onmouseover="this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.transform='scale(1)';" data-bs-toggle="modal"
                                    data-bs-target="#modalImportPR">Add New</button></li>
                            <form action="{{ route('open-pr.format') }}" enctype="multipart/form-data" method="GET">
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
                <div class="modal fade" id="modalImportPR" tabindex="-1" aria-labelledby="modalLabelPR" aria-hidden="true"
                    onsubmit="showLoading()">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalLabelPR">Import File Purchase Requisition</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('open-pr.import') }}" enctype="multipart/form-data" method="POST"
                                id="importPR">
                                @csrf
                                <div class="modal-body">
                                    <label for="date" class="form-label"><strong>Date</strong></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                    <label for="file" class="form-label mt-2"><strong>Choose File</strong></label>
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

                <!-- Modal Update -->
                <div class="modal fade" id="modalUpdatePR" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Data Purchase Order</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('open-pr.import') }}" enctype="multipart/form-data" method="POST"
                                id="importPR">
                                @csrf
                                <div class="modal-body">
                                    <label for="date" class="form-label"><strong>Date</strong></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                    <label for="date" class="form-label mt-2"><strong>Choose File</strong></label>
                                    <input class="form-control" type="file" id="file" name="file" required>
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
                            @forelse ($monthPR as $index => $mp)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::create($mp->year, $mp->month)->format('F Y') }}
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('open-pr.index-edit', ['year' => $mp->year, 'month' => $mp->month]) }}">
                                            <button class="btn btn-inverse-dark px-4">
                                                <i class="mdi mdi-table-edit"></i> Edit
                                            </button>
                                            <input type="hidden" id="year" name="year">
                                            <input type="hidden" id="month" name="month">
                                        </a>
                                        <button class="btn btn-inverse-success px-4" data-bs-toggle="modal"
                                            data-bs-target="#modalUpdatePR">
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
                                                        <h3 class="modal-title" id="viewModalLabel">Purchase Requisition
                                                            in : <span id="modalMonthYear" class="text-danger"></span>
                                                        </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table display" id="prTable"
                                                                style="width: 100%">
                                                                <div class="d-flex mb-4">
                                                                    <h4 class="mt-3">Filter: </h4>
                                                                    <select id="filter-status" class="form-select mt-2"
                                                                        style="width: 20%; margin-left: 20px;">
                                                                        <option value="">PR Status</option>
                                                                    </select>
                                                                </div>
                                                                <thead>
                                                                    <tr>
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
    @push('scriptPo')
        <script>
            $(document).ready(function() {
                $('#viewModal').on('show.bs.modal', function(event) {
                    let button = $(event.relatedTarget);
                    let year = button.data('year');
                    let month = button.data('month');

                    const table = $('#prTable').DataTable({
                        "lengthMenu": [10, 25, 50, 100],
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: `/ppic/purchase-requisition/load-data/${year}/${month}`,
                            type: 'GET',
                            data: function(d) {
                                var statusValue = $('#filter-status').val();
                                if (statusValue) {
                                    d.pr_status = statusValue;
                                }
                            }
                        },
                        columns: [{
                                data: 'pr_id'
                            },
                            {
                                data: 'item_id'
                            },
                            {
                                data: 'part'
                            },
                            {
                                data: 'old_name'
                            },
                            {
                                data: 'pr_date'
                            },
                            {
                                data: 'request_date'
                            },
                            {
                                data: 'qty'
                            },
                            {
                                data: 'pr_status'
                            }
                        ],
                        initComplete: function() {
                            $.get(`/ppic/purchase-requisition/get-unique-status/${year}/${month}`,
                                function(
                                    data) {
                                    var select = $('#filter-status');
                                    select.empty().append(
                                        '<option value="">Filter PR Status</option>');
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
                    $('#filter-status').on('change', function() {
                        table.ajax.reload();
                    });
                });
                $('#viewModal').on('hidden.bs.modal', function() {
                    if ($.fn.DataTable.isDataTable('#prTable')) {
                        $('#prTable').DataTable().destroy();
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
        </script>
    @endpush
@endsection
