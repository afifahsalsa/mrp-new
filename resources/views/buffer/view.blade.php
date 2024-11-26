@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            {{-- <h3 class="page-title">Buffer Table</h3> --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <a href="{{ route('buffer.index') }}"><button class="btn btn-inverse-dark px-4"
                            style="margin-left: -15px;"><i class="mdi mdi-arrow-left-bold-circle"></i></button></a>
                </ol>
            </nav>
            <div class="d-flex">
                <a class="dropdown-item" href="{{ route('buffer.export', ['year' => $year, 'month' => $month]) }}">
                    <button type="button" class="btn btn-gradient-success"
                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                        onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                        <i class="mdi mdi-cloud-download"></i> Download Excel </button> </a>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
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
        </script>
    @endpush
@endsection
