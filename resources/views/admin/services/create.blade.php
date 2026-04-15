@extends('layouts.admin')

@section('title', 'Tambah Jasa')
@section('header', 'Tambah Jasa Layanan Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.services.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke daftar
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <div class="p-6">
        <form action="{{ route('admin.services.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="service_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Jasa</label>
                <input type="text" name="service_name" id="service_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('service_name') border-red-500 @enderror" value="{{ old('service_name') }}" required maxlength="50">
                @error('service_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" id="price" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('price') border-red-500 @enderror" value="{{ old('price') }}" required>
                @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition-colors cursor-pointer">
                    Simpan Jasa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
