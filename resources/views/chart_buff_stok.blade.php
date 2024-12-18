@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <a href="{{ route('buffer.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('buffer.index') ? 'purple' : 'blue' }}">Buffer |</a>
        <a href="{{ route('stok.index') }}"
            style="text-decoration: none; color: {{ request()->routeIs('stok.index') ? 'purple' : 'blue' }}"> Stock | </a>
        <a href="{{ route('buffer.stok.visualisasi') }}"
            style="text-decoration: none; color: {{ request()->routeIs('buffer.stok.visualisasi') ? 'purple' : 'blue' }}">
            Visualization</a>

        <div class="row mt-3">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-start">Summary Buffer By Month</h4>
                            <div id="sum-buffer-chart-legend"
                                class="rounded-legend legend-horizontal legend-top-right float-end"></div>
                        </div>
                        <canvas id="sum-buffer-chart" class="mt-4"></canvas>
                        <div class="mt-3 d-flex justify-content-between"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-start">Percentage Stock</h4>
                            <div id="percentage-stock-chart-legend"
                                class="rounded-legend legend-horizontal legend-top-right float-end"></div>
                        </div>
                        <canvas id="percentage-stock-chart" class="mt-4"
                            style="width: 100%; height: 85%; text-align:center"></canvas>
                        <div class="mt-3 d-flex justify-content-between">
                            {{-- <button id="prev-month" class="btn btn-sm btn-primary">Previous</button>
                            <button id="next-month" class="btn btn-sm btn-primary">Next</button> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Visualisasi --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('sum-buffer-chart').getContext('2d');
            const cth = document.getElementById('percentage-stock-chart').getContext('2d');

            // Buffer Chart
            const bufferData = @json($bufferData);
            const bufferLabels = bufferData.map(item => item.month);
            const bufferCounts = bufferData.map(item => item.count);

            const dataBuffers = {
                labels: bufferLabels,
                datasets: [{
                    label: 'Buffer Count',
                    data: bufferCounts,
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
            };

            const bufferChart = new Chart(ctx, {
                type: 'line',
                data: dataBuffers,
                options: {
                    responsive: true,
                }
            });

            // Bar chart percentage stock
            const percentageData = @json($percentageData);
            const allRanges = [
                '<25%',
                '50-25%',
                '75-50%',
                '100-75%',
                '100%',
                '>100%'
            ];

            const completeData = allRanges.map(range => {
                const found = percentageData.find(item => item.percentage_range === range);
                return found ? found : {
                    percentage_range: range,
                    quantity_percentage: 0
                };
            });

            const sortedData = completeData.sort((a, b) => {
                const getNumber = range => parseInt(range.match(/\d+/)[0]);
                return getNumber(a.percentage_range) - getNumber(b.percentage_range);
            });
            const percentageLabels = sortedData.map(item => item.percentage_range);
            const percentageQuantities = sortedData.map(item => item.quantity_percentage);

            const dataPercentages = {
                labels: percentageLabels,
                datasets: [{
                    label: 'Stock Percentage Distribution',
                    data: percentageQuantities,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            const percentageChart = new Chart(cth, {
                type: 'bar',
                data: dataPercentages,
                maintainAspectRatio: false,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Stock Percentage Distribution',
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            offset: 4,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
            percentageChart.canvas.parentNode.style.height = '100px';
        });
    </script>
@endsection
