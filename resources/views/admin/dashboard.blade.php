@extends('layouts.admin')

@section('title', 'Dashboard Administrator')
@section('header', 'Dashboard Utama')

@section('content')

<!-- Row 1: Key Metrics requested by user -->
<h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Ringkasan Utama</h4>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <i class="fas fa-money-bill-wave fa-xl"></i>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-indigo-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                <i class="fas fa-check-circle fa-xl"></i>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan Selesai</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['selesai'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-yellow-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                <i class="fas fa-clock fa-xl"></i>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 uppercase tracking-wider">Sedang Diproses</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['proses'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <i class="fas fa-users fa-xl"></i>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['customers'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Secondary / Master Data Metrics -->
<h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Statistik Sistem Data Master</h4>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-100 text-gray-500 mr-4">
                <i class="fas fa-users-cog fa-lg"></i>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Users Sistem</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['users'] }} <span class="text-xs text-gray-400 font-normal">akun</span></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-100 text-gray-500 mr-4">
                <i class="fas fa-list fa-lg"></i>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan Jasa</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['services'] }} <span class="text-xs text-gray-400 font-normal">item</span></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gray-100 text-gray-500 mr-4">
                <i class="fas fa-receipt fa-lg"></i>
            </div>
            <div>
                <p class="mb-1 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Semua Transaksi</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['orders'] }} <span class="text-xs text-gray-400 font-normal">riwayat</span></p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mt-4">
    <h3 class="text-lg font-semibold text-gray-800 mb-3"><i class="fas fa-hand-sparkles text-indigo-500 mr-2"></i> Selamat Datang!</h3>
    <p class="text-gray-600 leading-relaxed">
        Anda login sebagai pengguna level <strong>Administrator</strong>. 
        Pantau ringkasan aktivitas transaksi finansial di atas dan kontrol informasi secara penuh menggunakan navigasi Master Data di sebelah kiri.
    </p>
</div>

@endsection
