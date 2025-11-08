{{-- resources/views/owner/report.blade.php --}}
@extends('layouts.owner')

@section('title', 'Laporan Keuangan')
@section('header', 'Laporan Keuangan')

@section('styles')
<style>
    .filter-section {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }

    .filter-row {
        display: flex;
        gap: 16px;
        align-items: end;
        flex-wrap: wrap;
    }

    .form-group {
        flex: 1;
        min-width: 180px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--charcoal-gray);
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--mocha-cream);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--warm-white);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--sage-green);
        box-shadow: 0 0 0 3px rgba(183, 196, 164, 0.1);
    }

    .btn-group {
        display: flex;
        gap: 12px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--sage-green);
        color: var(--charcoal-gray);
    }

    .btn-primary:hover {
        background: #a3b393;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(183, 196, 164, 0.3);
    }

    .btn-success {
        background: #4CAF50;
        color: white;
    }

    .btn-success:hover {
        background: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .btn-danger {
        background: #f44336;
        color: white;
    }

    .btn-danger:hover {
        background: #da190b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
    }

    .report-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .summary-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .summary-card h3 {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .summary-card .amount {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .summary-card.income .amount {
        color: #4CAF50;
    }

    .summary-card.expense .amount {
        color: #f44336;
    }

    .summary-card.profit .amount {
        color: var(--sage-green);
    }

    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-header {
        padding: 20px 24px;
        border-bottom: 2px solid var(--warm-white);
    }

    .table-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--charcoal-gray);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: var(--warm-white);
    }

    th {
        padding: 16px 24px;
        text-align: left;
        font-weight: 600;
        color: var(--charcoal-gray);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 16px 24px;
        border-bottom: 1px solid var(--warm-white);
    }

    tbody tr:hover {
        background: var(--warm-white);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    .loading {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-danger {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ef9a9a;
    }

    .amount-income {
        color: #4CAF50;
        font-weight: 600;
    }

    .amount-expense {
        color: #f44336;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="filter-section">
    <div class="filter-row">
        <div class="form-group">
            <label for="month">Bulan</label>
            <select id="month" class="form-control">
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>

        <div class="form-group">
            <label for="year">Tahun</label>
            <select id="year" class="form-control">
                <!-- Akan diisi dengan JavaScript -->
            </select>
        </div>

        <div class="btn-group">
            <button class="btn btn-primary" onclick="loadReports()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Tampilkan
            </button>
            <button class="btn btn-success" onclick="downloadCSV()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                CSV
            </button>
            <button class="btn btn-danger" onclick="downloadPDF()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                PDF
            </button>
        </div>
    </div>
</div>

<div id="errorAlert" class="alert alert-danger" style="display: none;">
    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span id="errorMessage"></span>
</div>

<div class="report-summary">
    <div class="summary-card income">
        <h3>Total Pemasukan</h3>
        <div class="amount" id="totalIncome">Rp 0</div>
    </div>
    <div class="summary-card expense">
        <h3>Total Pengeluaran</h3>
        <div class="amount" id="totalExpense">Rp 0</div>
    </div>
    <div class="summary-card profit">
        <h3>Laba Bersih</h3>
        <div class="amount" id="netProfit">Rp 0</div>
    </div>
</div>

<div class="table-container">
    <div class="table-header">
        <h2>Detail Laporan</h2>
    </div>
    <div id="tableContent">
        <div class="loading">Memuat data...</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Set default bulan dan tahun ke saat ini
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth() + 1;
    const currentYear = currentDate.getFullYear();

    // Isi dropdown tahun (5 tahun terakhir)
    const yearSelect = document.getElementById('year');
    for (let year = currentYear; year >= currentYear - 5; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    // Set default values
    document.getElementById('month').value = currentMonth;
    document.getElementById('year').value = currentYear;

    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    // Format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }

    // Show error
    function showError(message) {
        const errorAlert = document.getElementById('errorAlert');
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = message;
        errorAlert.style.display = 'flex';
        setTimeout(() => {
            errorAlert.style.display = 'none';
        }, 5000);
    }

    // Load reports
    async function loadReports() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;
        const tableContent = document.getElementById('tableContent');

        tableContent.innerHTML = '<div class="loading">Memuat data...</div>';

        try {
            const response = await fetch(`/owner/report/data?month=${month}&year=${year}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal memuat laporan');
            }

            const result = await response.json();
            const reports = result.data;

            if (reports.length === 0) {
                tableContent.innerHTML = `
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>Tidak ada data laporan untuk periode ini</p>
                    </div>
                `;

                document.getElementById('totalIncome').textContent = 'Rp 0';
                document.getElementById('totalExpense').textContent = 'Rp 0';
                document.getElementById('netProfit').textContent = 'Rp 0';
                return;
            }

            // Calculate totals
            let totalIncome = 0;
            let totalExpense = 0;

            reports.forEach(report => {
                totalIncome += parseFloat(report.income) || 0;
                totalExpense += parseFloat(report.expenses) || 0;
            });

            const netProfit = totalIncome - totalExpense;

            // Update summary cards
            document.getElementById('totalIncome').textContent = formatCurrency(totalIncome);
            document.getElementById('totalExpense').textContent = formatCurrency(totalExpense);
            document.getElementById('netProfit').textContent = formatCurrency(netProfit);

            // Build table
            let tableHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Pemasukan</th>
                            <th>Pengeluaran</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            reports.forEach(report => {
                tableHTML += `
                    <tr>
                        <td>${formatDate(report.report_date)}</td>
                        <td>${report.description || '-'}</td>
                        <td class="amount-income">${formatCurrency(report.income)}</td>
                        <td class="amount-expense">${formatCurrency(report.expenses)}</td>
                    </tr>
                `;
            });

            tableHTML += `
                    </tbody>
                </table>
            `;

            tableContent.innerHTML = tableHTML;

        } catch (error) {
            console.error('Error:', error);
            showError(error.message || 'Terjadi kesalahan saat memuat data');
            tableContent.innerHTML = `
                <div class="empty-state">
                    <p>Gagal memuat data laporan</p>
                </div>
            `;
        }
    }

    // Download CSV
    function downloadCSV() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;

        window.location.href = `/owner/report/download/csv/${month}/${year}`;
    }

    // Download PDF
    function downloadPDF() {
        const month = document.getElementById('month').value;
        const year = document.getElementById('year').value;

        window.location.href = `/owner/report/download/pdf/${month}/${year}`;
    }

    // Load reports on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadReports();
    });
</script>
@endsection
