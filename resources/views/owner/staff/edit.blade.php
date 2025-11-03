@extends('layouts.owner')

@section('title', 'Edit Staf')
@section('header', 'Ubah Data Staf')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-lg max-w-2xl mx-auto">
    <form action="{{ route('owner.staff.update', $staff->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-semibold mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ $staff->name }}" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" value="{{ $staff->email }}" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">No. Telepon</label>
            <input type="text" name="phone_number" value="{{ $staff->phone_number }}" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" required>
        </div>

        <div>
            <label class="block font-semibold mb-1">Alamat</label>
            <textarea name="address" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach" rows="2">{{ $staff->address }}</textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Password Baru (opsional)</label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-peach">
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-sage text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">Perbarui</button>
            <a href="{{ route('owner.staff.index') }}" class="ml-3 text-charcoal hover:underline">Kembali</a>
        </div>
    </form>
</div>
@endsection
