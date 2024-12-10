@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- Sidebar Menu -->
            @include('management.import.sidebar')

            <!-- Main Content Area -->
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white text-center" style="background-color: #abc32f;">
                        <h5 class="mb-0">Statistik Jumlah Pembeli</h5>
                    </div>
                    <div class="card-body">
                        <!-- Form Pilihan Jenis Laporan -->
                        <form method="GET" action="/cashier/customer-report" class="mb-4">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label for="report_type" class="form-label">Jenis Laporan</label>
                                    <select name="report_type" id="report_type" class="form-control">
                                        <option value="daily" {{ request('report_type') == 'daily' ? 'selected' : '' }}>Harian</option>
                                        <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="yearly" {{ request('report_type') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                                </div>
                            </div>
                        </form>

                        <!-- Menampilkan Grafik Berdasarkan Pilihan -->
                        @if(request('report_type') == 'daily' && isset($dailyReport))
                            <h5 class="mt-4 text-center">Jumlah Pembeli Harian</h5>
                            <canvas id="dailyChart"></canvas>
                        @elseif(request('report_type') == 'monthly' && isset($monthlyReport))
                            <h5 class="mt-4 text-center">Jumlah Pembeli Bulanan</h5>
                            <canvas id="monthlyChart"></canvas>
                        @elseif(request('report_type') == 'yearly' && isset($yearlyReport))
                            <h5 class="mt-4 text-center">Jumlah Pembeli Tahunan</h5>
                            <canvas id="yearlyChart"></canvas>
                        @else
                            <p class="text-center mt-4">Pilih jenis laporan untuk melihat data.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if(request('report_type') == 'daily' && isset($dailyReport))
            const dailyLabels = [
                @foreach($dailyReport as $report)
                    "{{ $report->date }}",
                @endforeach
            ];
            const dailyData = [
                @foreach($dailyReport as $report)
                    {{ $report->total_customers }},
                @endforeach
            ];
            new Chart(document.getElementById('dailyChart'), {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: 'Jumlah Pembeli Harian',
                        data: dailyData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.3
                    }]
                },
                options: { responsive: true }
            });
        @elseif(request('report_type') == 'monthly' && isset($monthlyReport))
            const monthlyLabels = [
                @foreach($monthlyReport as $report)
                    "{{ $report->year }}-{{ str_pad($report->month, 2, '0', STR_PAD_LEFT) }}",
                @endforeach
            ];
            const monthlyData = [
                @foreach($monthlyReport as $report)
                    {{ $report->total_customers }},
                @endforeach
            ];
            new Chart(document.getElementById('monthlyChart'), {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Jumlah Pembeli Bulanan',
                        data: monthlyData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive: true }
            });
        @elseif(request('report_type') == 'yearly' && isset($yearlyReport))
            const yearlyLabels = [
                @foreach($yearlyReport as $report)
                    "{{ $report->year }}",
                @endforeach
            ];
            const yearlyData = [
                @foreach($yearlyReport as $report)
                    {{ $report->total_customers }},
                @endforeach
            ];
            new Chart(document.getElementById('yearlyChart'), {
                type: 'bar',
                data: {
                    labels: yearlyLabels,
                    datasets: [{
                        label: 'Jumlah Pembeli Tahunan',
                        data: yearlyData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive: true }
            });
        @endif
    </script>
@endsection
