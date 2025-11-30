@extends('layouts.production')

@section('title', 'Jadwal Produksi')
@section('header', 'Jadwal Produksi')

@section('styles')
<style>
    .content-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .btn-primary {
        background: var(--sage-green);
        color: var(--charcoal-gray);
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background: #a0b090;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        font-size: 12px;
    }

    .btn-edit {
        background: #2196F3;
        color: white;
    }

    .btn-edit:hover {
        background: #1976D2;
    }

    .btn-delete {
        background: #f44336;
        color: white;
    }

    .btn-delete:hover {
        background: #d32f2f;
    }

    .btn-complete {
        background: var(--sage-green);
        color: var(--charcoal-gray);
    }

    .btn-complete:hover {
        background: #a0b090;
    }

    .btn-action:disabled {
        opacity: 0.5;
        cursor: not-allowed;
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

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal.show {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 24px;
        max-width: 450px;
        width: 90%;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-header h2 {
        font-size: 1.25rem;
        color: var(--charcoal-gray);
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: var(--charcoal-gray);
        line-height: 1;
        padding: 0;
        width: 28px;
        height: 28px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: var(--charcoal-gray);
        font-size: 14px;
    }

    .form-group input, .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--mocha-cream);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: var(--sage-green);
    }

    .btn-submit {
        background: var(--sage-green);
        color: var(--charcoal-gray);
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-submit:hover {
        background: #a0b090;
        transform: translateY(-2px);
    }

    .alert {
        display: none;
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        animation: slideDown 0.3s ease;
        font-size: 14px;
    }

    .alert.show {
        display: block;
    }

    .alert-success {
        background: #E8F5E9;
        color: #2E7D32;
        border-left: 4px solid #4CAF50;
    }

    .alert-error {
        background: #FFEBEE;
        color: #C62828;
        border-left: 4px solid #F44336;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
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

    .info-box {
        background: #E3F2FD;
        border-left: 4px solid #2196F3;
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 16px;
        font-size: 13px;
        color: #1565C0;
    }
</style>
@endsection

@section('content')
<div class="content-card">
    <div id="alertContainer"></div>

    <div class="top-bar">
        <h2 style="font-size: 1.25rem; color: var(--charcoal-gray);">Jadwal Produksi Saya</h2>
        <button class="btn-primary" onclick="openAddModal()">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jadwal
        </button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Produksi</th>
                    <th>Produk</th>
                    <th>Ukuran</th>
                    <th>Jumlah</th>
                    <th>Material</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="productionTableBody">
                <tr>
                    <td colspan="8" class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p>Memuat data jadwal produksi...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination" id="pagination"></div>
</div>

<!-- Modal Tambah/Edit Jadwal -->
<div id="productionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Tambah Jadwal Produksi</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form id="productionForm">
            <input type="hidden" id="productionId">
            <div class="info-box">
                <strong>Info:</strong> Pilih produk dan material untuk dijadwalkan. Material akan dikurangi otomatis.
            </div>

            <div class="form-group">
                <label>Tanggal Produksi</label>
                <input type="date" id="productionDate" required>
            </div>

            <div class="form-group">
                <label>Produk</label>
                <select id="productSelect" required onchange="loadProductSizes()">
                    <option value="">Pilih Produk</option>
                </select>
            </div>

            <div class="form-group">
                <label>Ukuran</label>
                <select id="sizeSelect" required disabled>
                    <option value="">Pilih ukuran terlebih dahulu</option>
                </select>
            </div>

            <div class="form-group">
                <label>Jumlah Produksi</label>
                <input type="number" id="quantityProduced" required min="1" placeholder="Jumlah yang akan diproduksi">
            </div>

            <div class="form-group">
                <label>Material</label>
                <select id="materialSelect" required>
                    <option value="">Pilih Material</option>
                </select>
            </div>

            <div class="form-group">
                <label>Jumlah Material Digunakan</label>
                <input type="number" id="materialUsed" required min="1" placeholder="Jumlah material yang digunakan">
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">Buat Jadwal Produksi</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let productions = [];
    let products = [];
    let materials = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let editMode = false;

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
                renderTable();
            }
        } catch (error) {
            showAlert('Gagal memuat data produksi', 'error');
        }
    }

    async function loadProducts() {
        try {
            const response = await fetch('/products', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            });
            const data = await response.json();
            if (data.success) {
                products = data.data;
                const select = document.getElementById('productSelect');
                select.innerHTML = '<option value="">Pilih Produk</option>';
                products.forEach(product => {
                    select.innerHTML += `<option value="${product.id}">${product.product_name} - ${product.product_color}</option>`;
                });
            }
        } catch (error) {
            console.error('Error loading products');
        }
    }

    async function loadMaterials() {
        try {
            const response = await fetch('/materials', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            });
            const data = await response.json();
            if (data.success) {
                materials = data.data;
                const select = document.getElementById('materialSelect');
                select.innerHTML = '<option value="">Pilih Material</option>';
                materials.forEach(material => {
                    select.innerHTML += `<option value="${material.id}">${material.material_name} (Stok: ${material.material_quantity})</option>`;
                });
            }
        } catch (error) {
            console.error('Error loading materials');
        }
    }

    function loadProductSizes() {
        const productId = document.getElementById('productSelect').value;
        const sizeSelect = document.getElementById('sizeSelect');

        if (!productId) {
            sizeSelect.disabled = true;
            sizeSelect.innerHTML = '<option value="">Pilih produk terlebih dahulu</option>';
            return;
        }

        const product = products.find(p => p.id == productId);
        if (product && product.product_detail) {
            sizeSelect.disabled = false;
            sizeSelect.innerHTML = '<option value="">Pilih Ukuran</option>';
            product.product_detail.forEach(detail => {
                sizeSelect.innerHTML += `<option value="${detail.id}">${detail.product_size} (Stok: ${detail.product_stock})</option>`;
            });
        }
    }

    function renderTable() {
        const tbody = document.getElementById('productionTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = productions.slice(start, end);

        if (paginatedData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p>Belum ada jadwal produksi</p>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = paginatedData.map((prod, index) => {
                const statusClass = prod.status === 'Selesai' ? 'status-done' : 'status-progress';
                const statusText = prod.status;
                const canEdit = prod.status !== 'Selesai';
                const canComplete = prod.status === 'Dalam Progres';

                return `
                    <tr>
                        <td>${start + index + 1}</td>
                        <td>${new Date(prod.production_date).toLocaleDateString('id-ID')}</td>
                        <td><strong>${prod.product_detail?.product?.product_name || 'N/A'}</strong></td>
                        <td>${prod.product_detail?.product_size || 'N/A'}</td>
                        <td>${prod.quantity_produced}</td>
                        <td>${prod.material?.material_name || 'N/A'} (${prod.material_used})</td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="action-buttons">
                                ${canEdit ? `
                                    <button class="btn-action btn-edit" onclick="openEditModal(${prod.id})" title="Edit">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteProduction(${prod.id})" title="Hapus">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                ` : ''}
                                <button class="btn-action btn-complete" onclick="updateStatus(${prod.id})" ${!canComplete ? 'disabled' : ''} title="${canComplete ? 'Selesaikan' : 'Selesai'}">
                                    ${canComplete ? '✓ Selesaikan' : '✓ Selesai'}
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        renderPagination();
    }

    function renderPagination() {
        const totalPages = Math.ceil(productions.length / itemsPerPage);
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

    function openAddModal() {
        editMode = false;
        document.getElementById('modalTitle').textContent = 'Tambah Jadwal Produksi';
        document.getElementById('submitBtn').textContent = 'Buat Jadwal Produksi';
        document.getElementById('productionForm').reset();
        document.getElementById('productionId').value = '';
        document.getElementById('productionDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('sizeSelect').disabled = true;
        loadProducts();
        loadMaterials();
        document.getElementById('productionModal').classList.add('show');
    }

    function openEditModal(id) {
        editMode = true;
        document.getElementById('modalTitle').textContent = 'Edit Jadwal Produksi';
        document.getElementById('submitBtn').textContent = 'Update Jadwal Produksi';

        const production = productions.find(p => p.id === id);
        if (!production) return;

        document.getElementById('productionId').value = production.id;
        document.getElementById('productionDate').value = production.production_date;
        document.getElementById('quantityProduced').value = production.quantity_produced;
        document.getElementById('materialUsed').value = production.material_used;

        loadProducts().then(() => {
            document.getElementById('productSelect').value = production.product_detail.product.id;
            loadProductSizes();
            setTimeout(() => {
                document.getElementById('sizeSelect').value = production.product_detail_id;
            }, 100);
        });

        loadMaterials().then(() => {
            document.getElementById('materialSelect').value = production.material_id;
        });

        document.getElementById('productionModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('productionModal').classList.remove('show');
    }

    document.getElementById('productionForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
            production_date: document.getElementById('productionDate').value,
            quantity_produced: parseInt(document.getElementById('quantityProduced').value),
            material_used: parseInt(document.getElementById('materialUsed').value),
            product_detail_id: parseInt(document.getElementById('sizeSelect').value),
            material_id: parseInt(document.getElementById('materialSelect').value)
        };

        console.log('Form Data:', formData); // Log data yang dikirimkan

        const productionId = document.getElementById('productionId').value;
        const url = editMode ? `/productions/${productionId}` : '/productions';
        const method = editMode ? 'PUT' : 'POST';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData)
            });

            console.log('Response Status:', response.status); // Log status respons

            const data = await response.json();
            console.log('Response Data:', data); // Log data respons

            if (response.ok && data.success) {
                showAlert(data.message || 'Jadwal produksi berhasil disimpan!', 'success');
                closeModal();
                loadProductions();
            } else {
                showAlert(data.message || 'Gagal menyimpan jadwal produksi', 'error');
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            showAlert('Terjadi kesalahan saat menyimpan jadwal produksi', 'error');
        }
    });

    async function updateStatus(id) {
        if (!confirm('Selesaikan produksi ini? Stok produk akan bertambah otomatis.')) {
            return;
        }

        try {
            const response = await fetch(`/productions/${id}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: 'Selesai' })
            });

            console.log('Response Status:', response.status); // Log status respons

            const data = await response.json();
            console.log('Response Data:', data); // Log data respons

            if (data.success) {
                showAlert('Produksi berhasil diselesaikan!', 'success');
                loadProductions();
            } else {
                showAlert('Gagal mengupdate status', 'error');
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            showAlert('Terjadi kesalahan', 'error');
        }
    }

    function showAlert(message, type) {
        const container = document.getElementById('alertContainer');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} show`;
        alert.textContent = message;
        container.appendChild(alert);

        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        }, 3000);
    }

    loadProductions();
</script>
@endsection
