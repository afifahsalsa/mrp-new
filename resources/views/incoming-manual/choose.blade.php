@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <a href="{{ route('open-po.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('open-po.index') ? 'purple' : 'blue' }}">Purchase
            Order |</a>
        <a href="{{ route('open-pr.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('open-pr.index') ? 'purple' : 'blue' }}">
            Purchase Requisition | </a>
        <a href="{{ route('incomong-manual.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('incomong-manual.index') ? 'purple' : 'blue' }}">
            Incoming Manual | </a>
        <a href="#"
            style="text-decoration: none; color: {{ request()->routeIs('buffer.stok.visualisasi') ? 'purple' : 'blue' }}">
            Visualization</a>

        <div class="col-lg-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="page-header ms-4 mt-3">
                    <h3>Choose Month to <span class="text-primary">View</span> or <span class="text-primary">Edit</span>
                        Data Incoming Manual</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><button type="button" class="btn btn-gradient-danger btn-rounded"
                                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                    onmouseover="this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.transform='scale(1)';" data-bs-toggle="modal"
                                    data-bs-target="#modalImportIM">Add New</button></li>
                            <li> <a href="{{ route('incoming-manual.format') }}">
                                    <button type="button" class="btn btn-gradient-info btn-rounded ms-2"
                                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                        onmouseover="this.style.transform='scale(1.05)';"
                                        onmouseout="this.style.transform='scale(1)';"><i class="mdi mdi-download"></i>
                                        Download Format</button></li></a>
                        </ol>
                    </nav>
                </div>

                {{-- Modal Import --}}
                <div class="modal fade" id="modalImportIM" tabindex="-1" aria-labelledby="modalLabelIM" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalLabelIM">Import File Incoming Manual</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('incoming-manual.import') }}" enctype="multipart/form-data"
                                method="POST" id="importIM" onsubmit="showLoading()">
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
                <div class="modal fade" id="modalUpdateIM" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Data Purchase Order</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('incoming-manual.import') }}" enctype="multipart/form-data"
                                method="POST" id="importIM">
                                @csrf
                                <div class="modal-body">
                                    <label for="date" class="form-label"><strong>Date</strong></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                    <label for="file" class="form-label mt-2"><strong>Choose File</strong></label>
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
                            @forelse ($monthIncoming as $index => $mi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::create($mi->year, $mi->month)->format('F Y') }}
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('open-po.index-edit', ['year' => $mi->year, 'month' => $mi->month]) }}">
                                            <button class="btn btn-inverse-dark px-4">
                                                <i class="mdi mdi-table-edit"></i> Edit
                                            </button>
                                            <input type="hidden" id="year" name="year">
                                            <input type="hidden" id="month" name="month">
                                        </a>
                                        <button class="btn btn-inverse-success px-4" data-bs-toggle="modal"
                                            data-bs-target="#modalUpdateIM">
                                            <i class="mdi mdi-cloud-sync"></i> Update
                                        </button>
                                        <button class="btn btn-inverse-primary px-4" data-bs-toggle="modal"
                                            data-bs-target="#viewModal" data-year="{{ $mi->year }}"
                                            data-month="{{ $mi->month }}">
                                            <i class="mdi mdi-magnify"></i> View
                                        </button>
                                        <div class="modal fade" id="viewModal" tabindex="-1"
                                            aria-labelledby="viewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
                                                style="width: 100rem;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="viewModalLabel"> Incoming Manual in :
                                                            <span id="modalMonthYear" class="text-danger"></span>
                                                        </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table display" id="imTable"
                                                                style="width: 100%">
                                                                <div class="d-flex mb-4 position-relative">
                                                                    <h4 class="mt-2 me-1">Filter: </h4>
                                                                    <div class="dropdown" style="position: relative; margin-left: 10px;">
                                                                        <input type="text"
                                                                            class="form-control dropdown-toggle"
                                                                            id="searchDropdown" data-bs-toggle="dropdown"
                                                                            aria-expanded="false"
                                                                            placeholder="Select Item Number" readonly
                                                                            style=" background-color: white; cursor: pointer; border: 1px solid #ced4da; border-radius: 0.375rem;">
                                                                        <div class="dropdown-menu custom-dropdown-menu"
                                                                            aria-labelledby="searchDropdown"
                                                                            style="width: 100%; max-height: 350px; overflow-y: auto;
                                                                                    padding: 0; margin-top: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e0e0e0; border-radius: 0.375rem;">
                                                                            <div class="search-container"
                                                                                style="padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #e0e0e0;
                                                                                        position: sticky; top: 0; z-index: 10;">
                                                                                <input type="text" class="form-control"
                                                                                    id="filterInput"
                                                                                    placeholder="Search in list..."
                                                                                    onkeyup="filterList()"
                                                                                    style="border-radius: 0.25rem; border: 1px solid #ced4da; padding: 0.25rem 0.5rem;">
                                                                            </div>
                                                                            <ul class="list-group list-group-flush"
                                                                                id="dropdownMenuItems"
                                                                                style="max-height: 250px; overflow-y: auto;">
                                                                                <li class="list-group-item list-group-item-action"
                                                                                    style="cursor: pointer; padding: 0.5rem 1rem; transition: background-color 0.2s;"
                                                                                    data-value="All">
                                                                                    <span class="text-muted">All Item Number</span>
                                                                                </li>
                                                                                {{-- @foreach ($uniquePOs as $po)
                                                                                    <li class="list-group-item list-group-item-action"
                                                                                        style="cursor: pointer; padding: 0.5rem 1rem; transition: background-color 0.2s;"
                                                                                        data-value="{{ $po }}">
                                                                                        {{ $po }}
                                                                                    </li>
                                                                                @endforeach --}}
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                    <div class="dropdown" style="position: relative; margin-left: 20px;">
                                                                        <input type="text"
                                                                            class="form-control dropdown-toggle"
                                                                            id="searchDropdown" data-bs-toggle="dropdown"
                                                                            aria-expanded="false"
                                                                            placeholder="Select Purchase Order" readonly
                                                                            style=" background-color: white; cursor: pointer; border: 1px solid #ced4da; border-radius: 0.375rem;">
                                                                        <div class="dropdown-menu custom-dropdown-menu"
                                                                            aria-labelledby="searchDropdown"
                                                                            style="width: 100%; max-height: 350px; overflow-y: auto;
                                                                                    padding: 0; margin-top: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e0e0e0; border-radius: 0.375rem;">
                                                                            <div class="search-container"
                                                                                style="padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #e0e0e0;
                                                                                        position: sticky; top: 0; z-index: 10;">
                                                                                <input type="text" class="form-control"
                                                                                    id="filterInput"
                                                                                    placeholder="Search in list..."
                                                                                    onkeyup="filterList()"
                                                                                    style="border-radius: 0.25rem; border: 1px solid #ced4da; padding: 0.25rem 0.5rem;">
                                                                            </div>
                                                                            <ul class="list-group list-group-flush"
                                                                                id="dropdownMenuItems"
                                                                                style="max-height: 250px; overflow-y: auto;">
                                                                                <li class="list-group-item list-group-item-action"
                                                                                    style="cursor: pointer; padding: 0.5rem 1rem; transition: background-color 0.2s;"
                                                                                    data-value="All">
                                                                                    <span class="text-muted">All Purchase
                                                                                        Orders</span>
                                                                                </li>
                                                                                {{-- @foreach ($uniquePOs as $po)
                                                                                    <li class="list-group-item list-group-item-action"
                                                                                        style="cursor: pointer; padding: 0.5rem 1rem; transition: background-color 0.2s;"
                                                                                        data-value="{{ $po }}">
                                                                                        {{ $po }}
                                                                                    </li>
                                                                                @endforeach --}}
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                    <a href="{{ route('open-po.export', ['year' => $mi->year, 'month' => $mi->month]) }}"
                                                                        class="ms-auto" onload="showLoading()">
                                                                        <button type="button"
                                                                            class="btn btn-gradient-success"
                                                                            style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                                                            onmouseover="this.style.transform='scale(1.05)';"
                                                                            onmouseout="this.style.transform='scale(1)';">
                                                                            <i class="mdi mdi-cloud-download"></i>
                                                                            Download Excel
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                                <thead>
                                                                    <tr>
                                                                        <th>Supplier</th>
                                                                        <th>Tanggal Kedatangan</th>
                                                                        <th>Item Number</th>
                                                                        <th>Part Number</th>
                                                                        <th>Purchase Order</th>
                                                                        <th>Qty</th>
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
    @push('scriptIM')
        <script>
            $(document).ready(function() {
                $('#viewModal').on('show.bs.modal', function(event) {
                    let button = $(event.relatedTarget);
                    let year = button.data('year');
                    let month = button.data('month');

                    const table = $('#imTable').DataTable({
                        "lengthMenu": [10, 25, 50, 100],
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: `/ppic/incoming-manual/load-data/${year}/${month}`,
                            type: 'GET',
                        },
                        columns: [{
                                data: 'supplier',
                                className: 'text-start'
                            },
                            {
                                data: 'tgl_kedatangan',
                                className: 'text-start'
                            },
                            {
                                data: 'item_number',
                                className: 'text-start'
                            },
                            {
                                data: 'part_number',
                                className: 'text-start'
                            },
                            {
                                data: 'purchase_order',
                                className: 'text-start'
                            },
                            {
                                data: 'qty',
                                className: 'text-start'
                            },
                        ],
                        responsive: true,
                        autoWidth: false
                    });
                });
                $('#viewModal').on('hidden.bs.modal', function() {
                    if ($.fn.DataTable.isDataTable('#imTable')) {
                        $('#imTable').DataTable().destroy();
                    }
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
                    if ($.fn.DataTable.isDataTable('#imTable')) {
                        var table = $('#imTable').DataTable();
                        table.column(0).search(selectedValue === 'All' ? '' : selectedValue).draw();
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

            document.addEventListener('DOMContentLoaded', function() {
                const viewModal = document.getElementById('viewModal');
                const modalMonthYear = document.getElementById('modalMonthYear');
                const downloadLink = document.getElementById('downloadLink');

                viewModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const year = button.getAttribute('data-year');
                    const month = button.getAttribute('data-month');
                    const monthName = new Date(year, month - 1).toLocaleString('default', {
                        month: 'long'
                    });
                    modalMonthYear.textContent = `${monthName}, ${year}`;
                    downloadLink.href = `/ppic/purchase-order/export/${year}/${month}`;
                });
            });
        </script>
    @endpush
@endsection
