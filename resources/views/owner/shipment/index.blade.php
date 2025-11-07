@extends('layouts.owner')

@section('title', 'Data Pengiriman - Owner')
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
</style>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pengiriman</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola semua data pengiriman produk</p>
        </div>
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Staff</th>
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
    let currentPage = 1;
    const itemsPerPage = 5;

    async function fetchShipments() {
        try {
            const response = await fetch('/shipments', {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                shipments = await response.json();
                filteredShipments = [...shipments];
                renderTable();
            }
        } catch (error) {
            console.error('Error fetching shipments:', error);
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
                <td class="px-6 py-4 text-sm text-gray-900">${shipment.user?.name || '-'}</td>
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
                    <button onclick="showDetail(${shipment.id})"
                            class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        Detail
                    </button>
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

        // Previous button
        paginationHTML += `
            <button onclick="changePage(${currentPage - 1})"
                    ${currentPage === 1 ? 'disabled' : ''}
                    class="px-3 py-2 border rounded-lg ${currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                Previous
            </button>
        `;

        // Page numbers
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

        // Next button
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

    async function showDetail(id) {
        const shipment = shipments.find(s => s.id === id);
        if (!shipment) return;

        if (shipment.shipment_detail && shipment.shipment_detail.length > 0) {
            const firstDetail = shipment.shipment_detail[0];
            console.log('Available fields in product_detail:', Object.keys(firstDetail.product_detail || {}));

            // Jika ada product, cek field di product juga
            if (firstDetail.product_detail?.product) {
                console.log('Available fields in product:', Object.keys(firstDetail.product_detail.product));
            }
        }

        const detailContent = document.getElementById('detailContent');
        detailContent.innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <!-- ... bagian lainnya tetap sama ... -->
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

    // Utility functions
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    // Initialize
    fetchShipments();
</script>
@endsection
