@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Pimpinan')
@section('header', 'Laporan Rekapitulasi Penjualan')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6 print:hidden">
    <form action="{{ route('pimpinan.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4">
        <div>
            <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
            <select name="month" id="month" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                @for($m=1; $m<=12; ++$m)
                    <option value="{{ sprintf('%02d', $m) }}" {{ $selectedMonth == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endfor
            </select>
        </div>
        <div>
            <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
            <select name="year" id="year" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                @for($y=date('Y'); $y>=date('Y')-5; $y--)
                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded shadow-sm text-sm transition-colors">
                <i class="fas fa-filter mr-2"></i> Filter Laporan
            </button>
            <button type="button" onclick="window.location='?month={{ $selectedMonth }}&year={{ $selectedYear }}&print=1'" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded shadow-sm text-sm transition-colors">
                <i class="fas fa-print mr-2"></i> Cetak PDF
            </button>
        </div>
    </form>
</div>

<div class="hidden print:block text-center mb-6">
    <h1 class="text-2xl font-bold uppercase">Laporan Penjualan Laundry</h1>
    <p class="text-md">Periode: {{ date('F Y', mktime(0,0,0, $selectedMonth, 1, $selectedYear)) }}</p>
    <hr class="mt-4 border-t-2 border-gray-800">
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 print:hidden">
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                <i class="fas fa-money-bill-wave fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <i class="fas fa-check-circle fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Pesanan Selesai</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['completed_orders'] }} Transaksi</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-yellow-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                <i class="fas fa-clock fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Sedang Diproses</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_orders'] }} Transaksi</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                <i class="fas fa-users fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_customers'] }} Orang</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center print:hidden">
        <h3 class="text-lg font-semibold text-gray-800">Rincian Transaksi Selesai</h3>
        <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded font-bold uppercase tracking-wider">Pimpinan Access</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b">No</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Kode Order</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Tgl Order</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Tgl Ambil</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $report)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $report->order_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->order_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ optional($report->customer)->customer_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ optional(optional($report->pickup)->pickup_date)->format('d/m/Y H:i') ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                        Rp {{ number_format($report->total, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                        Tidak ada data transaksi ditemukan untuk periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($reports, 'hasPages') && $reports->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 print:hidden text-center">
        {{ $reports->appends(['month' => $selectedMonth, 'year' => $selectedYear])->links() }}
    </div>
    @endif
</div>

<div class="hidden print:block mt-8 text-right pr-6">
    <p class="text-lg font-bold">
        GRAND TOTAL PENDAPATAN: <span class="ml-4">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</span>
    </p>
</div>

<div class="hidden print:block mt-16 text-right float-right pr-10">
    <p class="mb-20">Jakarta, {{ date('d F Y') }}</p>
    <p class="font-bold underline">Pimpinan Laundry</p>
</div>

<style>
    @media print {
        /* 1. Sembunyikan elemen Navigasi Utama, Sidebar, dan Header Layout */
        nav, 
        header, 
        aside, 
        .navbar, 
        .sidebar, 
        .main-header,
        .print\:hidden,
        form,
        footer {
            display: none !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* 2. Reset Margin Body agar Konten Full Page */
        body {
            background-color: white !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 11px;
            color: black;
        }

        /* 3. Pastikan Container Utama tidak berjarak (offset) karena Sidebar yang hilang */
        main, .content-wrapper, .container {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* 4. Pengaturan Tabel agar rapi saat dicetak */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        th, td {
            border: 1px solid #000 !important;
            padding: 6px !important;
            color: black !important;
        }

        th {
            background-color: #eee !important;
            -webkit-print-color-adjust: exact;
        }

        /* 5. Force Page Breaks */
        tr { page-break-inside: avoid; }
        thead { display: table-header-group; }
    }
</style>

@if(request()->has('print') && request('print') == '1')
<script>
    window.onload = function() {
        window.print();
        // Opsional: Kembali ke halaman sebelumnya setelah dialog print ditutup
        // window.onafterprint = function() { window.history.back(); };
    };
</script>
@endif

@endsection