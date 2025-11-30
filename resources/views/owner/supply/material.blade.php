{{-- views/owner/material.blade.php --}}
@extends('layouts.owner')

@section('title', 'Manajemen Material - Owner')
@section('header', 'Manajemen Material')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fefefe;
        padding: 30px;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .btn-primary {
        background: linear-gradient(135deg, #B7C4A4 0%, #8FA87D 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

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

    .search-box {
        position: relative;
        flex-grow: 1;
        max-width: 400px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: #9CA3AF;
    }

    .search-input {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #8FA87D;
        box-shadow: 0 0 0 3px rgba(143, 168, 125, 0.1);
    }
</style>

@section('content')
<div class="space-y-6">
    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Action Buttons -->
    <div class="flex gap-4 flex-wrap">
        <div class="search-box">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari nama material atau warna..." class="search-input">
        </div>
        <button onclick="openAddMaterialModal()" class="btn-primary">
            <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Material Baru
        </button>
        <button onclick="openProcurementModal()" class="btn-primary">
            <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Buat Pengadaan
        </button>
    </div>

    <!-- Materials Table -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Daftar Material</h2>
        <div class="table-container">
            <table id="materialsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Material</th>
                        <th>Warna</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="pagination" class="flex justify-center mt-4 space-x-2"></div>

</div>

<!-- Modal Add Material -->
<div id="addMaterialModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Tambah Material Baru</h3>
        <form id="addMaterialForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Material</label>
                <input type="text" name="material_name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                <input type="text" name="material_color" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok</label>
                <input type="number" name="material_quantity" required min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex gap-3 justify-end mt-6">
                <button type="button" onclick="closeModal('addMaterialModal')"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Material -->
<div id="editMaterialModal" class="modal">
    <div class="modal-content">
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Edit Material</h3>
        <form id="editMaterialForm" class="space-y-4">
            <input type="hidden" name="material_id" id="edit_material_id">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Material</label>
                <input type="text" name="material_name" id="edit_material_name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                <input type="text" name="material_color" id="edit_material_color" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok</label>
                <input type="number" name="material_quantity" id="edit_material_quantity" required min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="flex gap-3 justify-end mt-6">
                <button type="button" onclick="closeModal('editMaterialModal')"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Procurement -->
<div id="procurementModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <h3 class="text-2xl font-bold mb-6 text-gray-800">Buat Pengadaan Material</h3>
        <form id="procurementForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengadaan</label>
                <input type="date" name="procurement_date" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Biaya</label>
                <input type="number" name="total_cost" required min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="Contoh: 1000000">
            </div>

            <div class="border-t pt-4 mt-4">
                <div class="flex justify-between items-center mb-4">
                    <label class="block text-sm font-medium text-gray-700">Daftar Material</label>
                    <button type="button" onclick="addMaterialRow()"
                            class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded-lg hover:bg-green-200 transition">
                        + Tambah Material
                    </button>
                </div>
                <div id="materialRows" class="space-y-3">
                    <!-- Material rows will be added here -->
                </div>
            </div>

            <div class="flex gap-3 justify-end mt-6">
                <button type="button" onclick="closeModal('procurementModal')"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    Buat Pengadaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let materials = [];
    let currentPage = 1;
    let lastPage = 1;

    const apiUrl = '/materials';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Load materials on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadMaterials();

        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="procurement_date"]').value = today;
    });

    // Load materials from API
    async function loadMaterials(page = 1) {
        try {
            const response = await fetch(`${apiUrl}?page=${page}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            const data = await response.json();

            if (data.success) {
                materials = data.data;
                currentPage = data.pagination.current_page;
                lastPage = data.pagination.last_page;
                renderMaterialsTable();
                renderPagination(); // tambahkan ini
            }
        } catch (error) {
            showAlert('Gagal memuat data material', 'danger');
            console.error('Error:', error);
        }
    }

    document.getElementById('searchInput')?.addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#materialsTable tbody tr');

        rows.forEach(row => {
            const material = row.children[1].innerText.toLowerCase();
            const warna = row.children[2].innerText.toLowerCase();
            row.style.display = material.includes(query) || warna.includes(query) ? '' : 'none';
        });
    });

    // Render materials table
    function renderMaterialsTable() {
        const tbody = document.querySelector('#materialsTable tbody');

        if (materials.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">Tidak ada data material</td></tr>';
            return;
        }

        tbody.innerHTML = materials.map(material => `
            <tr>
                <td class="font-medium">${material.id}</td>
                <td>${material.material_name}</td>
                <td>${material.material_color}</td>
                <td class="font-semibold">${material.material_quantity}</td>
                <td>${getStockBadge(material.material_quantity)}</td>
                <td>
                    <button onclick="openEditModal(${material.id})"
                            class="text-blue-600 hover:text-blue-800 font-medium mr-3">
                        Edit
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // Get stock status badge
    function getStockBadge(quantity) {
        if (quantity > 100) {
            return '<span class="badge badge-success">Stok Aman</span>';
        } else if (quantity > 50) {
            return '<span class="badge badge-warning">Stok Sedang</span>';
        } else {
            return '<span class="badge badge-danger">Stok Rendah</span>';
        }
    }

    // Modal functions
    function openAddMaterialModal() {
        document.getElementById('addMaterialModal').classList.add('show');
    }

    function openEditModal(id) {
        const material = materials.find(m => m.id === id);
        if (material) {
            document.getElementById('edit_material_id').value = material.id;
            document.getElementById('edit_material_name').value = material.material_name;
            document.getElementById('edit_material_color').value = material.material_color;
            document.getElementById('edit_material_quantity').value = material.material_quantity;
            document.getElementById('editMaterialModal').classList.add('show');
        }
    }

    function openProcurementModal() {
        document.getElementById('materialRows').innerHTML = '';
        addMaterialRow();
        document.getElementById('procurementModal').classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    // Add material row for procurement
    function addMaterialRow() {
        const container = document.getElementById('materialRows');
        const row = document.createElement('div');
        row.className = 'flex gap-3 items-start bg-gray-50 p-3 rounded-lg';
        row.innerHTML = `
            <div class="flex-1">
                <select name="materials[][material_id]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                    <option value="">Pilih Material</option>
                    ${materials.map(m => `<option value="${m.id}">${m.material_name} - ${m.material_color}</option>`).join('')}
                </select>
            </div>
            <div class="w-32">
                <input type="number" name="materials[][quantity]" required min="1" placeholder="Jumlah"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
            </div>
            <button type="button" onclick="this.parentElement.remove()"
                    class="text-red-600 hover:text-red-800 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        container.appendChild(row);
    }

    // Form submissions
    document.getElementById('addMaterialForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showAlert(data.message, 'success');
                closeModal('addMaterialModal');
                this.reset();
                loadMaterials();
            } else {
                showAlert('Gagal menambahkan material', 'danger');
            }
        } catch (error) {
            showAlert('Terjadi kesalahan', 'danger');
            console.error('Error:', error);
        }
    });

    document.getElementById('editMaterialForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('edit_material_id').value;
        const formData = new FormData(this);

        try {
            const response = await fetch(`${apiUrl}/${id}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showAlert(data.message, 'success');
                closeModal('editMaterialModal');
                loadMaterials();
            } else {
                showAlert('Gagal mengupdate material', 'danger');
            }
        } catch (error) {
            showAlert('Terjadi kesalahan', 'danger');
            console.error('Error:', error);
        }
    });

    document.getElementById('procurementForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const materials = [];

        // Collect material data
        const materialSelects = document.querySelectorAll('select[name="materials[][material_id]"]');
        const quantityInputs = document.querySelectorAll('input[name="materials[][quantity]"]');

        for (let i = 0; i < materialSelects.length; i++) {
            materials.push({
                material_id: parseInt(materialSelects[i].value),
                quantity: parseInt(quantityInputs[i].value)
            });
        }

        const payload = {
            procurement_date: formData.get('procurement_date'),
            total_cost: parseInt(formData.get('total_cost')),
            materials: materials
        };

        try {
            const response = await fetch('/procurements', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (data.success) {
                showAlert(data.message, 'success');
                closeModal('procurementModal');
                this.reset();
                loadMaterials();
            } else {
                showAlert('Gagal membuat pengadaan', 'danger');
            }
        } catch (error) {
            showAlert('Terjadi kesalahan', 'danger');
            console.error('Error:', error);
        }
    });

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
                <p class="font-medium">${message}</p>
                <button onclick="this.parentElement.parentElement.remove()" class="text-lg font-bold ml-4">&times;</button>
            </div>
        `;

        alertContainer.appendChild(alert);

        setTimeout(() => {
            alert.remove();
        }, 5000);
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    }

    function renderPagination() {
        const container = document.getElementById('pagination');
        container.innerHTML = '';

        if (lastPage <= 1) return;

        // Tombol Previous
        const prevBtn = document.createElement('button');
        prevBtn.textContent = '← Sebelumnya';
        prevBtn.className = 'px-3 py-1 rounded border border-gray-300 hover:bg-gray-100';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => loadMaterials(currentPage - 1);
        container.appendChild(prevBtn);

        // Nomor halaman
        for (let i = 1; i <= lastPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.textContent = i;
            pageBtn.className =
                `px-3 py-1 rounded border ${i === currentPage
                    ? 'bg-green-600 text-white'
                    : 'hover:bg-gray-100'}`;
            pageBtn.onclick = () => loadMaterials(i);
            container.appendChild(pageBtn);
        }

        // Tombol Next
        const nextBtn = document.createElement('button');
        nextBtn.textContent = 'Berikutnya →';
        nextBtn.className = 'px-3 py-1 rounded border border-gray-300 hover:bg-gray-100';
        nextBtn.disabled = currentPage === lastPage;
        nextBtn.onclick = () => loadMaterials(currentPage + 1);
        container.appendChild(nextBtn);
    }

</script>
@endsection
