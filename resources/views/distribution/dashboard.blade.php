@extends('layouts.distribution')

@section('title', 'Dashboard Distribusi')
@section('header', 'Dashboard Distribusi')

@section('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon.yellow {
        background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
    }

    .stat-icon.green {
        background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
    }

    .stat-icon.peach {
        background: linear-gradient(135deg, var(--peach) 0%, var(--mocha-cream) 100%);
    }

    .stat-icon.sage {
        background: linear-gradient(135deg, var(--sage-green) 0%, #9DB88A 100%);
    }

    .stat-label {
        font-size: 0.875rem;
        color: #666;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--charcoal-gray);
    }

    .chart-container {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .chart-header {
        margin-bottom: 24px;
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--charcoal-gray);
    }

    .chart-subtitle {
        font-size: 0.875rem;
        color: #666;
        margin-top: 4px;
    }

    .recent-shipments {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: var(--warm-white);
    }

    th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--charcoal-gray);
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.875rem;
    }

    tbody tr:hover {
        background: var(--warm-white);
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-yellow {
        background: #FFF3E0;
        color: #E65100;
    }

    .badge-green {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .chart-canvas {
        max-height: 300px;
    }

    .loading {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .welcome-message {
        color: #666;
        font-size: 0.875rem;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <p class="welcome-message">Selamat datang kembali!</p>

    <div style="display: flex; gap: 12px; align-items: center;">
        <label style="font-weight: 500; color: var(--charcoal-gray);">Filter:</label>
        <select id="monthFilter" style="padding: 8px 16px; border: 2px solid var(--mocha-cream); border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
            <option value="all">Semua Bulan</option>
        </select>
        <select id="yearFilter" style="padding: 8px 16px; border: 2px solid var(--mocha-cream); border-radius: 8px; font-size: 14px; background: white; cursor: pointer;">
            <option value="all">Semua Tahun</option>
        </select>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Pengiriman</div>
                <div class="stat-value" id="totalShipments">-</div>
            </div>
            <div class="stat-icon yellow">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18l-1 8H4l-1-8zm3 8a2 2 0 104 0m-4 0a2 2 0 114 0m10 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Dalam Pengiriman</div>
                <div class="stat-value" id="ongoingShipments">-</div>
            </div>
            <div class="stat-icon peach">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Sampai Tujuan</div>
                <div class="stat-value" id="deliveredShipments">-</div>
            </div>
            <div class="stat-icon green">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value" id="totalRevenue">-</div>
            </div>
            <div class="stat-icon sage">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="chart-container">
    <div class="chart-header">
        <div class="chart-title">Statistik Pengiriman Bulanan</div>
        <div class="chart-subtitle">Data pengiriman 6 bulan terakhir</div>
    </div>
    <canvas id="shipmentsChart" class="chart-canvas"></canvas>
</div>

<!-- Recent Shipments -->
<div class="recent-shipments">
    <div class="chart-header">
        <div class="chart-title">Pengiriman Terbaru</div>
        <div class="chart-subtitle">5 pengiriman terakhir</div>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tujuan</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="recentShipmentsBody">
                <tr>
                    <td colspan="4" class="loading">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let shipmentsData = [];
    let chart = null;
    let selectedYear = 'current';
    let selectedMonth = 'current';

    async function fetchDashboardData() {
        try {
            const response = await fetch('/shipments', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                shipmentsData = await response.json();
                populateFilters();
                setDefaultFilters();
                updateStatistics();
                renderChart();
                renderRecentShipments();
            } else {
                console.error('Failed to fetch shipments');
            }
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
        }
    }

    function populateFilters() {
        const yearFilter = document.getElementById('yearFilter');
        const monthFilter = document.getElementById('monthFilter');
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // Get unique years from shipments
        const years = new Set();
        shipmentsData.forEach(s => {
            const date = new Date(s.shipment_date);
            years.add(date.getFullYear());
        });

        // Sort years descending
        const sortedYears = Array.from(years).sort((a, b) => b - a);

        // Populate year filter
        yearFilter.innerHTML = '<option value="all">Semua Tahun</option>';
        sortedYears.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearFilter.appendChild(option);
        });

        // Populate month filter
        monthFilter.innerHTML = '<option value="all">Semua Bulan</option>';
        monthNames.forEach((name, index) => {
            const option = document.createElement('option');
            option.value = index + 1;
            option.textContent = name;
            monthFilter.appendChild(option);
        });
    }

    function setDefaultFilters() {
        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = now.getMonth() + 1;

        const yearFilter = document.getElementById('yearFilter');
        const monthFilter = document.getElementById('monthFilter');

        // Set default to current year and month
        yearFilter.value = currentYear;
        monthFilter.value = currentMonth;

        selectedYear = currentYear;
        selectedMonth = currentMonth;
    }

    function getFilteredShipments() {
        return shipmentsData.filter(s => {
            const date = new Date(s.shipment_date);
            const shipYear = date.getFullYear();
            const shipMonth = date.getMonth() + 1;

            const yearMatch = selectedYear === 'all' || shipYear === parseInt(selectedYear);
            const monthMatch = selectedMonth === 'all' || shipMonth === parseInt(selectedMonth);

            return yearMatch && monthMatch;
        });
    }

    function updateStatistics() {
        const filteredData = getFilteredShipments();

        const total = filteredData.length;
        const ongoing = filteredData.filter(s => s.shipment_status === 'Dalam Pengiriman').length;
        const delivered = filteredData.filter(s => s.shipment_status === 'Sampai Tujuan').length;
        const revenue = filteredData.reduce((sum, s) => sum + parseInt(s.total_price || 0), 0);

        document.getElementById('totalShipments').textContent = total;
        document.getElementById('ongoingShipments').textContent = ongoing;
        document.getElementById('deliveredShipments').textContent = delivered;
        document.getElementById('totalRevenue').textContent = 'Rp ' + formatPrice(revenue);
    }

    function renderChart() {
        const ctx = document.getElementById('shipmentsChart').getContext('2d');

        // Get monthly data for the last 6 months
        const monthlyData = getMonthlyData();

        if (chart) {
            chart.destroy();
        }

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.labels,
                datasets: [{
                    label: 'Jumlah Pengiriman',
                    data: monthlyData.values,
                    borderColor: '#B7C4A4',
                    backgroundColor: 'rgba(183, 196, 164, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#B7C4A4',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(58, 58, 58, 0.9)',
                        padding: 12,
                        cornerRadius: 8,
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    function getMonthlyData() {
        const months = [];
        const values = [];
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Get last 6 months
        const now = new Date();
        for (let i = 5; i >= 0; i--) {
            const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
            const monthYear = `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
            months.push(monthYear);

            // Count shipments in this month
            const count = shipmentsData.filter(s => {
                const shipDate = new Date(s.shipment_date);
                return shipDate.getMonth() === date.getMonth() &&
                       shipDate.getFullYear() === date.getFullYear();
            }).length;

            values.push(count);
        }

        return { labels: months, values: values };
    }

    function renderRecentShipments() {
        const tbody = document.getElementById('recentShipmentsBody');
        const filteredData = getFilteredShipments();

        const recent = filteredData
            .sort((a, b) => new Date(b.shipment_date) - new Date(a.shipment_date))
            .slice(0, 5);

        if (recent.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="empty-state">Belum ada data pengiriman untuk periode ini</td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = recent.map(shipment => `
            <tr>
                <td>${formatDate(shipment.shipment_date)}</td>
                <td>${shipment.destination_address}</td>
                <td>Rp ${formatPrice(shipment.total_price)}</td>
                <td>
                    <span class="badge ${shipment.shipment_status === 'Sampai Tujuan' ? 'badge-green' : 'badge-yellow'}">
                        ${shipment.shipment_status}
                    </span>
                </td>
            </tr>
        `).join('');
    }

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }

    // Year filter change handler
    document.getElementById('yearFilter').addEventListener('change', function(e) {
        selectedYear = e.target.value;
        updateStatistics();
        renderRecentShipments();
    });

    // Month filter change handler
    document.getElementById('monthFilter').addEventListener('change', function(e) {
        selectedMonth = e.target.value;
        updateStatistics();
        renderRecentShipments();
    });

    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        fetchDashboardData();

        // Refresh data every 5 minutes
        setInterval(fetchDashboardData, 300000);
    });
</script>
@endsection
