@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <a href="{{ route('buffer.index') }}"><button class="btn btn-inverse-dark px-4" style="margin-left: -15px;">
                        <i class="mdi mdi-arrow-left-bold-circle"></i>
                    </button></a>
                    <h3 class="ms-2 mt-2">Buffer in : <span class="text-danger">{{ $monthName . ',  ' . $year }}</span></h3>
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
                            <div class="d-flex">
                                <h4 class="mt-3">Filter: </h4>
                                <select id="filter-lt" class="form-select mt-2" style="width: 20%; margin-left: 20px;">
                                    <option value="">Filter LT</option>
                                </select>
                            </div>
                            <table class="table display" id="bufferTable" style="width: 100%">
                                <thead>
                                    <tr>
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

            const table = $('#bufferTable').DataTable({
                "lengthMenu": [10, 25, 50, 100, 500, 1000],
                processing: true,
                serverSide: true,
                searching: true,
                scrollX: true,
                ajax: {
                    url: `/buffer/load-data/${year}/${month}`,
                    type: 'GET',
                    data: function(d) {
                        var ltValue = $('#filter-lt').val();
            if (ltValue) {
                d.lt = ltValue;
            }
                    }
                },
                columns: [
                    { data: 'item_number', name: 'item_number' },
                    { data: 'part_number', name: 'part_number' },
                    { data: 'product_name', name: 'product_name' },
                    { data: 'lt', name: 'lt' },
                    { data: 'supplier', name: 'supplier' },
                    { data: 'qty', name: 'qty' },
                    { data: 'date', name: 'date' }
                ],
                initComplete: function () {
                    $.get(`/buffer/get-unique-lt/${year}/${month}`, function(data) {
                        var select = $('#filter-lt');
                        select.empty().append('<option value="">Filter LT</option>');
                        $.each(data, function(index, value) {
                            select.append(`<option value="${value}">${value}</option>`);
                        });
                    });
                }
            });

            $('#filter-lt').on('change', function(){
                table.ajax.reload();
            });
        });
    </script>
    @endpush
@endsection
