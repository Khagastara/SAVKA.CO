@extends('layouts.production')

@section('title', 'Dashboard Produksi')
@section('header', 'Dashboard Produksi')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .stat-card {
        background: linear-gradient(135deg, var(--sage-green) 0%, var(--peach) 100%);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--charcoal-gray);
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--charcoal-gray);
        opacity: 0.8;
        font-weight: 500;
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .chart-header {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--charcoal-gray);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .production-item {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: background 0.3s ease;
    }

    .production-item:last-child {
        border-bottom: none;
    }

    .production-item:hover {
        background: var(--warm-white);
    }

    .production-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .production-icon.progress {
        background: #FFF3E0;
        color: #E65100;
    }

    .production-icon.done {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .production-content {
        flex: 1;
    }

    .production-title {
        font-weight: 600;
        color: var(--charcoal-gray);
        margin-bottom: 4px;
    }

    .production-meta {
        font-size: 0.85rem;
        color: #999;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-progress {
        background: #FFF3E0;
        color: #E65100;
    }

    .status-done {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .quick-action-btn {
        background: white;
        border: 2px solid var(--sage-green);
        color: var(--charcoal-gray);
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        justify-content: center;
    }

    .quick-action-btn:hover {
        background: var(--sage-green);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .material-alert {
        background: #FFF3E0;
        border-left: 4px solid #F57C00;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .material-alert-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .material-alert-title {
        font-weight: 600;
        color: #E65100;
    }

    .material-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .material-item:last-child {
        border-bottom: none;
    }

    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 8px;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #E5E7EB;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--sage-green), var(--peach));
        transition: width 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="chart-card bg-gradient-to-r from-green-50 to-orange-50 border-l-4 border-green-500">
        <div class="flex items-center gap-4">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Selamat Datang, <span id="staffName">Staff Produksi</span>!</h2>
                <p class="text-gray-600">Kelola jadwal produksi dan pantau stok material dengan efisien</p>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Jadwal Aktif -->
        <div class="stat-card">
            <div class="stat-icon">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="stat-value" id="activeSchedules">0</div>
            <div class="stat-label">Jadwal Aktif</div>
        </div>

        <!-- Total Produksi -->
        <div class="stat-card">
            <div class="stat-icon">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="stat-value" id="completedProduction">0</div>
            <div class="stat-label">Produksi Selesai</div>
        </div>

        <!-- Total Unit -->
        <div class="stat-card">
            <div class="stat-icon">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="stat-value" id="totalUnits">0</div>
            <div class="stat-label">Total Unit Diproduksi</div>
        </div>

        <!-- Material Tersedia -->
        <div class="stat-card">
            <div class="stat-icon">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
            </div>
            <div class="stat-value" id="availableMaterials">0</div>
            <div class="stat-label">Material Tersedia</div>
        </div>
    </div>

    <!-- Material Alert & Production Schedule -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Material Low Stock Alert -->
        <div class="chart-card">
            <div class="chart-header">
                <span>Material Stok Rendah</span>
            </div>
            <div id="lowStockMaterials">
                <div class="loading-skeleton h-20 mb-3"></div>
                <div class="loading-skeleton h-20 mb-3"></div>
                <div class="loading-skeleton h-20"></div>
            </div>
        </div>

        <!-- Jadwal Produksi Hari Ini -->
        <div class="chart-card lg:col-span-2">
            <div class="chart-header">
                <span Jadwal Produksi Bulain ini</span>
                <a href="{{ route('production.product.production') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua →</a>
            </div>
            <div id="monthlySchedule">
                <div class="loading-skeleton h-20 mb-3"></div>
                <div class="loading-skeleton h-20 mb-3"></div>
                <div class="loading-skeleton h-20"></div>
            </div>
        </div>
    </div>

    <!-- Progress This Week -->
    {{-- <div class="chart-card">
        <div class="chart-header">
            <span>Progres Minggu Ini</span>
            <span class="text-sm font-normal text-gray-600" id="weekProgress">0%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" id="progressBar" style="width: 0%"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800" id="weekTotal">0</div>
                <div class="text-sm text-gray-600">Total Jadwal</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600" id="weekCompleted">0</div>
                <div class="text-sm text-gray-600">Selesai</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600" id="weekProgress">0</div>
                <div class="text-sm text-gray-600">Dalam Proses</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600" id="weekUnits">0</div>
                <div class="text-sm text-gray-600">Unit Diproduksi</div>
            </div>
        </div>
    </div> --}}

    <!-- Quick Actions -->
    <div class="chart-card">
        <div class="chart-header">
            <span>⚡ Aksi Cepat</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('production.product.production') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Jadwal Produksi
            </a>
            <a href="{{ route('production.supply') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Cek Stok Material
            </a>
            <a href="{{ route('production.product.index') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Lihat Produk
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function loadDashboardData() {
        try {
            await Promise.all([
                loadProductions(),
                loadMaterials()
            ]);
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    async function loadProductions() {
        try {
            const response = await fetch('/productions', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            });
            const data = await response.json();

            if (data.success) {
                const productions = data.data;

                // Filter untuk bulan ini
                const now = new Date();
                const currentMonth = now.getMonth();
                const currentYear = now.getFullYear();

                const monthlyProductions = productions.filter(p => {
                    const prodDate = new Date(p.production_date);
                    return prodDate.getMonth() === currentMonth &&
                        prodDate.getFullYear() === currentYear;
                });

                // Stats berdasarkan bulan ini
                const activeSchedules = monthlyProductions.filter(p => p.status === 'Dalam Progres').length;
                const completedProduction = monthlyProductions.filter(p => p.status === 'Selesai').length;
                const totalUnits = monthlyProductions.reduce((sum, p) => sum + parseInt(p.quantity_produced || 0), 0);

                document.getElementById('activeSchedules').textContent = activeSchedules;
                document.getElementById('completedProduction').textContent = completedProduction;
                document.getElementById('totalUnits').textContent = totalUnits.toLocaleString('id-ID');

                // Monthly Schedule
                renderMonthlySchedule(productions);

                // Week Progress (tetap per minggu)
                renderWeekProgress(productions);
            }
        } catch (error) {
            console.error('Error loading productions:', error);
        }
    }

    async function loadMaterials() {
        try {
            const response = await fetch('/materials', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            const data = await response.json();

            if (data.success) {
                const materials = data.data;
                document.getElementById('availableMaterials').textContent = materials.length;

                // Low Stock Materials
                const lowStock = materials.filter(m => m.material_quantity < 50);
                renderLowStockMaterials(lowStock);
            }
        } catch (error) {
            console.error('Error loading materials:', error);
        }
    }

    function renderMonthlySchedule(productions) {
        // Get current month and year
        const now = new Date();
        const currentMonth = now.getMonth(); // 0-11
        const currentYear = now.getFullYear();

        // Filter productions untuk bulan ini
        const monthlyProductions = productions.filter(p => {
            const prodDate = new Date(p.production_date);
            return prodDate.getMonth() === currentMonth &&
                prodDate.getFullYear() === currentYear;
        });

        const container = document.getElementById('monthlySchedule');

        if (monthlyProductions.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="font-medium">Tidak ada jadwal produksi bulan ini</p>
                    <p class="text-sm mt-2">Buat jadwal produksi baru untuk memulai</p>
                </div>
            `;
        } else {
            // Sort by date (newest first) dan batasi 10 item
            const sortedProductions = monthlyProductions
                .sort((a, b) => new Date(b.production_date) - new Date(a.production_date))
                .slice(0, 10);

            container.innerHTML = sortedProductions.map(prod => {
                const iconClass = prod.status === 'Selesai' ? 'done' : 'progress';
                const statusClass = prod.status === 'Selesai' ? 'status-done' : 'status-progress';

                // Format tanggal untuk menampilkan hari dan tanggal
                const prodDate = new Date(prod.production_date);
                const formattedDate = prodDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short'
                });

                return `
                    <div class="production-item">
                        <div class="production-icon ${iconClass}">
                            ${prod.status === 'Selesai' ?
                                '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>' :
                                '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>'
                            }
                        </div>
                        <div class="production-content">
                            <div class="production-title">${prod.product_detail?.product?.product_name || 'Produk'} - ${prod.product_detail?.product_size || '-'}</div>
                            <div class="production-meta">${formattedDate} • ${prod.quantity_produced} unit • ${prod.material?.material_name || 'Material'}</div>
                        </div>
                        <span class="status-badge ${statusClass}">${prod.status}</span>
                    </div>
                `;
            }).join('');
        }
    }

    function renderLowStockMaterials(materials) {
        const container = document.getElementById('lowStockMaterials');

        if (materials.length === 0) {
            container.innerHTML = `
                <div class="text-center py-6 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm">Semua material stok aman</p>
                </div>
            `;
        } else {
            container.innerHTML = materials.slice(0, 5).map(material => `
                <div class="material-item">
                    <div>
                        <div class="font-semibold text-sm">${material.material_name}</div>
                        <div class="text-xs text-gray-600">${material.material_color}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-red-600">${material.material_quantity}</div>
                        <div class="text-xs text-gray-500">unit</div>
                    </div>
                </div>
            `).join('');
        }
    }

    function renderWeekProgress(productions) {
        const now = new Date();
        const weekStart = new Date(now.setDate(now.getDate() - now.getDay()));
        weekStart.setHours(0, 0, 0, 0);

        const weekProductions = productions.filter(p => {
            const prodDate = new Date(p.production_date);
            return prodDate >= weekStart;
        });

        const weekTotal = weekProductions.length;
        const weekCompleted = weekProductions.filter(p => p.status === 'Selesai').length;
        const weekInProgress = weekProductions.filter(p => p.status === 'Dalam Progres').length;
        const weekUnits = weekProductions.reduce((sum, p) => sum + parseInt(p.quantity_produced || 0), 0);

        const progressPercent = weekTotal > 0 ? Math.round((weekCompleted / weekTotal) * 100) : 0;

        document.getElementById('weekTotal').textContent = weekTotal;
        document.getElementById('weekCompleted').textContent = weekCompleted;
        document.getElementById('weekProgress').textContent = weekInProgress;
        document.getElementById('weekUnits').textContent = weekUnits.toLocaleString('id-ID');
        document.getElementById('progressBar').style.width = progressPercent + '%';

        const progressText = document.querySelector('.chart-header span.text-sm');
        if (progressText) {
            progressText.textContent = progressPercent + '%';
        }
    }

    // Load data on page load
    document.addEventListener('DOMContentLoaded', loadDashboardData);
</script>
@endsection
