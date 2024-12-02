@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <a href="{{ route('buffer.index') }}" style="text-decoration: none; color: {{ request()->routeIs('buffer.index') ? 'purple' : 'blue' }}">Buffer |</a>
        <a href="{{ route('stok.index') }}" style="text-decoration: none; color: {{ request()->routeIs('stok.index') ? 'purple' : 'blue' }}"> Stock | </a>
        <a href="{{ route('buffer.stok.visualisasi') }}" style="text-decoration: none; color: {{ request()->routeIs('buffer.stok.visualisasi') ? 'purple' : 'blue' }}"> Visualization</a>

        <div class="row mt-3">
            <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-start">Summary Buffer Every Month</h4>
                            <div id="sum-buffer-chart-legend"
                                class="rounded-legend legend-horizontal legend-top-right float-end"></div>
                        </div>
                        <canvas id="sum-buffer-chart" class="mt-4"></canvas>
                        <div class="mt-3 d-flex justify-content-between">
                            {{-- <button id="prev-month" class="btn btn-sm btn-primary">Previous</button>
                            <button id="next-month" class="btn btn-sm btn-primary">Next</button> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Traffic Sources</h4>
                        <div class="doughnutjs-wrapper d-flex justify-content-center">
                            <canvas id="traffic-chart"></canvas>
                        </div>
                        <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Visualisasi Buffer --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('sum-buffer-chart').getContext('2d');
            let currentMonth = new Date().getMonth() + 1;
            const currentDate = new Date();
            const labels = Array.from({
                length: 6
            }, (_, i) => {
                const date = new Date(currentDate.getFullYear(), currentMonth - (6 - i), 1);
                return date.toLocaleString('default', {
                    month: 'short',
                    year: 'numeric'
                });
            });
            const bufferData = @json($bufferData);
            const rawBufferData = bufferData.reduce((acc, item) => {
                const month = item.month;
                acc[month] = item.count, 12;
                console.log(acc[month]);
                return acc;
            }, new Array(12).fill(0)).slice(-6);

            const dataBuffers = {
                labels: labels,
                datasets: [{
                    label: 'Buffer Count',
                    data: rawBufferData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)'
                    ],
                    borderWidth: 1
                }]
            }

            const chart = new Chart(ctx, {
                type: 'bar',
                data: dataBuffers,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            //     const chart = new Chart(ctx, {
            //         type: 'bar',
            //         data: {
            //             labels: [labels[currentMonth]],
            //             datasets: [{
            //                 label: 'Buffer Count',
            //                 data: [dataBuffer[currentMonth]],
            //                 backgroundColor: 'rgba(75, 192, 192, 0.2)',
            //                 borderColor: 'rgba(75, 192, 192, 1)',
            //                 borderWidth: 1
            //             }]
            //         },
            //         options: {
            //             responsive: true,
            //             plugins: {
            //                 legend: {
            //                     position: 'top',
            //                 },
            //                 tooltip: {
            //                     enabled: true
            //                 }
            //             },
            //             scales: {
            //                 y: {
            //                     beginAtZero: true
            //                 }
            //             }
            //         }
            //     });

            //     // Update chart on button click
            //     document.getElementById('prev-month').addEventListener('click', function() {
            //         currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
            //         updateChart();
            //     });

            //     document.getElementById('next-month').addEventListener('click', function() {
            //         currentMonth = (currentMonth === 11) ? 0 : currentMonth + 1;
            //         updateChart();
            //     });

            //     function updateChart() {
            //         chart.data.labels = [labels[currentMonth]];
            //         chart.data.datasets[0].data = [dataBuffer[currentMonth]];
            //         chart.update();
            //     }
        });
    </script>
@endsection
