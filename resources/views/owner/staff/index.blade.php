@extends('layouts.owner')

@section('title', 'Kelola Staff')

@section('header', 'Manajemen Staff')

@section('content')
<div class="staff-container">
    <!-- Toast Notification Container -->
    <div id="toast-container"></div>

    <!-- Header Actions -->
    <div class="header-actions">
        <div class="search-box">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari staff berdasarkan nama, email, telepon, atau lain..." class="search-input">
        </div>
        <button class="btn-add" onclick="openModal('create')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Staff
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon production">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Staff Produksi</div>
                <div class="stat-value">{{ $staff->where('role', 'Production Staff')->count() }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon distribution">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Staff Distribusi</div>
                <div class="stat-value">{{ $staff->where('role', 'Distribution Staff')->count() }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon total">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Staff</div>
                <div class="stat-value">{{ $staff->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="card">
        <div class="card-header">
            <h2>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 28px; height: 28px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Daftar Staff
            </h2>
        </div>

        <div class="table-container">
            @if($staff->isEmpty())
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 64px; height: 64px; color: #9ca3af;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="empty-title">Belum Ada Staff</p>
                <p class="empty-subtitle">Klik tombol "Tambah Staff" untuk menambahkan staff baru</p>
            </div>
            @else
            <table class="staff-table" id="staffTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Role</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $member)
                    <tr class="staff-row">
                        <td>
                            <div class="staff-name">
                                <div class="avatar">{{ substr($member->name, 0, 1) }}</div>
                                <span>{{ $member->name }}</span>
                            </div>
                        </td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->phone_number }}</td>
                        <td>
                            <span class="role-badge {{ $member->role === 'Production Staff' ? 'production' : 'distribution' }}">
                                {{ $member->role }}
                            </span>
                        </td>
                        <td>{{ Str::limit($member->address, 30) }}</td>
                        <td class="action-cell">
                            <button class="btn-icon edit" onclick='openModal("edit", {{ json_encode($member) }})' title="Edit">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            {{-- <button class="btn-icon delete" onclick="deleteStaff({{ $member->id }}, '{{ $member->name }}')" title="Hapus">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

<!-- Modal Create/Edit Staff -->
<div id="staffModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Tambah Staff Baru</h3>
            <button class="modal-close" onclick="closeModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="staffForm" onsubmit="submitStaff(event)" autocomplete="off">
            @csrf
            <input type="hidden" id="staffId" name="staff_id">
            <input type="hidden" id="formMethod" value="POST">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-input" required>
                </div>

                <div class="form-group" id="usernameGroup">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <input type="text" name="address" id="address" class="form-input" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" id="role" class="form-input" required>
                        <option value="">Pilih Role</option>
                        <option value="Production Staff">Staff Produksi</option>
                        <option value="Distribution Staff">Staff Distribusi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span id="passwordHint">(opsional)</span></label>
                    <input type="password" name="password" id="password" class="form-input">
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="submitBtnText">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .staff-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Toast Notification */
    #toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        min-width: 300px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        animation: slideInRight 0.4s ease, fadeOut 0.4s ease 2.6s forwards;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateX(400px);
        }
    }

    .toast-success {
        border-left: 4px solid #10b981;
    }

    .toast-error {
        border-left: 4px solid #ef4444;
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
    }

    .toast-success .toast-icon {
        color: #10b981;
    }

    .toast-error .toast-icon {
        color: #ef4444;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 600;
        margin-bottom: 2px;
        font-size: 0.95rem;
    }

    .toast-success .toast-title {
        color: #065f46;
    }

    .toast-error .toast-title {
        color: #991b1b;
    }

    .toast-message {
        font-size: 0.85rem;
        color: #6b7280;
    }

    /* Header Actions */
    .header-actions {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        align-items: center;
    }

    .search-box {
        flex: 1;
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: #9ca3af;
    }

    .search-input {
        width: 100%;
        padding: 12px 16px 12px 48px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--sage-green);
        box-shadow: 0 0 0 4px rgba(183, 196, 164, 0.1);
    }

    .btn-add {
        display: flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--sage-green) 0%, #8fa87c 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(183, 196, 164, 0.4);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon svg {
        width: 28px;
        height: 28px;
        color: white;
    }

    .stat-icon.production {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .stat-icon.distribution {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .stat-icon.total {
        background: linear-gradient(135deg, var(--sage-green) 0%, #8fa87c 100%);
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--charcoal-gray);
    }

    /* Card & Table */
    .card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--sage-green) 0%, var(--peach) 100%);
        padding: 24px 30px;
        color: var(--charcoal-gray);
    }

    .card-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .table-container {
        overflow-x: auto;
    }

    .staff-table {
        width: 100%;
        border-collapse: collapse;
    }

    .staff-table thead {
        background: #f9fafb;
    }

    .staff-table th {
        padding: 16px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }

    .staff-table td {
        padding: 16px 20px;
        border-top: 1px solid #f3f4f6;
    }

    .staff-row {
        transition: background 0.2s ease;
    }

    .staff-row:hover {
        background: #f9fafb;
    }

    .staff-name {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--sage-green) 0%, var(--peach) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--charcoal-gray);
        font-weight: 700;
        font-size: 1rem;
    }

    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .role-badge.production {
        background: #dbeafe;
        color: #1e40af;
    }

    .role-badge.distribution {
        background: #fef3c7;
        color: #92400e;
    }

    .action-cell {
        text-align: center;
    }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 0 4px;
    }

    .btn-icon svg {
        width: 18px;
        height: 18px;
    }

    .btn-icon.edit {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-icon.edit:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-icon.delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-icon.delete:hover {
        background: #ef4444;
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--charcoal-gray);
        margin: 16px 0 8px;
    }

    .empty-subtitle {
        color: #6b7280;
        font-size: 0.95rem;
    }

    /* Modal */
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
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        margin: 50px auto;
        border-radius: 20px;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        background: linear-gradient(135deg, var(--sage-green) 0%, var(--peach) 100%);
        padding: 24px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--charcoal-gray);
    }

    .modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--charcoal-gray);
        padding: 4px;
        display: flex;
        transition: transform 0.2s;
    }

    .modal-close:hover {
        transform: rotate(90deg);
    }

    .modal-close svg {
        width: 24px;
        height: 24px;
    }

    .modal form {
        padding: 30px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--charcoal-gray);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--sage-green);
        box-shadow: 0 0 0 4px rgba(183, 196, 164, 0.1);
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: var(--charcoal-gray);
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #B7C4A4;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        background-color: #9BB88A;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-save .icon {
        width: 18px;
        height: 18px;
    }

    .text-center {
        text-align: center;
    }

    @media (max-width: 768px) {
        .header-actions {
            flex-direction: column;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .modal-content {
            margin: 20px;
            max-width: calc(100% - 40px);
        }
    }
</style>
@endsection

@section('scripts')
<script>
    function showToast(type, title, message) {
        const container = document.getElementById('toast-container');

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        const iconSvg = type === 'success'
            ? '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            : '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

        toast.innerHTML = `
            ${iconSvg}
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }

    function openModal(type, data = null) {
        const modal = document.getElementById('staffModal');
        const form = document.getElementById('staffForm');
        const modalTitle = document.getElementById('modalTitle');
        const submitText = document.getElementById('submitBtnText');
        const passwordHint = document.getElementById('passwordHint');
        const methodInput = document.getElementById('formMethod');
        const idField = document.getElementById('staffId');

        form.reset();
        modal.style.display = 'block';

        if (type === 'create') {
            modalTitle.textContent = 'Tambah Staff Baru';
            submitText.textContent = 'Simpan';
            passwordHint.textContent = '(wajib)';
            methodInput.value = 'POST';
            idField.value = '';
        } else {
            modalTitle.textContent = 'Edit Staff';
            submitText.textContent = 'Perbarui';
            passwordHint.textContent = '(kosongkan jika tidak diubah)';
            methodInput.value = 'PUT';
            idField.value = data.id;


            document.getElementById('name').value = data.name;
            document.getElementById('username').value = data.username ?? '';
            document.getElementById('email').value = data.email;
            document.getElementById('phone_number').value = data.phone_number;
            document.getElementById('address').value = data.address;
            document.getElementById('role').value = data.role;
        }
    }

    function closeModal() {
        document.getElementById('staffModal').style.display = 'none';
    }

    window.onclick = function (event) {
        const modal = document.getElementById('staffModal');
        if (event.target === modal) modal.style.display = 'none';
    };

    async function submitStaff(event) {
        event.preventDefault();

        const form = document.getElementById('staffForm');
        const method = document.getElementById('formMethod').value;
        const id = document.getElementById('staffId').value;
        const formData = new FormData(form);

        const url = method === 'POST'
            ? "{{ route('owner.staff.store') }}"
            : `/owner/staff/${id}`;

        const btn = form.querySelector('.btn-save');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="icon animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg> Menyimpan...
        `;

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const result = await response.json();
            btn.disabled = false;
            btn.innerHTML = originalText;

            if (response.ok) {
                showToast('success', 'Berhasil', result.message ?? 'Data staff berhasil disimpan.');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('error', 'Gagal', result.message ?? 'Terjadi kesalahan saat menyimpan data.');
            }
        } catch (error) {
            btn.disabled = false;
            btn.innerHTML = originalText;
            showToast('error', 'Kesalahan', 'Tidak dapat terhubung ke server.');
        }
    }

    async function deleteStaff(id, name) {
        if (!confirm(`Apakah Anda yakin ingin menghapus staff "${name}"?`)) return;

        try {
            const response = await fetch(`/owner/staff/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (response.ok) {
                showToast('success', 'Dihapus', result.message ?? 'Staff berhasil dihapus.');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('error', 'Gagal', result.message ?? 'Gagal menghapus staff.');
            }
        } catch (error) {
            showToast('error', 'Kesalahan', 'Tidak dapat terhubung ke server.');
        }
    }

    document.getElementById('searchInput')?.addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#staffTable tbody tr');

        rows.forEach(row => {
            const name = row.children[0].innerText.toLowerCase();
            const email = row.children[1].innerText.toLowerCase();
            const phone = row.children[2].innerText.toLowerCase();
            const role = row.children[3].innerText.toLowerCase();
            row.style.display = name.includes(query) || email.includes(query) || phone.includes(query) || role.includes(query) ? '' : 'none';
        });
    });

    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes spin { to { transform: rotate(360deg); } }
        .animate-spin { animation: spin 1s linear infinite; }
    `;
    document.head.appendChild(style);
</script>
@endsection
