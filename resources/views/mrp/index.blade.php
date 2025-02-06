@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <a href="{{ route('mrp.moq-mpq') }}"
            style="text-decoration: none; color: {{ request()->routeIs('mrp.moq-mpq') ? 'purple' : 'blue' }}">MOQ & MPQ |
        </a>
        <a href="{{ route('mrp.keb-material') }}"
            style="text-decoration: none; color: {{ request()->routeIs('mrp.keb-material') ? 'purple' : 'blue' }}">Kebutuhan
            Material
            |
        </a>
        <a href="{{ route('mrp.data-keb-production') }}"
            style="text-decoration: none; color: {{ request()->routeIs('mrp.data-keb-production') ? 'purple' : 'blue' }}">Kebutuhan
            Produksi
            |
        </a>
        <a href="{{ route('mrp.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('mrp.index') ? 'purple' : 'blue' }}"> Rencana
            Pembelian</a>

        <div class="col-lg-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="page-header ms-4 mt-3 mb-5">
                    <h3>Rencana Pembelian</h3>
                    <nav aria-label="breadcrumb" style="margin-right: 10px;">
                        <ol class="breadcrumb">
                            <li class="m-3">
                                <h4>Choose Month to Filter LT:</h4>
                            </li>
                            <li><input type="month" name="month-lt" id="month-lt" class="form-control"
                                    style="border-color:grey" value="{{ now()->format('Y-m') }}"></li>
                        </ol>
                    </nav>
                </div>
                <div class="card-body table-responsive" style="margin-top: -4%;">
                    <table id="example" class="display">
                        <thead>
                            <tr>
                                <th rowspan="2">Item Number</th>
                                <th rowspan="2">Part Number</th>
                                <th rowspan="2">Product Name</th>
                                <th rowspan="2">Supplier</th>
                                <th rowspan="2">LT</th>
                                <th rowspan="2">L/I</th>
                                <th rowspan="2">Type</th>
                                <th rowspan="2">Unit</th>
                                <th rowspan="2">MOQ</th>
                                <th rowspan="2">MPQ</th>
                                <th rowspan="2">Stok</th>
                                <th colspan="12" class="text-center"
                                    style="background-color: rgba(19, 19, 116, 0.729); color: white">
                                    <span id="current-month">{{ now()->format('F') }}</span> (LT-0)
                                </th>
                                <th colspan="11" class="text-center"
                                    style="background-color: rgba(184, 184, 126, 0.84); color: white">
                                    <span id="next-month">{{ now()->addMonth()->format('F') }}</span> (LT-1)
                                </th>
                            </tr>
                            <tr>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">Keb. Material</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">Buffer</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">Renc. Produksi</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">PO</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">PR</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">Manual Incoming</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">Balance</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">Renc Beli</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">MOQ + 1</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">MOQ</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">% Buffer</th>
                                <th style="background-color: rgba(209, 209, 243, 0.729)">Pembelian</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">Keb. Material</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">Buffer</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">Renc. Produksi</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">PO</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">PR</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">Balance</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">Renc Beli</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">MOQ + 1</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">MOQ</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">% Buffer</th>
                                <th style="background-color: rgba(255, 255, 226, 0.839)">Pembelian</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('scriptMrp')
        <script>
            document.getElementById('month-lt').addEventListener('change', function() {
                const monthValue = this.value;
                if (monthValue) {
                    const selectedDate = new Date(monthValue);
                    const currentMonthName = selectedDate.toLocaleString('default', {
                        month: 'long'
                    });
                    document.getElementById('current-month').textContent = currentMonthName;

                    // Update next month header
                    const nextDate = new Date(selectedDate);
                    nextDate.setMonth(nextDate.getMonth() + 1);
                    const nextMonthName = nextDate.toLocaleString('default', {
                        month: 'long'
                    });
                    document.getElementById('next-month').textContent = nextMonthName;

                    // Refresh the DataTable
                    $('#example').DataTable().ajax.reload();
                }
            });

            // Set default value for month input to current month
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date();
                const year = today.getFullYear();
                const month = (today.getMonth() + 1).toString().padStart(2, '0');
                document.getElementById('month-lt').value = `${year}-${month}`;
            });
            $('#example').DataTable({
                ajax: {
                    scrollX: true,
                    url: "{{ route('mrp.data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.selected_month = document.getElementById('month-lt').value;
                    },
                    serverside: true
                },
                columns: [{
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
                        data: 'spl',
                        name: 'spl'
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
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'unit_id',
                        name: 'bom.unit_id'
                    },
                    {
                        data: 'moq',
                        name: 'moq_mpq.moq'
                    },
                    {
                        data: 'mpq',
                        name: 'moq_mpq.mpq'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'keb_material',
                        name: 'temp_keb_material.keb_material'
                    },
                    {
                        data: 'buffer_qty',
                        name: 'buffer_qty'
                    },
                    {
                        data: 'keb_produksi',
                        name: 'temp_keb_produksi.keb_produksi'
                    },
                    {
                        data: 'open_po_qty',
                        name: 'open_po_qty'
                    },
                    {
                        data: 'open_pr_qty',
                        name: 'open_pr_qty'
                    },
                    {
                        data: 'incoming_manual_qty',
                        name: 'incoming_manual_qty'
                    },
                    {
                        data: 'balance_0',
                        name: 'balance_0'
                    },
                    {
                        data: 'renc_beli',
                        name: 'renc_beli'
                    },
                    {
                        data: 'moq_plus_1',
                        name: 'moq_plus_1'
                    },
                    {
                        data: 'moq_0',
                        name: 'moq_0'
                    },
                    {
                        data: 'percent_buff',
                        name: 'percent_buff'
                    },
                    {
                        data: 'pembelian_0',
                        name: 'pembelian_0'
                    },
                    {
                        data: 'keb_material',
                        name: 'temp_keb_material.keb_material'
                    },
                    {
                        data: 'buffer_qty',
                        name: 'buffer_qty'
                    },
                    {
                        data: 'keb_produksi',
                        name: 'temp_keb_produksi.keb_produksi'
                    },
                    {
                        data: 'open_po_qty',
                        name: 'open_po_qty'
                    },
                    {
                        data: 'open_pr_qty',
                        name: 'open_pr_qty'
                    },
                    {
                        data: 'balance_0',
                        name: 'balance_0'
                    },
                    {
                        data: 'renc_beli',
                        name: 'renc_beli'
                    },
                    {
                        data: 'moq_plus_1',
                        name: 'moq_plus_1'
                    },
                    {
                        data: 'moq_0',
                        name: 'moq_0'
                    },
                    {
                        data: 'percent_buff',
                        name: 'percent_buff'
                    },
                    {
                        data: 'pembelian_0',
                        name: 'pembelian_0'
                    },
                ]
            });
        </script>
    @endpush
@endsection
