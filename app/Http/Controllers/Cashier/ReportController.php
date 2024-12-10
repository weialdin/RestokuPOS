<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->input('report_type', 'daily'); // Default ke harian

        $data = [];

        // Laporan Harian
        if ($reportType === 'daily') {
            $data['dailySales'] = DB::table('transactions')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_purchase) as total_sales'))
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();
        }

        // Laporan Bulanan
        if ($reportType === 'monthly') {
            $data['monthlySales'] = DB::table('transactions')
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(total_purchase) as total_sales'))
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();
        }

        // Laporan Tahunan
        if ($reportType === 'yearly') {
            $data['yearlySales'] = DB::table('transactions')
                ->select(DB::raw('YEAR(created_at) as year'), DB::raw('SUM(total_purchase) as total_sales'))
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->get();
        }

        // Menu Terlaris
        if ($reportType === 'topItems') {
            $data['topItems'] = DB::table('transactions')
                ->select('menu_name', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('menu_name')
                ->orderBy('total_quantity', 'desc')
                ->take(10) // Batasi 10 menu terlaris
                ->get();
        }

        return view('reports.index', array_merge($data, [
            'reportType' => $reportType,
        ]));
    }

    public function exportExcel(Request $request)
{
    $reportType = $request->input('report_type', 'daily');

    // Ambil data laporan berdasarkan tipe
    $data = $this->fetchReportData($reportType);

    // Buat Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Tambahkan header berdasarkan tipe laporan
    if ($reportType === 'daily') {
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Total Penjualan');
    } elseif ($reportType === 'monthly') {
        $sheet->setCellValue('A1', 'Bulan');
        $sheet->setCellValue('B1', 'Total Penjualan');
    } elseif ($reportType === 'yearly') {
        $sheet->setCellValue('A1', 'Tahun');
        $sheet->setCellValue('B1', 'Total Penjualan');
    } elseif ($reportType === 'topItems') {
        $sheet->setCellValue('A1', 'Nama Menu');
        $sheet->setCellValue('B1', 'Total Pesanan');
    }

    // Tambahkan data ke dalam spreadsheet
    $row = 2;
    foreach ($data as $record) {
        $sheet->setCellValue('A' . $row, $record->report_date ?? $record->report_item);  // sesuaikan dengan nama kolom
        $sheet->setCellValue('B' . $row, number_format($record->total_sales ?? $record->total_quantity, 0, ',', '.')); // sesuaikan dengan nama kolom
        $row++;
    }

    // Buat file Excel
    $writer = new Xlsx($spreadsheet);
    $filename = 'laporan-' . $reportType . '.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $filename);
    $writer->save($temp_file);

    // Download file Excel
    return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
}

public function exportPdf(Request $request)
{
    $reportType = $request->input('report_type', 'daily');

    // Ambil data laporan berdasarkan tipe
    $data = $this->fetchReportData($reportType);

    // Render PDF menggunakan view
    $pdf = Pdf::loadView('reports.pdf', [
        'data' => $data,
        'reportType' => $reportType,
    ]);

    // Unduh file PDF
    return $pdf->download('laporan-' . $reportType . '.pdf');
}

private function fetchReportData($reportType)
{
    $columns = [
        'daily' => [DB::raw('DATE(created_at) as report_date'), DB::raw('SUM(total_purchase) as total_sales')],
        'monthly' => [DB::raw('DATE_FORMAT(created_at, "%Y-%m") as report_date'), DB::raw('SUM(total_purchase) as total_sales')],
        'yearly' => [DB::raw('YEAR(created_at) as report_date'), DB::raw('SUM(total_purchase) as total_sales')],
        'topItems' => ['menu_name as report_item', DB::raw('SUM(quantity) as total_quantity')],
    ];

    if (!isset($columns[$reportType])) {
        throw new \InvalidArgumentException("Tipe laporan tidak valid: {$reportType}");
    }

    $query = DB::table('transactions')
        ->select($columns[$reportType])
        ->orderBy($reportType === 'topItems' ? 'total_quantity' : 'report_date', 'desc');

    if ($reportType === 'topItems') {
        $query->groupBy('report_item')->take(10);
    } else {
        $query->groupBy('report_date');
    }

    return $query->get();
}


}