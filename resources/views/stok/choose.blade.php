@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <a href="{{ route('buffer.index') }}" style="text-decoration: none; color: {{ request()->routeIs('buffer.index') ? 'purple' : 'blue' }}">Buffer |</a>
        <a href="{{ route('stok.index') }}" style="text-decoration: none; color: {{ request()->routeIs('stok.index') ? 'purple' : 'blue' }}"> Stock | </a>
        <a href="{{ route('buffer.stok.visualisasi') }}" style="text-decoration: none; color: {{ request()->routeIs('buffer.stok.visualisasi') ? 'purple' : 'blue' }}"> Visualization</a>
        
        <div class="col-lg-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="page-header ms-4 mt-3">
                    <h3>Choose Month to <span class="text-primary">View</span> or <span class="text-primary">Edit</span>
                        Data Stock</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><button type="button" class="btn btn-gradient-danger btn-rounded"
                                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                    onmouseover="this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.transform='scale(1)';" data-bs-toggle="modal"
                                    data-bs-target="#modalImportStok">Add New</button></li>
                            <li> <a href="{{ route('stok.format') }}">
                                    <button type="button" class="btn btn-gradient-info btn-rounded ms-2"
                                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                        onmouseover="this.style.transform='scale(1.05)';"
                                        onmouseout="this.style.transform='scale(1)';"><i class="mdi mdi-download"></i>
                                        Download Format</button></li></a>
                        </ol>
                    </nav>
                </div>

                {{-- Modal Import --}}
                <div class="modal fade" id="modalImportStok" tabindex="-1" aria-labelledby="modalLabelStok"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalLabelStok">Import File Stok</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('stok.import') }}" enctype="multipart/form-data" method="POST"
                                id="importStok">
                                @csrf
                                <div class="modal-body">
                                    <label for="date" class="form-label"><strong>Date</strong></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
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
                </div>

                <!-- Modal Update -->
                <div class="modal fade" id="modalUpdateStok" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Data Stok</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('stok.import') }}" enctype="multipart/form-data" method="POST"
                                id="importStok">
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
                            @forelse ($monthStok as $index => $ms)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::create($ms->year, $ms->month)->format('F Y') }}
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('stok.index-edit', ['year' => $ms->year, 'month' => $ms->month]) }}">
                                            <button class="btn btn-inverse-dark px-4">
                                                <i class="mdi mdi-table-edit"></i> Edit
                                            </button>
                                            <input type="hidden" id="year" name="year">
                                            <input type="hidden" id="month" name="month">
                                        </a>
                                        <button class="btn btn-inverse-success px-4" data-bs-toggle="modal"
                                            data-bs-target="#modalUpdateStok">
                                            <i class="mdi mdi-cloud-sync"></i> Update
                                        </button>
                                        <a href="{{ route('stok.view', ['year' => $ms->year, 'month' => $ms->month]) }}">
                                            <button class="btn btn-inverse-primary px-4">
                                                <i class="mdi mdi-magnify"></i> View
                                            </button>
                                            <input type="hidden" id="year" name="year">
                                            <input type="hidden" id="month" name="month">
                                        </a>
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
@endsection
