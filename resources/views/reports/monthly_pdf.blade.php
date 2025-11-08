<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan {{ $monthName }} {{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #B7C4A4;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #3A3A3A;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f4f0;
            border-radius: 8px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 8px;
        }
        .summary-row.total {
            border-top: 2px solid #B7C4A4;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 10px;
        }
        .summary-label {
            font-weight: 600;
        }
        .amount-income {
            color: #4CAF50;
        }
        .amount-expense {
            color: #f44336;
        }
        .amount-profit {
            color: #B7C4A4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background-color: #B7C4A4;
            color: white;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f4f0;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p>Periode: {{ $monthName }} {{ $year }}</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
    </div>

    <div class="summary">
        <h3 style="margin-top: 0;">Ringkasan Keuangan</h3>
        <div class="summary-row">
            <span class="summary-label">Total Pemasukan:</span>
            <span class="amount-income">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Pengeluaran:</span>
            <span class="amount-expense">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row total">
            <span class="summary-label">Laba Bersih:</span>
            <span class="amount-profit">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 40%;">Deskripsi</th>
                <th style="width: 22.5%;" class="text-right">Pemasukan</th>
                <th style="width: 22.5%;" class="text-right">Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</td>
                    <td>{{ $report->description ?? '-' }}</td>
                    <td class="text-right">
                        @if($report->income > 0)
                            <span class="amount-income">Rp {{ number_format($report->income, 0, ',', '.') }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        @if($report->expenses > 0)
                            <span class="amount-expense">Rp {{ number_format($report->expenses, 0, ',', '.') }}</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data laporan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem SAVKA.CO</p>
    </div>
</body>
</html>
