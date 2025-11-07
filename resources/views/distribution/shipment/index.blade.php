@extends('layouts.distribution')

@section('title', 'Data Pengiriman - Distribusi')
@section('header', 'Data Pengiriman')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 50;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Custom dropdown with search */
    .custom-select-wrapper {
        position: relative;
    }

    .custom-select-search {
        width: 100%;
        padding: 0.5rem 2.5rem 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        cursor: pointer;
    }

    .custom-select-search:focus {
        outline: none;
        ring: 2px;
        ring-color: #10b981;
        border-color: transparent;
    }

    .custom-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        max-height: 250px;
        overflow-y: auto;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        margin-top: 0.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 100;
    }

    .custom-dropdown.show {
        display: block;
    }

    .custom-dropdown-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
    }

    .custom-dropdown-item:hover {
        background-color: #f9fafb;
    }

    .custom-dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-arrow {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }
</style>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pengiriman</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data pengiriman Anda</p>
        </div>
        <button onclick="openModal('createModal')" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-lg font-medium flex items-center gap-2 transition-all shadow-sm hover:shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengiriman
        </button>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 flex gap-4">
        <div class="flex-1">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan tujuan atau status..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>
        <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <option value="">Semua Status</option>
            <option value="Dalam Pengiriman">Dalam Pengiriman</option>
            <option value="Sampai Tujuan">Sampai Tujuan</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tujuan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="shipmentTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-600">
            Menampilkan <span id="showingStart">0</span> - <span id="showingEnd">0</span> dari <span id="totalRecords">0</span> data
        </div>
        <div class="flex gap-2" id="paginationContainer">
            <!-- Pagination buttons akan diisi oleh JavaScript -->
        </div>
    </div>
</div>

<!-- Modal Create -->
<div id="createModal" class="modal">
    <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Tambah Pengiriman Baru</h3>
            <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="createForm" class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pengiriman *</label>
                    <input type="date" name="shipment_date" required readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Harga *</label>
                    <input type="number" name="total_price" required readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Tujuan *</label>
                <textarea name="destination_address" required rows="2"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
            </div>

            <div>
                <div class="flex justify-between items-center mb-3">
                    <label class="block text-sm font-semibold text-gray-700">Detail Produk *</label>
                    <button type="button" onclick="addProductRow()" class="text-green-600 hover:text-green-700 text-sm font-medium">
                        + Tambah Produk
                    </button>
                </div>
                <div id="productRows" class="space-y-3">
                    <!-- Product rows akan ditambahkan di sini -->
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2.5 rounded-lg font-medium transition-all">
                    Simpan
                </button>
                <button type="button" onclick="closeModal('createModal')" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium transition-all">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="modal">
    <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Edit Pengiriman</h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="editForm" class="p-6 space-y-4">
            <input type="hidden" name="id">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Tujuan</label>
                <textarea name="destination_address" rows="2"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Total Harga</label>
                <input type="number" name="total_price"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2.5 rounded-lg font-medium transition-all">
                    Update
                </button>
                <button type="button" onclick="closeModal('editModal')" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium transition-all">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="modal">
    <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Detail Pengiriman</h3>
            <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div id="detailContent" class="space-y-4">
                <!-- Detail akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let shipments = [];
    let filteredShipments = [];
    let products = [];
    let currentPage = 1;
    const itemsPerPage = 5;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    async function fetchShipments() {
        try {
            console.log('Fetching shipments from:', '/shipments');
            const response = await fetch('/shipments', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            });

            console.log('Response status:', response.status);

            if (response.ok) {
                shipments = await response.json();
                console.log('Shipments data:', shipments);
                filteredShipments = [...shipments];
                renderTable();
            } else {
                console.error('Failed to fetch shipments:', response.statusText);
            }
        } catch (error) {
            console.error('Error fetching shipments:', error);
        }
    }

    async function fetchProducts() {
        try {
            const response = await fetch('/products', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const result = await response.json();
                console.log('Raw API response:', result);

                // Akses property 'data' dari response
                const data = result.data || result;
                console.log('Products array:', data);

                // Transform products to include all size variants
                products = [];

                if (Array.isArray(data)) {
                    data.forEach(product => {
                        console.log('Processing product:', product);

                        if (product.product_detail) {
                            let detailArray = [];

                            if (typeof product.product_detail === 'object' && !Array.isArray(product.product_detail)) {
                                detailArray = Object.values(product.product_detail);
                            }
                            // Jika sudah array, gunakan langsung
                            else if (Array.isArray(product.product_detail)) {
                                detailArray = product.product_detail;
                            }

                            console.log('Detail array:', detailArray);

                            // Process each detail dengan validasi
                            detailArray.forEach(detail => {
                                // Validasi detail memiliki properti yang diperlukan
                                if (detail && typeof detail === 'object') {
                                    products.push({
                                        id: product.id || 0,
                                        name: product.product_name || 'Unknown',
                                        color: product.product_color || 'Unknown',
                                        size: detail.product_size || 'N/A',
                                        price: product.product_price || 0,
                                        stock: detail.product_stock || 0
                                    });
                                } else {
                                    console.warn('Invalid detail object:', detail);
                                }
                            });
                        } else {
                            console.warn('Product missing product_detail:', product);
                        }
                    });
                } else {
                    console.error('Data is not an array:', data);
                }

                console.log('Transformed products:', products);
                console.log('Total products with variants:', products.length);
            } else {
                const errorText = await response.text();
                console.error('Failed to fetch products:', response.statusText, errorText);
            }
        } catch (error) {
            console.error('Error fetching products:', error);
        }
    }

    // Render tabel
    function renderTable() {
        const tbody = document.getElementById('shipmentTableBody');
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filteredShipments.slice(start, end);

        tbody.innerHTML = paginatedData.map((shipment, index) => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-900">${start + index + 1}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${formatDate(shipment.shipment_date)}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${shipment.destination_address}</td>
                <td class="px-6 py-4 text-sm text-gray-900">Rp ${formatPrice(shipment.total_price)}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${
                        shipment.shipment_status === 'Sampai Tujuan'
                        ? 'bg-green-100 text-green-800'
                        : 'bg-yellow-100 text-yellow-800'
                    }">
                        ${shipment.shipment_status}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-2">
                        <button onclick="showDetail(${shipment.id})"
                                class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Detail
                        </button>
                        ${shipment.shipment_status === 'Dalam Pengiriman' ? `
                            <button onclick="markAsDelivered(${shipment.id})"
                                    class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                                Selesai
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');

        updatePagination();
    }

    // Update pagination
    function updatePagination() {
        const totalPages = Math.ceil(filteredShipments.length / itemsPerPage);
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(currentPage * itemsPerPage, filteredShipments.length);

        document.getElementById('showingStart').textContent = filteredShipments.length > 0 ? start : 0;
        document.getElementById('showingEnd').textContent = end;
        document.getElementById('totalRecords').textContent = filteredShipments.length;

        const paginationContainer = document.getElementById('paginationContainer');
        let paginationHTML = '';

        paginationHTML += `
            <button onclick="changePage(${currentPage - 1})"
                    ${currentPage === 1 ? 'disabled' : ''}
                    class="px-3 py-2 border rounded-lg ${currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                Previous
            </button>
        `;

        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                paginationHTML += `
                    <button onclick="changePage(${i})"
                            class="px-4 py-2 border rounded-lg ${i === currentPage ? 'bg-green-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                        ${i}
                    </button>
                `;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                paginationHTML += '<span class="px-2">...</span>';
            }
        }

        paginationHTML += `
            <button onclick="changePage(${currentPage + 1})"
                    ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}
                    class="px-3 py-2 border rounded-lg ${currentPage === totalPages || totalPages === 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                Next
            </button>
        `;

        paginationContainer.innerHTML = paginationHTML;
    }

    function changePage(page) {
        const totalPages = Math.ceil(filteredShipments.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderTable();
        }
    }

    // Add product row with searchable select
    function addProductRow() {
        const productRows = document.getElementById('productRows');
        const rowIndex = productRows.children.length;

        const row = document.createElement('div');
        row.className = 'flex gap-3 items-start';
        row.dataset.rowIndex = rowIndex;
        row.dataset.productPrice = 0;

        // Create wrapper div
        const wrapper = document.createElement('div');
        wrapper.className = 'flex-1 custom-select-wrapper';

        // Create search input
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'custom-select-search';
        searchInput.placeholder = 'Cari produk...';
        searchInput.addEventListener('keyup', function() {
            filterProducts(this, rowIndex);
        });
        searchInput.addEventListener('click', function() {
            showDropdown(rowIndex);
        });

        // Create hidden inputs
        const hiddenId = document.createElement('input');
        hiddenId.type = 'hidden';
        hiddenId.name = `product_details[${rowIndex}][product_id]`;

        const hiddenSize = document.createElement('input');
        hiddenSize.type = 'hidden';
        hiddenSize.name = `product_details[${rowIndex}][product_size]`;

        // Create arrow icon
        const arrow = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        arrow.setAttribute('class', 'dropdown-arrow w-4 h-4 text-gray-400');
        arrow.setAttribute('fill', 'none');
        arrow.setAttribute('stroke', 'currentColor');
        arrow.setAttribute('viewBox', '0 0 24 24');
        const arrowPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        arrowPath.setAttribute('stroke-linecap', 'round');
        arrowPath.setAttribute('stroke-linejoin', 'round');
        arrowPath.setAttribute('stroke-width', '2');
        arrowPath.setAttribute('d', 'M19 9l-7 7-7-7');
        arrow.appendChild(arrowPath);

        // Create dropdown
        const dropdown = document.createElement('div');
        dropdown.className = 'custom-dropdown';
        dropdown.id = `dropdown-${rowIndex}`;

        // Populate dropdown with products
        products.forEach((p, idx) => {
            const item = document.createElement('div');
            item.className = 'custom-dropdown-item';
            item.addEventListener('click', function() {
                selectProduct(rowIndex, p.id, p.name, p.color, p.size, p.price);
            });

            item.innerHTML = `
                <div class="font-medium text-sm text-gray-900">${p.name}</div>
                <div class="text-xs text-gray-500">Warna: ${p.color} | Ukuran: ${p.size} | Rp ${formatPrice(p.price)}</div>
                <div class="text-xs text-gray-400">Stok: ${p.stock}</div>
            `;

            dropdown.appendChild(item);
        });

        // Append all elements to wrapper
        wrapper.appendChild(searchInput);
        wrapper.appendChild(hiddenId);
        wrapper.appendChild(hiddenSize);
        wrapper.appendChild(arrow);
        wrapper.appendChild(dropdown);

        // Create quantity input
        const qtyInput = document.createElement('input');
        qtyInput.type = 'number';
        qtyInput.name = `product_details[${rowIndex}][quantity]`;
        qtyInput.placeholder = 'Qty';
        qtyInput.required = true;
        qtyInput.min = '1';
        qtyInput.className = 'w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm';
        qtyInput.addEventListener('change', function() {
            updateSubtotal(rowIndex);
        });

        // Create subtotal input
        const subTotalInput = document.createElement('input');
        subTotalInput.type = 'number';
        subTotalInput.name = `product_details[${rowIndex}][sub_total]`;
        subTotalInput.placeholder = 'Subtotal';
        subTotalInput.readOnly = true;
        subTotalInput.className = 'w-32 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm';

        // Create delete button
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'text-red-600 hover:text-red-800';
        deleteBtn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        `;
        deleteBtn.addEventListener('click', function() {
            removeProductRow(this);
        });

        // Append all to row
        row.appendChild(wrapper);
        row.appendChild(qtyInput);
        row.appendChild(subTotalInput);
        row.appendChild(deleteBtn);

        productRows.appendChild(row);
    }

    function removeProductRow(button) {
        button.closest('.flex').remove();
        calculateTotal();
    }

    function showDropdown(rowIndex) {
        // Close all other dropdowns first
        document.querySelectorAll('.custom-dropdown').forEach(dd => {
            dd.classList.remove('show');
        });

        const dropdown = document.getElementById(`dropdown-${rowIndex}`);
        if (dropdown) {
            dropdown.classList.add('show');
        }
    }

    function filterProducts(input, rowIndex) {
        const searchTerm = input.value.toLowerCase();
        const dropdown = document.getElementById(`dropdown-${rowIndex}`);

        if (!dropdown) return;

        const items = dropdown.querySelectorAll('.custom-dropdown-item');

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });

        dropdown.classList.add('show');
    }

    function selectProduct(rowIndex, productId, name, color, size, price) {
        const rows = document.querySelectorAll('#productRows > div');
        const row = rows[rowIndex];

        if (!row) return;

        const searchInput = row.querySelector('.custom-select-search');
        const hiddenIdInput = row.querySelector('input[name*="[product_id]"]');
        const hiddenSizeInput = row.querySelector('input[name*="[product_size]"]');
        const dropdown = document.getElementById(`dropdown-${rowIndex}`);

        if (searchInput) searchInput.value = `${name} - ${color} - ${size}`;
        if (hiddenIdInput) hiddenIdInput.value = productId;
        if (hiddenSizeInput) hiddenSizeInput.value = size;
        row.dataset.productPrice = price;

        if (dropdown) dropdown.classList.remove('show');

        updateSubtotal(rowIndex);
    }

    function updateSubtotal(rowIndex) {
        const rows = document.querySelectorAll('#productRows > div');
        const row = rows[rowIndex];

        if (!row) return;

        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        const subtotalInput = row.querySelector('input[name*="[sub_total]"]');

        const price = parseFloat(row.dataset.productPrice || 0);
        const quantity = parseInt(quantityInput?.value || 0);

        if (subtotalInput) {
            subtotalInput.value = price * quantity;
        }

        calculateTotal();
    }

    function calculateTotal() {
        const subtotals = document.querySelectorAll('input[name*="[sub_total]"]');
        let total = 0;
        subtotals.forEach(input => {
            total += parseFloat(input.value || 0);
        });
        document.querySelector('input[name="total_price"]').value = total;
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select-wrapper')) {
            document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    // Create shipment
    document.getElementById('createForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        // Validasi minimal satu produk
        const productRows = document.querySelectorAll('#productRows > div');
        if (productRows.length === 0) {
            alert('Harap tambahkan minimal satu produk!');
            return;
        }

        const formData = new FormData(e.target);
        const productDetails = [];

        productRows.forEach((row, index) => {
            const productId = formData.get(`product_details[${index}][product_id]`);
            const quantity = formData.get(`product_details[${index}][quantity]`);
            const subTotal = formData.get(`product_details[${index}][sub_total]`);
            const productSize = formData.get(`product_details[${index}][product_size]`);

            if (productId && quantity && subTotal) {
                productDetails.push({
                    product_id: parseInt(productId),
                    product_size: productSize,
                    quantity: parseInt(quantity),
                    sub_total: parseInt(subTotal)
                });
            }
        });

        if (productDetails.length === 0) {
            alert('Harap pilih produk dan isi quantity!');
            return;
        }

        const data = {
            shipment_date: formData.get('shipment_date'),
            destination_address: formData.get('destination_address'),
            total_price: parseInt(formData.get('total_price')),
            product_details: productDetails
        };

        console.log('Sending shipment data:', data);

        try {
            const response = await fetch('/shipments', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            console.log('Create shipment response:', result);

            if (response.ok) {
                alert('Pengiriman berhasil ditambahkan!');
                closeModal('createModal');
                e.target.reset();
                document.getElementById('productRows').innerHTML = '';
                fetchShipments();
            } else {
                alert('Gagal menambahkan pengiriman: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error creating shipment:', error);
            alert('Gagal menambahkan pengiriman!');
        }
    });

    // Show edit modal
    // function showEditModal(id) {
    //     const shipment = shipments.find(s => s.id === id);
    //     if (!shipment || shipment.shipment_status !== 'Dalam Pengiriman') return;

    //     const form = document.getElementById('editForm');
    //     form.querySelector('input[name="id"]').value = shipment.id;
    //     form.querySelector('textarea[name="destination_address"]').value = shipment.destination_address;
    //     form.querySelector('input[name="total_price"]').value = shipment.total_price;

    //     openModal('editModal');
    // }

    // Edit shipment
    document.getElementById('editForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const id = formData.get('id');

        const data = {
            destination_address: formData.get('destination_address'),
            total_price: parseInt(formData.get('total_price'))
        };

        try {
            const response = await fetch(`/shipments/${id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                alert('Pengiriman berhasil diupdate!');
                closeModal('editModal');
                fetchShipments();
            } else {
                alert('Gagal mengupdate pengiriman!');
            }
        } catch (error) {
            console.error('Error updating shipment:', error);
            alert('Gagal mengupdate pengiriman!');
        }
    });

    // Mark as delivered
    async function markAsDelivered(id) {
        if (!confirm('Tandai pengiriman ini sudah sampai tujuan?')) return;

        try {
            const response = await fetch(`/shipments/${id}/delivered`, {
                method: 'PATCH',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (response.ok) {
                alert('Status berhasil diupdate!');
                fetchShipments();
            } else {
                alert('Gagal mengupdate status!');
            }
        } catch (error) {
            console.error('Error marking as delivered:', error);
            alert('Gagal mengupdate status!');
        }
    }

    // Show detail modal
    function showDetail(id) {
        const shipment = shipments.find(s => s.id === id);
        if (!shipment) return;

        const detailContent = document.getElementById('detailContent');
        detailContent.innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600">Tanggal Pengiriman</label>
                    <p class="text-gray-900 mt-1">${formatDate(shipment.shipment_date)}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600">Status</label>
                    <p class="mt-1">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${
                            shipment.shipment_status === 'Sampai Tujuan'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-yellow-100 text-yellow-800'
                        }">
                            ${shipment.shipment_status}
                        </span>
                    </p>
                </div>
                <div class="col-span-2">
                    <label class="text-sm font-semibold text-gray-600">Alamat Tujuan</label>
                    <p class="text-gray-900 mt-1">${shipment.destination_address}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-600">Total Harga</label>
                    <p class="text-gray-900 mt-1 font-bold">Rp ${formatPrice(shipment.total_price)}</p>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="text-sm font-semibold text-gray-600 mb-3">Detail Produk</h4>
                <div class="border rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Produk</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Warna</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Ukuran</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Jumlah</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            ${shipment.shipment_detail?.map(detail => {
                                const productName = detail.product_detail?.product?.product_name || '-';
                                const productColor = detail.product_detail?.product?.product_color || '-';

                                return `
                                    <tr>
                                        <td class="px-4 py-2 text-sm">${productName}</td>
                                        <td class="px-4 py-2 text-sm">${productColor}</td>
                                        <td class="px-4 py-2 text-sm">${detail.product_detail?.product_size || '-'}</td>
                                        <td class="px-4 py-2 text-sm">${detail.product_quantity}</td>
                                        <td class="px-4 py-2 text-sm">Rp ${formatPrice(detail.sub_total)}</td>
                                    </tr>
                                `;
                            }).join('') || '<tr><td colspan="5" class="px-4 py-2 text-sm text-center text-gray-500">Tidak ada detail produk</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>
        `;

        openModal('detailModal');
    }

    // Search & Filter
    document.getElementById('searchInput').addEventListener('input', filterData);
    document.getElementById('statusFilter').addEventListener('change', filterData);

    function filterData() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;

        filteredShipments = shipments.filter(shipment => {
            const matchSearch = shipment.destination_address.toLowerCase().includes(searchTerm) ||
                              shipment.shipment_status.toLowerCase().includes(searchTerm);
            const matchStatus = !statusFilter || shipment.shipment_status === statusFilter;

            return matchSearch && matchStatus;
        });

        currentPage = 1;
        renderTable();
    }

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
        if (modalId === 'createModal') {
            document.getElementById('productRows').innerHTML = '';
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="shipment_date"]').value = today;
            addProductRow();
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        fetchProducts().then(() => {
            fetchShipments();
        });
    });
</script>
@endsection
