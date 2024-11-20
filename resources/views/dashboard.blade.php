@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-home"></i>
                </span> Dashboard
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('purple-free/src/assets/images/dashboard/circle.svg') }}"
                            class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Weekly Sales <i
                                class="mdi mdi-chart-line mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-5">$ 15,0000</h2>
                        <h6 class="card-text">Increased by 60%</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('purple-free/src/assets/images/dashboard/circle.svg') }}"
                            class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Weekly Orders <i
                                class="mdi mdi-bookmark-outline mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-5">45,6334</h2>
                        <h6 class="card-text">Decreased by 10%</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('purple-free/src/assets/images/dashboard/circle.svg') }}"
                            class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Visitors Online <i
                                class="mdi mdi-diamond mdi-24px float-end"></i>
                        </h4>
                        <h2 class="mb-5">95,5741</h2>
                        <h6 class="card-text">Increased by 5%</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="display: flex; align-items: center;">
            <div class="col-md-6 stretch-card grid-margin">
                <h3 class="page-title" style="display: flex; align-items: center;">
                    <span class="page-title-icon bg-gradient-primary text-white me-2" style="margin-right: 10px;">
                        <i class="mdi mdi-calendar-clock"></i>
                    </span>
                    Filter Date,
                    <label for="startDate" style="margin-right: 10px; margin-left: 10px; ">From:</label>
                    <input class="form-control bg-gradient-light" type="date" name="startDate" id="startDate"
                        style="margin-right: 10px; width: auto; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                    <label for="endDate" style="margin-right: 10px;">Until:</label>
                    <input class="form-control bg-gradient-light" type="date" name="endDate" id="endDate"
                        style="width: auto; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                </h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-start">Percentage Stock</h4>
                            <div id="percentage-stok-chart-legend"
                                class="rounded-legend legend-horizontal legend-top-right float-end"></div>
                        </div>
                        <canvas id="percentage-stok-chart" class="mt-4"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="card-body">
                        <h4 class="card-title" data-bs-toggle="collapse" href="#collapseLT" role="button"
                            aria-expanded="false" aria-controls="collapseLT">Remark Lead Time PO</h4>
                        <div class="collapse" id="collapseLT">
                            <div class="card" style="width: 50%;">
                                <table class="table table-hover" width="50%" cellspacing="0">
                                    <tr>
                                        <th style="width: 20%" class="text-danger">PO PREV MONTH</th>
                                        <th>LEAD TIME</th>
                                        <th>N LEAD TIME</th>
                                    </tr>
                                    <tbody>
                                        {{-- <tr>
                                            <td style="font-weight: bold; background-color: rgba(54, 162, 235, 0.2);">Qty
                                            </td>
                                            <td style="background-color: rgba(54, 162, 235, 0.2);">
                                                {{ $poData['qty_lt_prev'] }}</td>
                                            <td style="background-color: rgba(54, 162, 235, 0.2);">
                                                {{ $poData['qty_nlt_prev'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; background-color: rgba(255, 159, 64, 0.2);">Amount
                                            </td>
                                            <td style="background-color: rgba(255, 159, 64, 0.2);">
                                                {{ $poData['amount_lt_prev'] }}</td>
                                            <td style="background-color: rgba(255, 159, 64, 0.2);">
                                                {{ $poData['amount_nlt_prev'] }}</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                                <table class="table table-hover" width="50%" cellspacing="0">
                                    <tr>
                                        <th style="width: 20%;" class="text-success">PO CURRENT MONTH</th>
                                        <th>LEAD TIME</th>
                                        <th>N LEAD TIME</th>
                                    </tr>
                                    <tbody>
                                        {{-- <tr>
                                            <td style="font-weight: bold; background-color: rgba(54, 162, 235, 0.2);">Qty
                                            </td>
                                            <td style="background-color: rgba(54, 162, 235, 0.2);">
                                                {{ $poData['qty_lt_now'] }}</td>
                                            <td style="background-color: rgba(54, 162, 235, 0.2);">
                                                {{ $poData['qty_nlt_now'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; background-color: rgba(255, 159, 64, 0.2);">
                                                Amount</td>
                                            <td style="background-color: rgba(255, 159, 64, 0.2);">
                                                {{ $poData['amount_lt_now'] }}</td>
                                            <td style="background-color: rgba(255, 159, 64, 0.2);">
                                                {{ $poData['amount_nlt_now'] }}</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class=" d-flex justify-content-center">
                            <canvas id="percentage-po-chart"></canvas>
                        </div>
                        <div id="percentage-po-chart-legend"
                            class="rounded-legend legend-vertical legend-bottom-left pt-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="display: flex; align-items: center;">
            <div class="col-md-6 stretch-card grid-margin">
                <h3 class="page-title" style="display: flex; align-items: center;">
                    <span class="page-title-icon bg-gradient-primary text-white me-2" style="margin-right: 10px;">
                        <i class="mdi mdi-calendar-clock"></i>
                    </span>
                    Production Planning
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="card-body">
                        <div class="clearfix">
                            {{-- <h4 class="card-title float-start">Local</h4> --}}
                            <div id="production-planning-chart-legend"
                                class="rounded-legend legend-horizontal legend-top-right float-end"></div>
                        </div>
                        <canvas id="production-planning-chart" class="mt-4"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Recent Tickets</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> Assignee </th>
                                        <th> Subject </th>
                                        <th> Status </th>
                                        <th> Last Update </th>
                                        <th> Tracking ID </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="{{ asset('purple-free/src/assets/images/faces/face1.jpg') }}"
                                                class="me-2" alt="image"> David Grey
                                        </td>
                                        <td> Fund is not recieved </td>
                                        <td>
                                            <label class="badge badge-gradient-success">DONE</label>
                                        </td>
                                        <td> Dec 5, 2017 </td>
                                        <td> WD-12345 </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="{{ asset('purple-free/src/assets/images/faces/face2.jpg') }}"
                                                class="me-2" alt="image"> Stella Johnson
                                        </td>
                                        <td> High loading time </td>
                                        <td>
                                            <label class="badge badge-gradient-warning">PROGRESS</label>
                                        </td>
                                        <td> Dec 12, 2017 </td>
                                        <td> WD-12346 </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="{{ asset('purple-free/src/assets/images/faces/face3.jpg') }}"
                                                class="me-2" alt="image"> Marina Michel
                                        </td>
                                        <td> Website down for one week </td>
                                        <td>
                                            <label class="badge badge-gradient-info">ON HOLD</label>
                                        </td>
                                        <td> Dec 16, 2017 </td>
                                        <td> WD-12347 </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="{{ asset('purple-free/src/assets/images/faces/face4.jpg') }}"
                                                class="me-2" alt="image"> John Doe
                                        </td>
                                        <td> Loosing control on server </td>
                                        <td>
                                            <label class="badge badge-gradient-danger">REJECTED</label>
                                        </td>
                                        <td> Dec 3, 2017 </td>
                                        <td> WD-12348 </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-0 d-flex">
                        <div id="inline-datepicker" class="datepicker datepicker-custom"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Recent Updates</h4>
                        <div class="d-flex">
                            <div class="d-flex align-items-center me-4 text-muted font-weight-light">
                                <i class="mdi mdi-account-outline icon-sm me-2"></i>
                                <span>jack Menqu</span>
                            </div>
                            <div class="d-flex align-items-center text-muted font-weight-light">
                                <i class="mdi mdi-clock icon-sm me-2"></i>
                                <span>October 3rd, 2018</span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6 pe-1">
                                <img src="{{ asset('purple-free/src/assets/images/dashboard/img_1.jpg') }}"
                                    class="mb-2 mw-100 w-100 rounded" alt="image">
                                <img src="{{ asset('purple-free/src/assets/images/dashboard/img_4.jpg') }}"
                                    class="mw-100 w-100 rounded" alt="image">
                            </div>
                            <div class="col-6 ps-1">
                                <img src="{{ asset('purple-free/src/assets/images/dashboard/img_2.jpg') }}"
                                    class="mb-2 mw-100 w-100 rounded" alt="image">
                                <img src="{{ asset('purple-free/src/assets/images/dashboard/img_3.jpg') }}"
                                    class="mw-100 w-100 rounded" alt="image">
                            </div>
                        </div>
                        <div class="d-flex mt-5 align-items-top">
                            <img src="{{ asset('purple-free/src/assets/images/faces/face3.jpg') }}"
                                class="img-sm rounded-circle me-3" alt="image">
                            <div class="mb-0 flex-grow">
                                <h5 class="me-2 mb-2">School Website - Authentication Module.</h5>
                                <p class="mb-0 font-weight-light">It is a long established fact that a
                                    reader will be distracted by the readable content of a page.</p>
                            </div>
                            <div class="ms-auto">
                                <i class="mdi mdi-heart-outline text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Persentase Stock</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> # </th>
                                        <th> Persentase </th>
                                        <th> Count Persentase Stock </th>
                                        <th> Progress </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> 1 </td>
                                        <td> >100 % </td>
                                        <td> May 15, 2015 </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-success" role="progressbar"
                                                    style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> 2 </td>
                                        <td> 100% </td>
                                        <td> Jul 01, 2015 </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar"
                                                    style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> 3 </td>
                                        <td> 100 - 75% </td>
                                        <td> Apr 12, 2015 </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-warning" role="progressbar"
                                                    style="width: 90%" aria-valuenow="90" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> 4 </td>
                                        <td> 75 - 50% </td>
                                        <td> May 15, 2015 </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-primary" role="progressbar"
                                                    style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> 5 </td>
                                        <td> 50 - 25% </td>
                                        <td> May 03, 2015 </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar"
                                                    style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> 5 </td>
                                        <td>
                                            < 25% </td>
                                        <td> Jun 05, 2015 </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-info" role="progressbar"
                                                    style="width: 65%" aria-valuenow="65" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-dark">Todo List</h4>
                        <div class="add-items d-flex">
                            <input type="text" class="form-control todo-list-input"
                                placeholder="What do you need to do today?">
                            <button class="add btn btn-gradient-primary font-weight-bold todo-list-add-btn"
                                id="add-task">Add</button>
                        </div>
                        <div class="list-wrapper">
                            <ul class="d-flex flex-column-reverse todo-list todo-list-custom">
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox"> Meeting with Alisa
                                        </label>
                                    </div>
                                    <i class="remove mdi mdi-close-circle-outline"></i>
                                </li>
                                <li class="completed">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox" checked> Call John
                                        </label>
                                    </div>
                                    <i class="remove mdi mdi-close-circle-outline"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox"> Create invoice
                                        </label>
                                    </div>
                                    <i class="remove mdi mdi-close-circle-outline"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox"> Print Statements
                                        </label>
                                    </div>
                                    <i class="remove mdi mdi-close-circle-outline"></i>
                                </li>
                                <li class="completed">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox" checked> Prepare for
                                            presentation </label>
                                    </div>
                                    <i class="remove mdi mdi-close-circle-outline"></i>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox"> Pick up kids from
                                            school </label>
                                    </div>
                                    <i class="remove mdi mdi-close-circle-outline"></i>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scriptDashboard')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const startDateInput = document.getElementById('startDate');
                const endDateInput = document.getElementById('endDate');
                let stockChart = null;
                let poChart = null;

                const chartColors = {
                    backgrounds: [
                        'rgba(255, 99, 132, 0.2)', // Merah muda
                        'rgba(54, 162, 235, 0.2)', // Biru
                        'rgba(255, 206, 86, 0.2)', // Kuning
                        'rgba(75, 192, 192, 0.2)', // Hijau
                        'rgba(153, 102, 255, 0.2)', // Ungu
                        'rgba(255, 159, 64, 0.2)' // Oranye
                    ],
                    borders: [
                        'rgba(255, 99, 132, 1)', // Merah muda
                        'rgba(54, 162, 235, 1)', // Biru
                        'rgba(255, 206, 86, 1)', // Kuning
                        'rgba(75, 192, 192, 1)', // Hijau
                        'rgba(153, 102, 255, 1)', // Ungu
                        'rgba(255, 159, 64, 1)' // Oranye
                    ]
                };

                function initializeStockChart(data) {
                    const canvas = document.getElementById('percentage-stok-chart');
                    if (!canvas) {
                        console.error('Tidak dapat menemukan canvas untuk grafik stok');
                        return;
                    }

                    const config = {
                        type: 'doughnut',
                        data: {
                            labels: [
                                "> 100%",
                                "100%",
                                "75-100%",
                                "50-75%",
                                "25-50%",
                                "< 25%"
                            ],
                            datasets: [{
                                label: 'Jumlah Stok',
                                data: [
                                    data.upSeratus,
                                    data.seratus,
                                    data.tujulima,
                                    data.limapuluh,
                                    data.dualima,
                                    data.nol
                                ],
                                backgroundColor: chartColors.backgrounds,
                                borderColor: chartColors.borders,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Persentase Jumlah Stok'
                                }
                            }
                        }
                    };

                    stockChart = new Chart(canvas, config);
                }

                function initializePoChart(data) {
                    const canvas = document.getElementById('percentage-po-chart');
                    if (!canvas) {
                        console.error('Tidak dapat menemukan canvas untuk grafik PO');
                        return;
                    }

                    const currentDate = new Date();
                    const currentMonth = currentDate.toLocaleString('id-ID', {
                        month: 'short'
                    });
                    const prevMonth = new Date(currentDate.setMonth(currentDate.getMonth() - 1))
                        .toLocaleString('id-ID', {
                            month: 'short'
                        });

                    const config = {
                        type: 'line',
                        data: {
                            labels: [
                                [`${prevMonth}`, `LEAD TIME`],
                                [`${currentMonth}`, `LEAD TIME`],
                                [`${prevMonth}`, `NON LEAD TIME`],
                                [`${currentMonth}`, `NON LEAD TIME`]
                            ],
                            datasets: [{
                                    label: 'Jumlah PO',
                                    data: [
                                        data.qty_lt_now,
                                        data.qty_lt_prev,
                                        data.qty_nlt_now,
                                        data.qty_nlt_prev
                                    ],
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Total Nilai PO',
                                    data: [
                                        data.amount_lt_now,
                                        data.amount_lt_prev,
                                        data.amount_nlt_now,
                                        data.amount_nlt_prev
                                    ],
                                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                                    borderColor: 'rgba(255, 159, 64, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    grid: {
                                        display: true
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Analisis Lead Time PO'
                                }
                            }
                        }
                    };

                    poChart = new Chart(canvas, config);
                }

                function initializePlanProdChart(data) {
                    const canvas = document.getElementById('production-planning-chart');
                    if (!canvas) {
                        console.error('Tidak dapat menemukan canvas untuk grafik PO');
                        return;
                    }

                    const currentDate = new Date();
                    const months = Array.from({
                        length: 12
                    }, (_, i) => {
                        const date = new Date(currentDate.getFullYear(), currentDate.getMonth() + i, 1);
                        return date.toLocaleString('default', {
                            month: 'short',
                            year: 'numeric'
                        });
                    });

                    const config = {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                    label: 'Last Month',
                                    data: [
                                        data.bulan_1,
                                        data.bulan_2,
                                        data.bulan_3,
                                        data.bulan_4,
                                        data.bulan_5,
                                        data.bulan_6,
                                        data.bulan_7,
                                        data.bulan_8,
                                        data.bulan_9,
                                        data.bulan_10,
                                        data.bulan_11,
                                        data.bulan_12
                                    ],
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Current Month',
                                    data: [
                                        data.bulan_1,
                                        data.bulan_2,
                                        data.bulan_3,
                                        data.bulan_4,
                                        data.bulan_5,
                                        data.bulan_6,
                                        data.bulan_7,
                                        data.bulan_8,
                                        data.bulan_9,
                                        data.bulan_10,
                                        data.bulan_11,
                                    ],
                                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                                    borderColor: 'rgba(255, 159, 64, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    grid: {
                                        display: true
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            weight: 'bold'
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Analisis Lead Time PO'
                                }
                            }
                        }
                    };

                    poChart = new Chart(canvas, config);
                }

                function updateStockChart(newData) {
                    if (!stockChart) return;

                    stockChart.data.datasets[0].data = [
                        newData.upSeratus,
                        newData.seratus,
                        newData.tujulima,
                        newData.limapuluh,
                        newData.dualima,
                        newData.nol
                    ];
                    stockChart.update();
                }

                function updatePoChart(newData) {
                    if (!poChart) return;

                    poChart.data.datasets[0].data = [
                        newData.qty_lt_now,
                        newData.qty_nlt_now,
                        newData.qty_lt_prev,
                        newData.qty_nlt_prev
                    ];
                    poChart.data.datasets[1].data = [
                        newData.amount_lt_now,
                        newData.amount_nlt_now,
                        newData.amount_lt_prev,
                        newData.amount_nlt_prev
                    ];
                    poChart.update();
                }

                // ========= Data Fetching Function =========
                async function updateCharts() {
                    const startDate = startDateInput.value;
                    const endDate = endDateInput.value;

                    if (!startDate || !endDate) return;

                    try {
                        const response = await fetch(`/dashboard/data?startDate=${startDate}&endDate=${endDate}`);
                        if (!response.ok) throw new Error('Gagal mengambil data');

                        const data = await response.json();

                        // Update kedua grafik dengan data baru
                        updateStockChart({
                            upSeratus: data.upSeratus,
                            seratus: data.seratus,
                            tujulima: data.tujulima,
                            limapuluh: data.limapuluh,
                            dualima: data.dualima,
                            nol: data.nol
                        });

                        updatePoChart({
                            qty_lt_now: data.qty_lt_now,
                            amount_lt_now: data.amount_lt_now,
                            qty_lt_prev: data.qty_lt_prev,
                            amount_lt_prev: data.amount_lt_prev,
                            qty_nlt_now: data.qty_nlt_now,
                            amount_nlt_now: data.amount_nlt_now,
                            qty_nlt_prev: data.qty_nlt_prev,
                            amount_nlt_prev: data.amount_nlt_prev
                        });
                    } catch (error) {
                        console.error('Error saat mengambil data:', error);
                        alert('Terjadi kesalahan saat mengambil data. Silakan coba lagi.');
                    }
                }

                startDateInput.addEventListener('change', updateCharts);
                endDateInput.addEventListener('change', updateCharts);

                const initialStockData = {
                    upSeratus: parseInt("{{ $upSeratus }}"),
                    seratus: parseInt("{{ $seratus }}"),
                    tujulima: parseInt("{{ $tujulima }}"),
                    limapuluh: parseInt("{{ $limapuluh }}"),
                    dualima: parseInt("{{ $dualima }}"),
                    nol: parseInt("{{ $nol }}")
                };

                const initialPoData = {
                    qty_lt_now: parseInt("{{ $qty_lt_now }}"),
                    amount_lt_now: parseFloat("{{ $amount_lt_now }}"),
                    qty_lt_prev: parseInt("{{ $qty_lt_prev }}"),
                    amount_lt_prev: parseFloat("{{ $amount_lt_prev }}"),
                    qty_nlt_now: parseInt("{{ $qty_nlt_now }}"),
                    amount_nlt_now: parseFloat("{{ $amount_nlt_now }}"),
                    qty_nlt_prev: parseInt("{{ $qty_nlt_prev }}"),
                    amount_nlt_prev: parseFloat("{{ $amount_nlt_prev }}")
                };

                const initialPlanProdData = {
                    bulan_1: ("{{ $bulan_1 }}"),
                    bulan_2: ("{{ $bulan_2 }}"),
                    bulan_3: ("{{ $bulan_3 }}"),
                    bulan_4: ("{{ $bulan_4 }}"),
                    bulan_5: ("{{ $bulan_5 }}"),
                    bulan_6: ("{{ $bulan_6 }}"),
                    bulan_7: ("{{ $bulan_7 }}"),
                    bulan_8: ("{{ $bulan_8 }}"),
                    bulan_9: ("{{ $bulan_9 }}"),
                    bulan_10: ("{{ $bulan_10 }}"),
                    bulan_11: ("{{ $bulan_11 }}"),
                    bulan_12: ("{{ $bulan_12 }}"),
                }

                initializeStockChart(initialStockData);
                initializePoChart(initialPoData);
                initializePlanProdChart(initialPlanProdData);
            });
        </script>
    @endpush
@endsection
