<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Menampilkan semua lapora
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            return response()->json(['message' => 'Hanya owner yang dapat melihat laporan.'], 403);
        }

        $query = Report::query();

        // Filter optional berdasarkan bulan dan tahun
        if ($request->has('month') && $request->has('year')) {
            $query->whereMonth('report_date', $request->month)
                  ->whereYear('report_date', $request->year);
        }

        $reports = $query->orderBy('report_date', 'desc')->get();

        return response()->json([
            'message' => 'Daftar laporan berhasil diambil.',
            'data' => $reports
        ]);
    }

    public function getReports(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya owner yang dapat melihat laporan.'
            ], 403);
        }

        try {
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));

            $reports = Report::whereMonth('report_date', $month)
                            ->whereYear('report_date', $year)
                            ->orderBy('report_date', 'desc')
                            ->get();

            $totalIncome = $reports->sum('income');
            $totalExpense = $reports->sum('expenses');
            $netProfit = $totalIncome - $totalExpense;

            return response()->json([
                'success' => true,
                'data' => $reports,
                'summary' => [
                    'total_income' => $totalIncome,
                    'total_expense' => $totalExpense,
                    'net_profit' => $netProfit
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data laporan.'
            ], 500);
        }
    }

    /**
     * Unduh laporan bulanan dalam format CSV
     */

    public function downloadMonthlyReportCSV($month, $year)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya owner yang dapat mengunduh laporan.'
            ], 403);
        }

        try {
            $reports = Report::whereMonth('report_date', $month)
                            ->whereYear('report_date', $year)
                            ->orderBy('report_date', 'asc')
                            ->get(['report_date', 'description', 'income', 'expenses']);

            if ($reports->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada laporan untuk bulan dan tahun tersebut.'
                ], 404);
            }

            $fileName = "Laporan_Keuangan_{$month}_{$year}.csv";

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$fileName\"",
            ];

            $callback = function() use ($reports) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

                fputcsv($file, ['Tanggal', 'Deskripsi', 'Pemasukan', 'Pengeluaran']);

                foreach ($reports as $report) {
                    fputcsv($file, [
                        Carbon::parse($report->report_date)->format('d/m/Y'),
                        $report->description,
                        $report->income,
                        $report->expenses
                    ]);
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunduh laporan.'
            ], 500);
        }
    }

    /**
     * Unduh laporan bulanan dalam format PDF
     */
    public function downloadMonthlyReportPDF($month, $year)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya owner yang dapat mengunduh laporan.'
            ], 403);
        }

        try {
            $reports = Report::whereMonth('report_date', $month)
                            ->whereYear('report_date', $year)
                            ->orderBy('report_date', 'asc')
                            ->get();

            if ($reports->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada laporan untuk bulan dan tahun tersebut.'
                ], 404);
            }

            $totalIncome = $reports->sum('income');
            $totalExpenses = $reports->sum('expenses');
            $netProfit = $totalIncome - $totalExpenses;

            $monthName = Carbon::create($year, $month, 1)->locale('id')->translatedFormat('F');
            $year = $year;

            $pdf = Pdf::loadView('reports.monthly_pdf', compact(
                'reports', 'monthName', 'year', 'totalIncome', 'totalExpenses', 'netProfit'
            ));

            return $pdf->download("Laporan_Keuangan_{$monthName}_{$year}.pdf");

        } catch (\Exception $e) {
            Log::error('PDF Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunduh laporan PDF.'
            ], 500);
        }
    }
}
