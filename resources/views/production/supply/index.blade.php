{{-- views/production/supply/index.blade.php --}}
@extends('layouts.production')

@section('title', 'Supply Material - Produksi')
@section('header', 'Supply Material')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        padding: 24px;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #E5E7EB;
    }

    th {
        background-color: #F9FAFB;
        font-weight: 600;
        color: #374151;
    }

    tr:hover {
        background-color: #F9FAFB;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .badge-success {
        background-color: #D1FAE5;
        color: #065F46;
    }

    .badge-warning {
        background-color: #FEF3C7;
        color: #92400E;
    }

    .badge-danger {
        background-color: #FEE2E2;
        color: #991B1B;
    }

    .stat-card {
        background: linear-gradient(135deg, #B7C4A4 0%, #8FA87D 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }

    .search-input {
        padding: 10px 16px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        width: 100%;
        max-width: 400px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #B7C4A4;
        box-shadow: 0 0 0 3px rgba(183, 196, 164, 0.1);
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card">
            <div class="stat-value" id="totalMaterials">0</div>
            <div class="stat-label">Total Material</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="totalStock">0</div>
            <div class="stat-label">Total Stok</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="lowStock">0</div>
            <div class="stat-label">Stok Rendah</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1">
                <input type="text" id="searchInput" class="search-input"
                       placeholder="🔍 Cari material berdasarkan nama atau warna...">
            </div>
            <div class="flex gap-2">
                <select id="filterStock" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Stok</option>
                    <option value="high">Stok Aman (>100)</option>
                    <option value="medium">Stok Sedang (50-100)</option>
                    <option value="low">Stok Rendah (<50)</option>
                </select>
                <button onclick="resetFilters()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Materials Table -->
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Material</h2>
            <button onclick="loadMaterials()"
                    class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
        <div class="table-container">
            <table id="materialsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Material</th>
                        <th>Warna</th>
                        <th>Stok Tersedia</th>
                        <th>Status</th>
                        <th>Info</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                                <span>Memuat data material...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Material Info Box -->
    <div class="card bg-blue-50 border-l-4 border-blue-500">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="font-semibold text-blue-800 mb-2">Informasi</h3>
                <p class="text-sm text-blue-700">
                    Halaman ini menampilkan daftar material yang tersedia untuk proses produksi.
                    Jika stok material rendah, segera hubungi Owner untuk melakukan pengadaan material.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let materials = [];
    let filteredMaterials = [];
    const apiUrl = '/materials';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Load materials on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadMaterials();

        // Setup search and filter
        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('filterStock').addEventListener('change', applyFilters);
    });

    // Load materials from API
    async function loadMaterials() {
        try {
            const response = await fetch(apiUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await response.json();

            if (data.success) {
                materials = data.data;
                filteredMaterials = materials;
                updateStatistics();
                renderMaterialsTable();
                showAlert('Data material berhasil dimuat', 'success');
            }
        } catch (error) {
            showAlert('Gagal memuat data material', 'danger');
            console.error('Error:', error);
        }
    }

    // Update statistics
    function updateStatistics() {
        document.getElementById('totalMaterials').textContent = materials.length;

        const totalStock = materials.reduce((sum, m) => sum + m.material_quantity, 0);
        document.getElementById('totalStock').textContent = totalStock.toLocaleString('id-ID');

        const lowStock = materials.filter(m => m.material_quantity < 50).length;
        document.getElementById('lowStock').textContent = lowStock;
    }

    // Apply filters
    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const stockFilter = document.getElementById('filterStock').value;

        filteredMaterials = materials.filter(material => {
            const matchesSearch = material.material_name.toLowerCase().includes(searchTerm) ||
                                 material.material_color.toLowerCase().includes(searchTerm);

            let matchesStock = true;
            if (stockFilter === 'high') {
                matchesStock = material.material_quantity > 100;
            } else if (stockFilter === 'medium') {
                matchesStock = material.material_quantity >= 50 && material.material_quantity <= 100;
            } else if (stockFilter === 'low') {
                matchesStock = material.material_quantity < 50;
            }

            return matchesSearch && matchesStock;
        });

        renderMaterialsTable();
    }

    // Reset filters
    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterStock').value = '';
        filteredMaterials = materials;
        renderMaterialsTable();
    }

    // Render materials table
    function renderMaterialsTable() {
        const tbody = document.querySelector('#materialsTable tbody');

        if (filteredMaterials.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Tidak ada data material yang sesuai</span>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = filteredMaterials.map(material => `
            <tr class="hover:bg-gray-50 transition">
                <td class="font-medium text-gray-900">${material.id}</td>
                <td class="font-semibold text-gray-800">${material.material_name}</td>
                <td>
                    <span class="inline-flex items-center">
                        ${material.material_color}
                    </span>
                </td>
                <td class="font-bold text-lg ${material.material_quantity < 50 ? 'text-red-600' : 'text-green-600'}">
                    ${material.material_quantity.toLocaleString('id-ID')}
                </td>
                <td>${getStockBadge(material.material_quantity)}</td>
                <td>
                    ${material.material_quantity < 50 ?
                        '<span class="text-xs text-red-600 font-medium">⚠️ Perlu Restock</span>' :
                        '<span class="text-xs text-green-600 font-medium">✓ Tersedia</span>'
                    }
                </td>
            </tr>
        `).join('');
    }

    // Get color code for display
    function getColorCode(colorName) {
        const colorMap = {
            'merah': '#EF4444',
            'biru': '#3B82F6',
            'hijau': '#10B981',
            'kuning': '#F59E0B',
            'hitam': '#1F2937',
            'putih': '#F3F4F6',
            'ungu': '#8B5CF6',
            'pink': '#EC4899',
            'orange': '#F97316',
            'coklat': '#92400E'
        };
        return colorMap[colorName.toLowerCase()] || '#6B7280';
    }

    // Get stock status badge
    function getStockBadge(quantity) {
        if (quantity > 100) {
            return '<span class="badge badge-success">Stok Aman</span>';
        } else if (quantity >= 50) {
            return '<span class="badge badge-warning">Stok Sedang</span>';
        } else {
            return '<span class="badge badge-danger">Stok Rendah</span>';
        }
    }

    // Show alert notification
    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alertContainer');
        const alertColors = {
            success: 'bg-green-100 border-green-500 text-green-700',
            danger: 'bg-red-100 border-red-500 text-red-700',
            warning: 'bg-yellow-100 border-yellow-500 text-yellow-700',
            info: 'bg-blue-100 border-blue-500 text-blue-700'
        };

        const alert = document.createElement('div');
        alert.className = `${alertColors[type]} border-l-4 p-4 mb-4 rounded-lg shadow-md`;
        alert.innerHTML = `
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    ${type === 'success' ?
                        '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                        '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
                    }
                    <p class="font-medium">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()"
                        class="text-lg font-bold ml-4 hover:opacity-75">×</button>
            </div>
        `;

        alertContainer.appendChild(alert);

        setTimeout(() => {
            alert.remove();
        }, 5000);
    }

    // Auto refresh every 5 minutes
    setInterval(loadMaterials, 300000);
</script>
@endsection
