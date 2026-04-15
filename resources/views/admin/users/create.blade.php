@extends('layouts.admin')

@section('title', 'Tambah Operator')
@section('header', 'Tambah Operator Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke daftar
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <div class="p-6">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="id_level" value="{{ $levelOperator->id }}">
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Operator</label>
                <input type="text" name="name" id="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror" value="{{ old('name') }}" required maxlength="50">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror" value="{{ old('email') }}" required maxlength="50">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition-colors cursor-pointer">
                    Simpan Operator
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
