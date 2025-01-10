@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="page-header ms-4 mt-3">
                    <h3>Manage Data User Registered</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><button type="button" class="btn btn-gradient-danger btn-rounded"
                                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                    onmouseover="this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.transform='scale(1)';" data-bs-toggle="modal"
                                    data-bs-target="#modalAddUsers">Add New</button></li>
                        </ol>
                    </nav>
                </div>

                {{-- Modal add new sales --}}
                <div class="modal fade" id="modalAddUsers" tabindex="-1" aria-labelledby="modalLabelUsers"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalLabelUsers">Add New Users</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('user.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    <label for="name" class="form-label mt-2"><strong>Name</strong></label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter name...">
                                    <label for="email" class="form-label mt-2"><strong>Email</strong></label>
                                    <input type="text" name="email" placeholder="Enter email..." class="form-control" id="email">
                                    <label for="password" class="form-label mt-2"><strong>Password</strong></label>
                                    <input type="text" name="password" class="form-control" id="password" placeholder="Enter password...">
                                    <label for="email" class="form-label mt-2"><strong>Role</strong></label>
                                    <select name="role" id="" class="form-select">
                                        <option value="admin">Admin</option>
                                        <option value="staff">Staff</option>
                                    </select>
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
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $u)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $u->name}}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ $u->role }}</td>
                                    <td>
                                        <button class="btn btn-inverse-primary px-4" data-bs-toggle="modal"
                                            data-bs-target="#modalUpdateSales{{ $u->id }}">
                                            <i class="mdi mdi-cloud-sync"></i> Update
                                        </button>

                                        <!-- Modal Update Sales -->
                                        <div class="modal fade" id="modalUpdateSales{{ $u->id }}" tabindex="-1"
                                            aria-labelledby="modalLabelUsers" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="modalLabelUsers"> Update Data Users</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('user.update', $u->id) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <label for="name" class="form-label mt-2"><strong>Name</strong></label>
                                                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name..." value="{{ $u->name }}">
                                                            <label for="email" class="form-label mt-2"><strong>Email</strong></label>
                                                            <input type="text" name="email" placeholder="Enter email..." class="form-control" id="email" value="{{ $u->email }}">
                                                            <label for="password" class="form-label mt-2"><strong>Password</strong></label>
                                                            <input type="text" name="password" class="form-control" id="password" value="{{ $u->pw }}" readonly>
                                                            @if ($u->role !== 'superuser')
                                                                <label for="email" class="form-label mt-2"><strong>Role</strong></label>
                                                                <select name="role" id="" class="form-select" value="{{ $u->role }}">
                                                                    <option value="admin">Admin</option>
                                                                    <option value="staff">Staff</option>
                                                                </select>
                                                            @endif
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
                                        @if ($u->role !== 'superuser')
                                            <button class="btn btn-inverse-danger px-4" onclick="confirmDelete({{ $u->id }})">
                                                <i class="mdi mdi-delete-forever"></i> Delete
                                            </button>
                                            <form id="delete-form-{{ $u->id }}" action="{{ route('user.destroy', $u->id) }}" method="post" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
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
