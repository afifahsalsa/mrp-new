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
                <div class="page-header ms-4 mt-3 mb-5">
                    <h3>Kebutuhan Produksi</h3>
                    <nav aria-label="breadcrumb">
                    </nav>
                </div>
                <div class="card-body table-responsive" style="margin-top: -4%;">
                    <table id="kebProduction" class="display">
                        <thead>
                            <tr>
                                <th>Kode FGS</th>
                                <th>Kode RMI</th>
                                <th>Keb. Material</th>
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
            $(document).ready(function() {
                $('#kebProduction').DataTable({
                    ajax: {
                        url: "{{ route('mrp.data-keb-production') }}",
                        type: 'GET',
                        serverside: true
                    },
                    columns: [{
                            data: 'kode_fgs',
                            name: 'kode_fgs'
                        },
                        {
                            data: 'kode_rmi',
                            name: 'kode_rmi'
                        },
                        {
                            data: 'keb_produksi',
                            name: 'keb_produksi'
                        }
                    ]
                });
            });
        </script>
    @endpush
@endsection
