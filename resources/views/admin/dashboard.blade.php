@extends('layouts.admin')

@section('title', 'Dashboard Administrator')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-indigo-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                <i class="fas fa-users fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Total Customers</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['customers'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <i class="fas fa-user-tie fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['users'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                <i class="fas fa-box fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Layanan Jasa</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['services'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                <i class="fas fa-shopping-cart fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Total Transaksi</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['orders'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Selamat Datang di Sistem Informasi Laundry</h3>
    <p class="text-gray-600">Anda login sebagai <strong>{{ Auth::user()->name }}</strong> (Administrator). Gunakan menu di sidebar untuk mengelola master data.</p>
</div>
@endsection
