<!DOCTYPE html>
<html>
<head>
    <title>Laporan {{ ucfirst($reportType) }}</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan {{ ucfirst($reportType) }}</h2>
    <table>
        <thead>
            <tr>
                <th>@if($reportType === 'daily') Tanggal @elseif($reportType === 'monthly') Bulan @elseif($reportType === 'yearly') Tahun @elseif($reportType === 'topItems') Nama Menu @endif</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $record)
                <tr>
                    <td>{{ $record->report_date ?? $record->report_item }}</td>
                    <td>Rp{{ number_format($record->total_sales ?? $record->total_quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
