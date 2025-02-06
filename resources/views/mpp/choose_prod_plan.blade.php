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
                        Data Production Planning</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            {{-- <li><button type="button" class="btn btn-gradient-danger btn-rounded"
                                    style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                    onmouseover="this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.transform='scale(1)';" data-bs-toggle="modal"
                                    data-bs-target="#modalImportMpp">Add New</button></li>
                            <li> <a href="{{ route('mpp.format') }}">
                                    <button type="button" class="btn btn-gradient-info btn-rounded ms-2"
                                        style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform: scale(1);"
                                        onmouseover="this.style.transform='scale(1.05)';"
                                        onmouseout="this.style.transform='scale(1)';"><i class="mdi mdi-download"></i>
                                        Download Format</button></li></a> --}}
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
                            @forelse ($monthProdPlan as $index => $mp)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::create($mp->tahun, $mp->bulan)->format('F Y') }}
                                    </td>
                                    <td>
                                        <button class="btn btn-inverse-success px-4" data-bs-toggle="modal"
                                            data-bs-target="#modalUpdateMpp">
                                            <i class="mdi mdi-cloud-sync"></i> Update
                                        </button>
                                        <button class="btn btn-inverse-primary px-4" data-bs-toggle="modal"
                                            data-bs-target="#viewModal" data-year="{{ $mp->tahun }}"
                                            data-month="{{ $mp->bulan }}">
                                            <i class="mdi mdi-magnify"></i> View
                                        </button>
                                        <div class="modal fade" id="viewModal" tabindex="-1"
                                            aria-labelledby="viewModalMpp" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
                                                style="width: 100rem;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="viewModalMpp">Production Planning in :
                                                            <span id="modalMonthYear" class="text-danger"></span>
                                                        </h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table display" id="mppTable"
                                                                style="width: 100%">
                                                                <div class="d-flex mb-4">
                                                                    <h4 class="mt-2 me-2">Filter: </h4>
                                                                    <div class="dropdown" style="position: relative;">
                                                                        <input type="text"
                                                                            class="form-control dropdown-toggle"
                                                                            id="searchDropdown" data-bs-toggle="dropdown"
                                                                            aria-expanded="false"
                                                                            placeholder="Select Part Number" readonly
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
                                                                                    <span class="text-muted">All Part
                                                                                        Number</span>
                                                                                </li>
                                                                                @foreach ($uniquePartNumber as $upn)
                                                                                    <li class="list-group-item list-group-item-action"
                                                                                        style="cursor: pointer; padding: 0.5rem 1rem; transition: background-color 0.2s;"
                                                                                        data-value="{{ $upn }}">
                                                                                        {{ $upn }}
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                    @if (auth()->user()->role == 'staff' || auth()->user()->role == 'superuser')
                                                                        <form action="{{ route('mpp.delete') }}"
                                                                            method="DELETE" id="delMPP">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <button
                                                                                class="btn btn-danger ms-2 px-3 float-start"
                                                                                type="button"
                                                                                style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; transform:scale(1);"
                                                                                onmouseover="this.style.transform='scale(1.05)';"
                                                                                onmouseout="this.style.transform='scale(1)';"
                                                                                onclick="deleteConfirm('delMPP')"><i
                                                                                    class="mdi mdi-delete"></i></button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                                <thead>
                                                                    <tr>
                                                                        <th><input type="checkbox" name="select-checkbox"
                                                                                id="selectAll"></th>
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

                    const table = $('#mppTable').DataTable({
                        "lengthMenu": [10, 25, 50, 100],
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: `/mpp/order-original/load-data/${year}/${month}`,
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
                                data: 'prod_plan_bulan_1',
                                title: monthLabels[0]
                            },
                            {
                                data: 'prod_plan_bulan_2',
                                title: monthLabels[1]
                            },
                            {
                                data: 'prod_plan_bulan_3',
                                title: monthLabels[2]
                            },
                            {
                                data: 'prod_plan_bulan_4',
                                title: monthLabels[3]
                            },
                            {
                                data: 'prod_plan_bulan_5',
                                title: monthLabels[4]
                            },
                            {
                                data: 'prod_plan_bulan_6',
                                title: monthLabels[5]
                            },
                            {
                                data: 'prod_plan_bulan_7',
                                title: monthLabels[6]
                            },
                            {
                                data: 'prod_plan_bulan_8',
                                title: monthLabels[7]
                            },
                            {
                                data: 'prod_plan_bulan_9',
                                title: monthLabels[8]
                            },
                            {
                                data: 'prod_plan_bulan_10',
                                title: monthLabels[9]
                            },
                            {
                                data: 'prod_plan_bulan_11',
                                title: monthLabels[10]
                            },
                            {
                                data: 'prod_plan_bulan_12',
                                title: monthLabels[11]
                            }
                        ],
                        headerCallback: function(thead) {
                            $(thead).find('th').each(function(index) {
                                if (index > 5) {
                                    $(this).text(monthLabels[index - 5]);
                                }
                            });
                        }
                    });
                });

                $('#viewModal').on('hidden.bs.modal', function() {
                    if ($.fn.DataTable.isDataTable('#mppTable')) {
                        $('#mppTable').DataTable().destroy();
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const viewModal = document.getElementById('viewModal');
                const modalMonthYear = document.getElementById('modalMonthYear');

                viewModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const year = button.getAttribute('data-year');
                    const month = button.getAttribute('data-month');
                    const monthName = new Date(year, month - 1).toLocaleString('default', {
                        month: 'long'
                    });
                    modalMonthYear.textContent = `${monthName}, ${year}`;
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
                    searchDropdown.value = selectedValue === 'All' ? 'Select Part Number' : selectedValue;
                    if ($.fn.DataTable.isDataTable('#mppTable')) {
                        var table = $('#mppTable').DataTable();
                        table.column(4).search(selectedValue === 'All' ? '' : selectedValue).draw();
                    }
                    var dropdownMenu = listItem.closest('.dropdown-menu');
                    var dropdown = bootstrap.Dropdown.getInstance(searchDropdown);
                    dropdown.hide();
                }
            });

            function deleteConfirm(formId) {
                const selectedRows = [];
                const table = $('#mppTable').DataTable();

                table.$('input[type="checkbox"]:checked').each(function() {
                    const row = table.row($(this).closest('tr'));
                    const selectedID = row.data().kodefgs;
                    selectedRows.push(selectedID);
                });

                if (selectedRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silahkan pilih data yang akan dihapus!',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `${selectedRows.length} data yang dipilih akan dihapus!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('mpp.delete') }}",
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}",
                                kodefgs: selectedRows
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        $('#mppTable').DataTable().ajax.reload();
                                        $('#selectAllRows').prop('checked', false);
                                    });
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 400) {
                                    const response = JSON.parse(xhr.responseText);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Terjadi kesalahan saat menghapus data!'
                                    });
                                }
                            }
                        });
                    }
                });
            }

            $('#selectAll').on('change', function() {
                const isChecked = $(this).prop('checked');
                const table = $('#mppTable').DataTable();
                table.$('input[type="checkbox"]').prop('checked', isChecked);
            });
        </script>
    @endpush
@endsection
