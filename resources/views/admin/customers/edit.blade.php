@extends('layouts.admin')

@section('title', 'Edit Customer')
@section('header', 'Edit Customer')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.customers.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke daftar
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <div class="p-6">
        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Customer</label>
                <input type="text" name="customer_name" id="customer_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('customer_name') border-red-500 @enderror" value="{{ old('customer_name', $customer->customer_name) }}" required maxlength="50">
                @error('customer_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="phone" id="phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('phone') border-red-500 @enderror" value="{{ old('phone', $customer->phone) }}" required maxlength="13">
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="address" id="address" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('address') border-red-500 @enderror" required>{{ old('address', $customer->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition-colors cursor-pointer">
                    Perbarui Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
