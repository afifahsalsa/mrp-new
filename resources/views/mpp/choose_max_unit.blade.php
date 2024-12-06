@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <a href="{{ route('order-customer.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('order-customer.index') ? 'purple' : 'blue' }}">Order
            Customer |</a>
        <a href="{{ route('prod-plan.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('prod-plan.index') ? 'purple' : 'blue' }}">
            Production Planning | </a>
        <a href="{{ route('max.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('max.index') ? 'purple' : 'blue' }}">
            Maximum Unit</a>

        <div class="col-lg-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="page-header ms-4 mt-3">
                    <h3>Choose Month to <span class="text-primary">View</span> or <span class="text-primary">Edit</span>
                        Data Maximum Unit</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><button type="button" class="btn btn-gradient-danger btn-rounded"
                                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                    onmouseover="this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.transform='scale(1)';" data-bs-toggle="modal"
                                    data-bs-target="#modalImportMpp">Add New</button></li>
                            <li> <a href="{{ route('mpp.format') }}">
                                    <button type="button" class="btn btn-gradient-info btn-rounded ms-2"
                                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                        onmouseover="this.style.transform='scale(1.05)';"
                                        onmouseout="this.style.transform='scale(1)';"><i class="mdi mdi-download"></i>
                                        Download Format</button></li></a>
                        </ol>
                    </nav>
                </div>

                {{-- Modal Import --}}
                <div class="modal fade" id="modalImportMpp" tabindex="-1" aria-labelledby="modalLabelMpp"
                    aria-hidden="true" onsubmit="showLoading()">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalLabelMpp">Import File MPP</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('mpp.import') }}" enctype="multipart/form-data" method="POST"
                                id="importMpp">
                                @csrf
                                <div class="modal-body">
                                    <label for="month" class="form-label"><strong>Choose Month</strong></label>
                                    <input type="month" name="month" id="month" class="form-control"
                                        value="<?php echo date('Y-m'); ?>" required>
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

                <!-- Modal Update -->
                <div class="modal fade" id="modalUpdateMpp" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Data MPP</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('mpp.import') }}" enctype="multipart/form-data" method="POST"
                                id="importMpp">
                                @csrf
                                <div class="modal-body">
                                    <label for="month" class="form-label"><strong>Choose Month</strong></label>
                                    <input type="month" name="month" id="month" class="form-control"
                                        value="<?php echo date('Y-m'); ?>" required>
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
                            @forelse ($monthMaxUnit as $index => $mx)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::create($mx->year, $mx->month)->format('F Y') }}
                                    </td>
                                    <td>
                                        {{-- <a
                                            href="{{ route('buffer.index-edit', ['year' => $mx->year, 'month' => $mx->month]) }}">
                                            <button class="btn btn-inverse-dark px-4">
                                                <i class="mdi mdi-table-edit"></i> Edit
                                            </button>
                                            <input type="hidden" id="year" name="year">
                                            <input type="hidden" id="month" name="month">
                                        </a> --}}
                                        <button class="btn btn-inverse-success px-4" data-bs-toggle="modal"
                                            data-bs-target="#modalUpdateMpp">
                                            <i class="mdi mdi-cloud-sync"></i> Update
                                        </button>
                                        <button class="btn btn-inverse-primary px-4" data-bs-toggle="modal"
                                            data-bs-target="#viewModal" data-year="{{ $mx->year }}"
                                            data-month="{{ $mx->month }}">
                                            <i class="mdi mdi-magnify"></i> View
                                        </button>
                                        <div class="modal fade" id="viewModal" tabindex="-1"
                                            aria-labelledby="viewModalMpp" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
                                                style="width: 100rem;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="viewModalMpp">Production Planning in :
                                                            <span
                                                                class="text-danger">{{ \Carbon\Carbon::create()->month($mx->month)->format('F') .', ' .$mx->year }}</span>
                                                        </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table display" id="maxUnitTable"
                                                                style="width: 100%">
                                                                <div class="d-flex mb-4">
                                                                    <h4 class="mt-3">Filter: </h4>
                                                                    <select id="filter-lt" class="form-select mt-2"
                                                                        style="width: 20%; margin-left: 20px;">
                                                                        <option value="">Filter LT</option>
                                                                    </select>
                                                                </div>
                                                                <thead>
                                                                    <tr>
                                                                        <th>Customer</th>
                                                                        <th>Model</th>
                                                                        <th>Kode FGS</th>
                                                                        <th>Part Number</th>
                                                                        <th>Kategori</th>
                                                                        <th>Bulan 1</th>
                                                                        <th>Bulan 2</th>
                                                                        <th>Bulan 3</th>
                                                                        <th>Bulan 4</th>
                                                                        <th>Bulan 5</th>
                                                                        <th>Bulan 6</th>
                                                                        <th>Bulan 7</th>
                                                                        <th>Bulan 8</th>
                                                                        <th>Bulan 9</th>
                                                                        <th>Bulan 10</th>
                                                                        <th>Bulan 11</th>
                                                                        <th>Bulan 12</th>
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
    @push('scriptMpp')
        <script>
            $(document).ready(function() {
                $('#viewModal').on('show.bs.modal', function(event) {
                    let button = $(event.relatedTarget);
                    let year = button.data('year');
                    let month = button.data('month');

                    function generateMonthNames(startMonth, startYear) {
                        let monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                            'July', 'August', 'September', 'October', 'November', 'December'
                        ];
                        let monthLabels = [];

                        for (let i = 0; i < 12; i++) {
                            let currentMonth = (parseInt(startMonth) + i - 1) % 12;
                            let currentYear = startYear + Math.floor((parseInt(startMonth) + i - 1) / 12);
                            monthLabels.push(`${monthNames[currentMonth]} ${currentYear}`);
                        }
                        return monthLabels;
                    }

                    const monthLabels = generateMonthNames(month, year);

                    const table = $('#maxUnitTable').DataTable({
                        "lengthMenu": [10, 25, 50, 100],
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: `/ppic/mpp/order-original/load-data/${year}/${month}`,
                            type: 'GET',
                        },
                        columns: [{
                                data: 'customer'
                            },
                            {
                                data: 'model'
                            },
                            {
                                data: 'kodefgs'
                            },
                            {
                                data: 'partnumber'
                            },
                            {
                                data: 'kategori'
                            },
                            {
                                data: 'max_bulan_1',
                                title: monthLabels[0]
                            },
                            {
                                data: 'max_bulan_2',
                                title: monthLabels[1]
                            },
                            {
                                data: 'max_bulan_3',
                                title: monthLabels[2]
                            },
                            {
                                data: 'max_bulan_4',
                                title: monthLabels[3]
                            },
                            {
                                data: 'max_bulan_5',
                                title: monthLabels[4]
                            },
                            {
                                data: 'max_bulan_6',
                                title: monthLabels[5]
                            },
                            {
                                data: 'max_bulan_7',
                                title: monthLabels[6]
                            },
                            {
                                data: 'max_bulan_8',
                                title: monthLabels[7]
                            },
                            {
                                data: 'max_bulan_9',
                                title: monthLabels[8]
                            },
                            {
                                data: 'max_bulan_10',
                                title: monthLabels[9]
                            },
                            {
                                data: 'max_bulan_11',
                                title: monthLabels[10]
                            },
                            {
                                data: 'max_bulan_12',
                                title: monthLabels[11]
                            }
                        ],
                        headerCallback: function(thead) {
                            $(thead).find('th').each(function(index) {
                                if (index > 4) {
                                    $(this).text(monthLabels[index - 5]);
                                }
                            });
                        }
                    });
                });

                $('#viewModal').on('hidden.bs.modal', function() {
                    if ($.fn.DataTable.isDataTable('#maxUnitTable')) {
                        $('#maxUnitTable').DataTable().destroy();
                    }
                });
            });
        </script>
    @endpush
@endsection
