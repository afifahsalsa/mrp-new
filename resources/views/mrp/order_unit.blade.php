@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Tables</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Basic Table</li>
                </ol>
            </nav>
        </div>

        {{-- Modal Import MPP --}}
        {{-- <div class="modal fade" id="modalImportPP" tabindex="-1" aria-labelledby="modalLabelMPP" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalLabelMPP">Import File Production Planning</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('planning-production.import') }}" enctype="multipart/form-data" method="POST" id="importPP">
                        @csrf
                        <div class="modal-body">
                            <label for="month" class="form-label"><strong>Choose Month</strong></label>
                            <input type="month" name="month" id="month" class="form-control"
                                value="<?php echo date('Y-m'); ?>" required>
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
        </div> --}}

        {{-- Table MPP --}}
        <div class="card shadow mb-4">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Order In Unit</h4>
                        <p class="card-description"> Add class <code>.table-striped</code>
                        </p>
                        <div class="table-responsive">
                            <table class="table display" id="orderUnitTable" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select-checkbox" onclick="selectAll()"
                                                id="selectAllRows"></th>
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
                                        <th>Bulan Up</th>
                                        <th>Tahun</th>
                                        <th>Total</th>
                                        <th>AVG</th>
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
    @push('scriptPP')
        <script>
            let orderUnitTable = $('#orderUnitTable').DataTable({
                "lengthMenu": [10, 25, 50, 100, 500, 1000],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('order-unit.data') }}",
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
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'model',
                        name: 'model'
                    },
                    {
                        data: 'kodefgs',
                        name: 'kodefgs'
                    },
                    {
                        data: 'partnumber',
                        name: 'partnumber'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'bulan_1',
                        name: 'bulan_1'
                    },
                    {
                        data: 'bulan_2',
                        name: 'bulan_2'
                    },
                    {
                        data: 'bulan_3',
                        name: 'bulan_3'
                    },
                    {
                        data: 'bulan_4',
                        name: 'bulan_4'
                    },
                    {
                        data: 'bulan_5',
                        name: 'bulan_5'
                    },
                    {
                        data: 'bulan_6',
                        name: 'bulan_6'
                    },
                    {
                        data: 'bulan_7',
                        name: 'bulan_7'
                    },
                    {
                        data: 'bulan_8',
                        name: 'bulan_8'
                    },
                    {
                        data: 'bulan_9',
                        name: 'bulan_9'
                    },
                    {
                        data: 'bulan_10',
                        name: 'bulan_10'
                    },
                    {
                        data: 'bulan_11',
                        name: 'bulan_11'
                    },
                    {
                        data: 'bulan_12',
                        name: 'bulan_12'
                    },
                    {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'average',
                        name: 'average'
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
        </script>
    @endpush
@endsection
