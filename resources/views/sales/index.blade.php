@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="page-header ms-4 mt-3">
                    <h3>Quantity Sales</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><button type="button" class="btn btn-gradient-danger btn-rounded"
                                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                    onmouseover="this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.transform='scale(1)';" data-bs-toggle="modal"
                                    data-bs-target="#modalAddSales">Add New</button></li>
                        </ol>
                    </nav>
                </div>

                {{-- Modal add new sales --}}
                <div class="modal fade" id="modalAddSales" tabindex="-1" aria-labelledby="modalLabelSales"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalLabelSales">Add new sales</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('sales.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <label for="month" class="form-label mt-2"><strong>Choose Month</strong></label>
                                    <input type="month" name="bulan" class="form-control" id="month"
                                        value="<?php echo date('Y-m'); ?>">
                                    <label for="sales" class="form-label mt-2"><strong>Input Quantity
                                            Sales</strong></label>
                                    <input type="number" name="qty" placeholder="Quantity sales" class="form-control"
                                        id="sales">
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

                <div class="card-body" style="margin-top: -2%;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $index => $s)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $s->bulan}}</td>
                                    <td>{{ $s->tahun }}</td>
                                    <td>{{ $s->qty }}</td>
                                    <td>
                                        <button class="btn btn-inverse-primary px-4" data-bs-toggle="modal"
                                            data-bs-target="#modalUpdateSales{{ $s->id }}">
                                            <i class="mdi mdi-cloud-sync"></i> Update
                                        </button>

                                        <!-- Modal Update Sales -->
                                        <div class="modal fade" id="modalUpdateSales{{ $s->id }}" tabindex="-1"
                                            aria-labelledby="modalLabelSales" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="modalLabelSales"> Update Sales</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('sales.update', $s->id) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                                <label for="bulan" class="form-label"><strong>Month</strong></label>
                                                                <input type="month" name="bulan" class="form-control" value="{{ sprintf('%04d-%02d', $s->tahun, $monthNumbers[$s->id]) }}">
                                                            <label for="" class="form-label mt-2"><strong>Quantity Sales</strong></label>
                                                            <input class="form-control" type="text" name="qty" value="{{ $s->qty }}">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal"
                                                                style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);">
                                                                Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary" id="submitButton"
                                                                style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);">
                                                                Submit
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-inverse-danger px-4" onclick="confirmDelete({{ $s->id }})">
                                            <i class="mdi mdi-delete-forever"></i> Delete
                                        </button>
                                        <form id="delete-form-{{ $s->id }}" action="{{ route('sales.destroy', $s->id) }}" method="post" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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

    @push('scriptSales')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
    @endpush
@endsection
