<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

    /**
     * Unduh laporan bulanan dalam format CSV
     */
    public function downloadMonthlyReportCSV($month, $year)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            return response()->json(['message' => 'Hanya owner yang dapat mengunduh laporan.'], 403);
        }

        $reports = Report::whereMonth('report_date', $month)
                         ->whereYear('report_date', $year)
                         ->orderBy('report_date', 'asc')
                         ->get(['report_date', 'description', 'income', 'expenses']);

        if ($reports->isEmpty()) {
            return response()->json(['message' => 'Tidak ada laporan untuk bulan dan tahun tersebut.'], 404);
        }

        // Buat CSV
        $csvHeader = ['Report Date', 'Description', 'Income', 'Expenses'];
        $csvData = $reports->map(function ($report) {
            return [
                Carbon::parse($report->report_date)->format('Y-m-d'),
                $report->description,
                $report->income,
                $report->expenses
            ];
        });

        $fileName = "Monthly_Report_{$month}_{$year}.csv";
        $handle = fopen($fileName, 'w');
        fputcsv($handle, $csvHeader);

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return Response::download($fileName)->deleteFileAfterSend(true);
    }

    /**
     * Unduh laporan bulanan dalam format PDF
     */
    public function downloadMonthlyReportPDF($month, $year)
    {
        $user = Auth::user();

        if ($user->role !== 'Owner') {
            return response()->json(['message' => 'Hanya owner yang dapat mengunduh laporan.'], 403);
        }

        $reports = Report::whereMonth('report_date', $month)
                         ->whereYear('report_date', $year)
                         ->orderBy('report_date', 'asc')
                         ->get(['report_date', 'description', 'income', 'expenses']);

        if ($reports->isEmpty()) {
            return response()->json(['message' => 'Tidak ada laporan untuk bulan dan tahun tersebut.'], 404);
        }

        $totalIncome = $reports->sum('income');
        $totalExpenses = $reports->sum('expenses');
        $monthName = Carbon::create()->month($month)->translatedFormat('F');

        $pdf = Pdf::loadView('reports.monthly_pdf', [
            'reports' => $reports,
            'month' => $monthName,
            'year' => $year,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
        ]);

        return $pdf->download("Monthly_Report_{$month}_{$year}.pdf");
    }
}
