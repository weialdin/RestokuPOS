@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <!-- Sidebar Menu -->
        @include('management.import.sidebar')

        <!-- Main Content Area -->
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #abc32f;">
                    <h5 class="mb-0"><i class="fa fa-chart-line me-2"></i>Laporan dan Analitik</h5>
                </div>

                <div class="card-body">
                    <!-- Form Pilihan Jenis Laporan -->
                    <form action="/reports" method="GET" class="mb-4">
                        <div class="row g-2">
                            <!-- Pilihan Jenis Laporan -->
                            <div class="col-md-4">
                                <label for="report_type" class="form-label">Jenis Laporan</label>
                                <select name="report_type" id="report_type" class="form-control">
                                    <option value="daily" {{ request('report_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                                    <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="yearly" {{ request('report_type') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                    <option value="topItems" {{ request('report_type') == 'topItems' ? 'selected' : '' }}>Menu Terlaris</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                            </div>
                        </div>
                    </form>

                    <!-- Jenis Laporan -->
                    @if($reportType === 'daily' && isset($dailySales))
                        <h5 class="mt-4">Penjualan Harian</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="dailySalesChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover table-bordered text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Total Penjualan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dailySales as $sale)
                                            <tr>
                                                <td>{{ $sale->date }}</td>
                                                <td>Rp{{ number_format($sale->total_sales, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif($reportType === 'monthly' && isset($monthlySales))
                        <h5 class="mt-4">Penjualan Bulanan</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="monthlySalesChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover table-bordered text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Bulan</th>
                                            <th>Total Penjualan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlySales as $sale)
                                            <tr>
                                                <td>{{ $sale->month }}</td>
                                                <td>Rp{{ number_format($sale->total_sales, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif($reportType === 'yearly' && isset($yearlySales))
                        <h5 class="mt-4">Penjualan Tahunan</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="yearlySalesChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover table-bordered text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tahun</th>
                                            <th>Total Penjualan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($yearlySales as $sale)
                                            <tr>
                                                <td>{{ $sale->year }}</td>
                                                <td>Rp{{ number_format($sale->total_sales, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif($reportType === 'topItems' && isset($topItems))
                        <h5 class="mt-4">Menu Terlaris</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="topItemsChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover table-bordered text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Menu</th>
                                            <th>Total Pesanan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topItems as $item)
                                            <tr>
                                                <td>{{ $item->menu_name }}</td>
                                                <td>{{ $item->total_quantity }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <hr>
                    <form action="reports" method="GET" class="mb-4 d-flex gap-2">
                        <input type="hidden" name="report_type" value="{{ request('report_type') }}">
                        <button formaction="/reports/export/excel" class="btn btn-success">Export Excel</button>
                        <button formaction="/reports/export/pdf" class="btn btn-danger">Export PDF</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($reportType === 'daily' && isset($dailySales))
        const dailyLabels = @json($dailySales->pluck('date'));
        const dailyData = @json($dailySales->pluck('total_sales'));
        new Chart(document.getElementById('dailySalesChart'), {
            type: 'line',
            data: { labels: dailyLabels, datasets: [{ label: 'Penjualan Harian', data: dailyData }] },
            options: { responsive: true },
        });
    @elseif($reportType === 'monthly' && isset($monthlySales))
        const monthlyLabels = @json($monthlySales->pluck('month'));
        const monthlyData = @json($monthlySales->pluck('total_sales'));
        new Chart(document.getElementById('monthlySalesChart'), {
            type: 'bar',
            data: { labels: monthlyLabels, datasets: [{ label: 'Penjualan Bulanan', data: monthlyData }] },
            options: { responsive: true },
        });
    @elseif($reportType === 'yearly' && isset($yearlySales))
        const yearlyLabels = @json($yearlySales->pluck('year'));
        const yearlyData = @json($yearlySales->pluck('total_sales'));
        new Chart(document.getElementById('yearlySalesChart'), {
            type: 'bar',
            data: { labels: yearlyLabels, datasets: [{ label: 'Penjualan Tahunan', data: yearlyData }] },
            options: { responsive: true },
        });
    @elseif($reportType === 'topItems' && isset($topItems))
        const topItemsLabels = @json($topItems->pluck('menu_name'));
        const topItemsData = @json($topItems->pluck('total_quantity'));
        new Chart(document.getElementById('topItemsChart'), {
            type: 'doughnut',
            data: { labels: topItemsLabels, datasets: [{ label: 'Menu Terlaris', data: topItemsData }] },
            options: { responsive: true },
        });
    @endif
</script>
@endsection
