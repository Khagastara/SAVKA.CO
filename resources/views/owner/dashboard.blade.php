@extends('layouts.owner')

@section('title', 'Dashboard Owner')
@section('header', 'Dashboard Overview')

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

    .activity-item {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: background 0.3s ease;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item:hover {
        background: var(--warm-white);
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-icon.production {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .activity-icon.shipment {
        background: #E3F2FD;
        color: #1565C0;
    }

    .activity-icon.material {
        background: #FFF3E0;
        color: #E65100;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: var(--charcoal-gray);
        margin-bottom: 4px;
    }

    .activity-time {
        font-size: 0.85rem;
        color: #999;
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
    }

    .quick-action-btn:hover {
        background: var(--sage-green);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .low-stock-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #FFEBEE;
        color: #C62828;
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
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Produk -->
        <div class="stat-card">
            <div class="stat-icon">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="stat-value" id="totalProducts">0</div>
            <div class="stat-label">Total Produk</div>
        </div>

        <!-- Material Tersedia -->
        <div class="stat-card">
            <div class="stat-icon">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="stat-value" id="totalMaterials">0</div>
            <div class="stat-label">Material Tersedia</div>
        </div>

        <!-- Produksi Aktif -->
        <div class="stat-card">
            <div class="stat-icon">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                </svg>
            </div>
            <div class="stat-value" id="activeProductions">0</div>
            <div class="stat-label">Produksi Aktif</div>
        </div>

        <!-- Pengiriman Bulan Ini -->
        <div class="stat-card">
            <div class="stat-icon">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
            </div>
            <div class="stat-value" id="monthlyShipments">0</div>
            <div class="stat-label">Pengiriman Bulan Ini</div>
        </div>
    </div>

    <!-- Charts & Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stok Material Rendah -->
        <div class="chart-card">
            <div class="chart-header">
                <span>Stok Material Rendah</span>
                <span class="low-stock-badge" id="lowStockCount">0</span>
            </div>
            <div id="lowStockList" class="space-y-3">
                <div class="loading-skeleton h-16"></div>
                <div class="loading-skeleton h-16"></div>
                <div class="loading-skeleton h-16"></div>
            </div>
        </div>

        <!-- Aktivitas Terkini -->
        <div class="chart-card lg:col-span-2">
            <div class="chart-header">
                <span>Aktivitas Terkini</span>
                <a href="{{ route('owner.reports') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua →</a>
            </div>
            <div id="activityList">
                <div class="loading-skeleton h-20 mb-3"></div>
                <div class="loading-skeleton h-20 mb-3"></div>
                <div class="loading-skeleton h-20"></div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="chart-card">
        <div class="chart-header">
            <span>Aksi Cepat</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('owner.supply.material') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Material
            </a>
            <a href="{{ route('owner.product') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Produk
            </a>
            <a href="{{ route('owner.staff.index') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Kelola Staff
            </a>
            <a href="{{ route('owner.reports') }}" class="quick-action-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Lihat Laporan
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    async function loadDashboardData() {
        try {
            await Promise.all([
                loadProducts(),
                loadMaterials(),
                loadProductions(),
                loadShipments(),
                loadRecentActivities()
            ]);
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    async function loadProducts() {
        try {
            const response = await fetch('/products', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('totalProducts').textContent = data.data.length;
            }
        } catch (error) {
            console.error('Error loading products:', error);
        }
    }

    async function loadMaterials() {
        try {
            const response = await fetch('/materials', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await response.json();
            if (data.success) {
                const materials = data.data;
                document.getElementById('totalMaterials').textContent = materials.length;

                // Filter material dengan stok rendah (< 50)
                const lowStock = materials.filter(m => m.material_quantity < 50);
                document.getElementById('lowStockCount').textContent = lowStock.length;

                const lowStockList = document.getElementById('lowStockList');
                if (lowStock.length === 0) {
                    lowStockList.innerHTML = '<p class="text-center text-gray-500 py-4">Semua material stok aman</p>';
                } else {
                    lowStockList.innerHTML = lowStock.slice(0, 5).map(material => `
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
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
        } catch (error) {
            console.error('Error loading materials:', error);
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
                const activeProductions = data.data.filter(p => p.status === 'Dalam Progres');
                document.getElementById('activeProductions').textContent = activeProductions.length;
            }
        } catch (error) {
            console.error('Error loading productions:', error);
        }
    }

    async function loadShipments() {
        try {
            const response = await fetch('/shipments', {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            });
            const shipments = await response.json();

            // Filter pengiriman bulan ini
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();
            const monthlyShipments = shipments.filter(s => {
                const shipmentDate = new Date(s.shipment_date);
                return shipmentDate.getMonth() === currentMonth && shipmentDate.getFullYear() === currentYear;
            });

            document.getElementById('monthlyShipments').textContent = monthlyShipments.length;
        } catch (error) {
            console.error('Error loading shipments:', error);
        }
    }

    async function loadRecentActivities() {
        try {
            const [productions, shipments] = await Promise.all([
                fetch('/productions', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(r => r.json()),
                fetch('/shipments', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(r => r.json())
            ]);

            const activities = [];

            // Tambahkan SEMUA aktivitas produksi
            if (productions.success) {
                productions.data.forEach(prod => {
                    activities.push({
                        type: 'production',
                        title: `Produksi ${prod.product_detail?.product?.product_name || 'Produk'}`,
                        description: `${prod.quantity_produced} unit - ${prod.status}`,
                        time: prod.production_date,
                        rawDate: new Date(prod.production_date), // Untuk sorting
                        icon: 'production'
                    });
                });
            }

            // Tambahkan SEMUA aktivitas pengiriman
            shipments.forEach(ship => {
                activities.push({
                    type: 'shipment',
                    title: `Pengiriman ke ${ship.destination_address}`,
                    description: `Rp ${parseInt(ship.total_price).toLocaleString('id-ID')} - ${ship.shipment_status}`,
                    time: ship.shipment_date,
                    rawDate: new Date(ship.shipment_date), // Untuk sorting
                    icon: 'shipment'
                });
            });

            // Sort by time DESC (terbaru dulu) dan ambil 5 teratas
            activities.sort((a, b) => b.rawDate - a.rawDate);

            const activityList = document.getElementById('activityList');
            if (activities.length === 0) {
                activityList.innerHTML = '<p class="text-center text-gray-500 py-8">Belum ada aktivitas</p>';
            } else {
                activityList.innerHTML = activities.slice(0, 5).map(activity => `
                    <div class="activity-item">
                        <div class="activity-icon ${activity.icon}">
                            ${getActivityIcon(activity.icon)}
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">${activity.title}</div>
                            <div class="activity-time">${activity.description} • ${formatTime(activity.time)}</div>
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading activities:', error);
        }
    }

    function getActivityIcon(type) {
        const icons = {
            production: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>',
            shipment: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/></svg>',
            material: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/><path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/><path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/></svg>'
        };
        return icons[type] || icons.production;
    }

    function formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays === 0) return 'Hari ini';
        if (diffDays === 1) return 'Kemarin';
        if (diffDays < 7) return `${diffDays} hari lalu`;
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    // Load data saat halaman dimuat
    document.addEventListener('DOMContentLoaded', loadDashboardData);
</script>
@endsection
