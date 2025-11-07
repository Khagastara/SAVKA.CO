@extends('layouts.owner')

@section('title', 'Monitoring Produksi')
@section('header', 'Monitoring Produksi')

@section('styles')
<style>
    .content-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--sage-green) 0%, var(--peach) 100%);
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-label {
        font-size: 14px;
        color: var(--charcoal-gray);
        opacity: 0.8;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--charcoal-gray);
    }

    .filter-bar {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-item {
        flex: 1;
        min-width: 200px;
    }

    .filter-item label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--charcoal-gray);
        font-size: 14px;
    }

    .filter-item select {
        width: 100%;
        padding: 10px;
        border: 2px solid var(--mocha-cream);
        border-radius: 8px;
        font-size: 14px;
    }

    .table-container {
        overflow-x: auto;
        margin-bottom: 24px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: linear-gradient(135deg, var(--sage-green) 0%, var(--peach) 100%);
    }

    th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: var(--charcoal-gray);
    }

    td {
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
    }

    tbody tr:hover {
        background: var(--warm-white);
    }

    .status-badge {
        display: inline-block;
        padding: 6px 16px;
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

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 24px;
    }

    .page-btn {
        padding: 8px 12px;
        border: 2px solid var(--mocha-cream);
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .page-btn:hover:not(:disabled) {
        background: var(--sage-green);
        border-color: var(--sage-green);
    }

    .page-btn.active {
        background: var(--sage-green);
        border-color: var(--sage-green);
        color: var(--charcoal-gray);
    }

    .page-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .staff-name {
        color: var(--sage-green);
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="content-card">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Produksi</div>
            <div class="stat-value" id="totalProduction">0</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Dalam Progres</div>
            <div class="stat-value" id="inProgress">0</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Selesai</div>
            <div class="stat-value" id="completed">0</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Unit Diproduksi</div>
            <div class="stat-value" id="totalUnits">0</div>
        </div>
    </div>

    <div class="filter-bar">
        <div class="filter-item">
            <label>Filter Staff</label>
            <select id="staffFilter" onchange="filterData()">
                <option value="">Semua Staff</option>
            </select>
        </div>
        <div class="filter-item">
            <label>Filter Status</label>
            <select id="statusFilter" onchange="filterData()">
                <option value="">Semua Status</option>
                <option value="Dalam Progres">Dalam Progres</option>
                <option value="Selesai">Selesai</option>
            </select>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Staff</th>
                    <th>Tanggal Produksi</th>
                    <th>Produk</th>
                    <th>Ukuran</th>
                    <th>Jumlah</th>
                    <th>Material</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="productionTableBody">
                <tr>
                    <td colspan="8" class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p>Memuat data produksi...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination" id="pagination"></div>
</div>
@endsection

@section('scripts')
<script>
    let productions = [];
    let filteredProductions = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    async function loadProductions() {
        try {
            const response = await fetch('/productions', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            });
            const data = await response.json();
            if (data.success) {
                productions = data.data;
                filteredProductions = productions;
                populateStaffFilter();
                updateStats();
                renderTable();
            }
        } catch (error) {
            console.error('Error loading productions');
        }
    }

    function populateStaffFilter() {
        const staffSet = new Set();
        productions.forEach(prod => {
            if (prod.user) {
                staffSet.add(JSON.stringify({
                    id: prod.user.id,
                    name: prod.user.name
                }));
            }
        });

        const select = document.getElementById('staffFilter');
        select.innerHTML = '<option value="">Semua Staff</option>';

        Array.from(staffSet).forEach(staffStr => {
            const staff = JSON.parse(staffStr);
            select.innerHTML += `<option value="${staff.id}">${staff.name}</option>`;
        });
    }

    function filterData() {
        const staffId = document.getElementById('staffFilter').value;
        const status = document.getElementById('statusFilter').value;

        filteredProductions = productions.filter(prod => {
            const matchStaff = !staffId || prod.user_id == staffId;
            const matchStatus = !status || prod.status === status;
            return matchStaff && matchStatus;
        });

        currentPage = 1;
        updateStats();
        renderTable();
    }

    function updateStats() {
        document.getElementById('totalProduction').textContent = filteredProductions.length;

        const inProgress = filteredProductions.filter(p => p.status === 'Dalam Progres').length;
        document.getElementById('inProgress').textContent = inProgress;

        const completed = filteredProductions.filter(p => p.status === 'Selesai').length;
        document.getElementById('completed').textContent = completed;

        const totalUnits = filteredProductions.reduce((sum, p) => sum + parseInt(p.quantity_produced || 0), 0);
        document.getElementById('totalUnits').textContent = totalUnits.toLocaleString('id-ID');
    }

    function renderTable() {
        const tbody = document.getElementById('productionTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredProductions.slice(start, end);

        if (paginatedData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p>Tidak ada data produksi ditemukan</p>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = paginatedData.map((prod, index) => {
                const statusClass = prod.status === 'Selesai' ? 'status-done' : 'status-progress';

                return `
                    <tr>
                        <td>${start + index + 1}</td>
                        <td><span class="staff-name">${prod.user?.name || 'N/A'}</span></td>
                        <td>${new Date(prod.production_date).toLocaleDateString('id-ID')}</td>
                        <td><strong>${prod.product_detail?.product?.product_name || 'N/A'}</strong></td>
                        <td>${prod.product_detail?.product_size || 'N/A'}</td>
                        <td>${prod.quantity_produced}</td>
                        <td>${prod.material_used}</td>
                        <td><span class="status-badge ${statusClass}">${prod.status}</span></td>
                    </tr>
                `;
            }).join('');
        }

        renderPagination();
    }

    function renderPagination() {
        const totalPages = Math.ceil(filteredProductions.length / itemsPerPage);
        const pagination = document.getElementById('pagination');

        if (totalPages <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let html = `
            <button class="page-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">
                &laquo; Prev
            </button>
        `;

        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += `<span>...</span>`;
            }
        }

        html += `
            <button class="page-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">
                Next &raquo;
            </button>
        `;

        pagination.innerHTML = html;
    }

    function changePage(page) {
        currentPage = page;
        renderTable();
    }

    loadProductions();
</script>
@endsection
