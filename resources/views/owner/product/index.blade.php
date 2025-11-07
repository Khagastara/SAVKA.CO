@extends('layouts.owner')

@section('title', 'Manajemen Produk')
@section('header', 'Manajemen Produk')

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
        gap: 20px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 12px 40px 12px 16px;
        border: 2px solid var(--mocha-cream);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--sage-green);
    }

    .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--charcoal-gray);
        opacity: 0.5;
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

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-stock {
        background: #E8F5E9;
        color: #2E7D32;
    }

    .badge-low {
        background: #FFF3E0;
        color: #E65100;
    }

    .badge-out {
        background: #FFEBEE;
        color: #C62828;
    }

    .btn-edit, .btn-delete {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-right: 8px;
    }

    .btn-edit {
        background: var(--peach);
        color: var(--charcoal-gray);
    }

    .btn-edit:hover {
        background: #ffb89a;
    }

    .btn-delete {
        background: #FFEBEE;
        color: #C62828;
    }

    .btn-delete:hover {
        background: #FFCDD2;
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
        padding: 30px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .modal-header h2 {
        font-size: 1.5rem;
        color: var(--charcoal-gray);
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: var(--charcoal-gray);
        line-height: 1;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--charcoal-gray);
    }

    .form-group input, .form-group select {
        width: 100%;
        padding: 12px;
        border: 2px solid var(--mocha-cream);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: var(--sage-green);
    }

    .detail-row {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 12px;
        margin-bottom: 12px;
        align-items: end;
    }

    .btn-add-detail {
        background: var(--sage-green);
        color: var(--charcoal-gray);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: 600;
        margin-bottom: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-remove-detail {
        background: #FFEBEE;
        color: #C62828;
        border: none;
        border-radius: 8px;
        padding: 12px 16px;
        cursor: pointer;
    }

    .btn-submit {
        background: var(--sage-green);
        color: var(--charcoal-gray);
        border: none;
        border-radius: 10px;
        padding: 14px 28px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #a0b090;
        transform: translateY(-2px);
    }

    /* Alert Styles */
    .alert {
        display: none;
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        animation: slideDown 0.3s ease;
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
</style>
@endsection

@section('content')
<div class="content-card">
    <div id="alertContainer"></div>

    <div class="top-bar">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari produk...">
            <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <button class="btn-primary" onclick="openAddModal()">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Warna</th>
                    <th>Harga</th>
                    <th>Ukuran & Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <tr>
                    <td colspan="6" class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>Memuat data produk...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination" id="pagination"></div>
</div>

<!-- Modal Tambah/Edit Produk -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Tambah Produk</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form id="productForm">
            <input type="hidden" id="productId">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" id="productName" required>
            </div>
            <div class="form-group">
                <label>Warna</label>
                <input type="text" id="productColor" required>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" id="productPrice" required min="0">
            </div>
            
            <div id="detailsContainer">
                <label style="display: block; margin-bottom: 12px; font-weight: 600;">Detail Produk (Ukuran & Stok)</label>
                <button type="button" class="btn-add-detail" onclick="addDetailRow()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Detail
                </button>
                <div id="detailRows"></div>
            </div>

            <button type="submit" class="btn-submit">Simpan Produk</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let products = [];
    let filteredProducts = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let isEditMode = false;

    // Load products
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
                filteredProducts = products;
                renderTable();
            }
        } catch (error) {
            showAlert('Gagal memuat data produk', 'error');
        }
    }

    // Render table
    function renderTable() {
        const tbody = document.getElementById('productTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredProducts.slice(start, end);

        if (paginatedData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>Tidak ada produk ditemukan</p>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = paginatedData.map((product, index) => {
                const detailsHtml = product.product_detail.map(detail => {
                    let badgeClass = 'badge-stock';
                    if (detail.product_stock === 0) badgeClass = 'badge-out';
                    else if (detail.product_stock < 10) badgeClass = 'badge-low';
                    
                    return `<span class="badge ${badgeClass}">${detail.product_size}: ${detail.product_stock}</span>`;
                }).join(' ');

                return `
                    <tr>
                        <td>${start + index + 1}</td>
                        <td><strong>${product.product_name}</strong></td>
                        <td>${product.product_color}</td>
                        <td>Rp ${parseInt(product.product_price).toLocaleString('id-ID')}</td>
                        <td>${detailsHtml}</td>
                        <td>
                            <button class="btn-edit" onclick="editProduct(${product.id})">Edit</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        renderPagination();
    }

    // Render pagination
    function renderPagination() {
        const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
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

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        filteredProducts = products.filter(product => 
            product.product_name.toLowerCase().includes(search) ||
            product.product_color.toLowerCase().includes(search)
        );
        currentPage = 1;
        renderTable();
    });

    // Modal functions
    function openAddModal() {
        isEditMode = false;
        document.getElementById('modalTitle').textContent = 'Tambah Produk';
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('detailRows').innerHTML = '';
        addDetailRow();
        addDetailRow();
        addDetailRow();
        document.getElementById('productModal').classList.add('show');
    }

    function editProduct(id) {
        isEditMode = true;
        const product = products.find(p => p.id === id);
        document.getElementById('modalTitle').textContent = 'Edit Produk';
        document.getElementById('productId').value = product.id;
        document.getElementById('productName').value = product.product_name;
        document.getElementById('productColor').value = product.product_color;
        document.getElementById('productPrice').value = product.product_price;
        
        document.getElementById('detailRows').innerHTML = '';
        product.product_detail.forEach(detail => {
            addDetailRow(detail);
        });
        
        document.getElementById('productModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('productModal').classList.remove('show');
    }

    let detailCounter = 0;
    function addDetailRow(detail = null) {
        const container = document.getElementById('detailRows');
        const row = document.createElement('div');
        row.className = 'detail-row';
        row.id = `detail-${detailCounter}`;
        
        row.innerHTML = `
            <div class="form-group" style="margin-bottom: 0;">
                <select class="detail-size" required>
                    <option value="">Pilih Ukuran</option>
                    <option value="S" ${detail && detail.product_size === 'S' ? 'selected' : ''}>S</option>
                    <option value="M" ${detail && detail.product_size === 'M' ? 'selected' : ''}>M</option>
                    <option value="L" ${detail && detail.product_size === 'L' ? 'selected' : ''}>L</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <input type="number" class="detail-stock" placeholder="Stok" value="${detail ? detail.product_stock : ''}" required min="0">
            </div>
            <button type="button" class="btn-remove-detail" onclick="removeDetailRow(${detailCounter})">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        container.appendChild(row);
        detailCounter++;
    }

    function removeDetailRow(id) {
        const row = document.getElementById(`detail-${id}`);
        if (row) row.remove();
    }

    // Form submit
    document.getElementById('productForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const details = [];
        document.querySelectorAll('.detail-row').forEach(row => {
            const size = row.querySelector('.detail-size').value;
            const stock = row.querySelector('.detail-stock').value;
            if (size && stock) {
                details.push({
                    product_size: size,
                    product_stock: parseInt(stock)
                });
            }
        });

        if (details.length === 0) {
            showAlert('Tambahkan minimal satu detail produk', 'error');
            return;
        }

        const formData = {
            product_name: document.getElementById('productName').value,
            product_color: document.getElementById('productColor').value,
            product_price: parseInt(document.getElementById('productPrice').value),
            details: details
        };

        try {
            const productId = document.getElementById('productId').value;
            let url = '/api/products';
            let method = 'POST';
            
            if (isEditMode && productId) {
                url = `/api/products/${productId}`;
                method = 'PUT';
                delete formData.details; // Edit mode doesn't update details
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();
            
            if (data.success) {
                showAlert(data.message, 'success');
                closeModal();
                loadProducts();
            } else {
                showAlert('Gagal menyimpan produk', 'error');
            }
        } catch (error) {
            showAlert('Terjadi kesalahan', 'error');
        }
    });

    // Alert function
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

    // Initialize
    loadProducts();
</script>
@endsection