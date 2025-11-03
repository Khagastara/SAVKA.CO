@extends('layouts.owner')

@section('title', 'Tambah Staf')
@section('header', 'Tambah Staf Baru')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-lg max-w-2xl mx-auto">
    <form action="{{ route('owner.staff.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Username</label>
            <input type="text" name="username" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Nama Lengkap</label>
            <input type="text" name="name" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">No. Telepon</label>
            <input type="text" name="phone_number" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Alamat</label>
            <textarea name="address" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" rows="2"></textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Role</label>
            <select name="role" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" required>
                <option value="">Pilih Role</option>
                <option value="Production Staff">Staf Produksi</option>
                <option value="Distribution Staff">Staf Distribusi</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Password</label>
            <input type="password" name="password" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" required>
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-sage text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">Simpan</button>
            <a href="{{ route('owner.staff.index') }}" class="ml-3 text-charcoal hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
