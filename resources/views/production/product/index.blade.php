@extends('layouts.production')

@section('title', 'Daftar Produk')
@section('header', 'Daftar Produk')

@section('styles')
<style>
    .content-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .search-box {
        margin-bottom: 24px;
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
</style>
@endsection

@section('content')
<div class="content-card">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Cari produk...">
        <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
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
                </tr>
            </thead>
            <tbody id="productTableBody">
                <tr>
                    <td colspan="5" class="empty-state">
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
@endsection

@section('scripts')
<script>
    let products = [];
    let filteredProducts = [];
    let currentPage = 1;
    const itemsPerPage = 10;

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
            console.error('Error loading products');
        }
    }

    function renderTable() {
        const tbody = document.getElementById('productTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredProducts.slice(start, end);

        if (paginatedData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="empty-state">
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
                    </tr>
                `;
            }).join('');
        }

        renderPagination();
    }

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

    document.getElementById('searchInput').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        filteredProducts = products.filter(product =>
            product.product_name.toLowerCase().includes(search) ||
            product.product_color.toLowerCase().includes(search)
        );
        currentPage = 1;
        renderTable();
    });

    loadProducts();
</script>
@endsection
