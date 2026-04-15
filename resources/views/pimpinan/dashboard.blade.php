@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Pimpinan')
@section('header', 'Laporan Rekapitulasi Penjualan')

@section('content')
<!-- Filter Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
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
            <button type="button" onclick="window.print()" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded shadow-sm text-sm transition-colors print:hidden">
                <i class="fas fa-print mr-2"></i> Cetak PDF
            </button>
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4 print:hidden">
                <i class="fas fa-money-bill-wave fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Total Pendapatan <span class="text-xs print:inline hidden">(Bulan/Tahun Terpilih)</span></p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 border-l-4 border-l-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4 print:hidden">
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
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4 print:hidden">
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
            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4 print:hidden">
                <i class="fas fa-users fa-lg"></i>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_customers'] }} Orang</p>
            </div>
        </div>
    </div>
</div>

<!-- Laporan Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Rincian Transaksi Selesai ({{ date('F Y', mktime(0,0,0, $selectedMonth, 1, $selectedYear)) }})</h3>
        <span class="text-sm px-3 py-1 bg-green-100 text-green-800 rounded-full font-medium">Read Only Access</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode Order</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Order</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Ambil</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Nilai Transaksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $key => $report)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reports->firstItem() + $key }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">{{ $report->order_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->order_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ 
                        optional($report->customer)->customer_name 
                    }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ optional(optional($report->pickup)->pickup_date)->format('d/m/Y H:i') ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                        Rp {{ number_format($report->total, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                        Tidak ada transaksi selesai pada periode bulan {{ $selectedMonth }}/{{ $selectedYear }}.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <th colspan="5" class="px-6 py-3 text-right text-sm font-bold text-gray-700">SUBTOTAL HALAMAN INI:</th>
                    <th class="px-6 py-3 text-right text-sm font-bold text-indigo-700">Rp {{ number_format($reports->sum('total'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    
    @if($reports->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 print:hidden">
        {{ $reports->appends(['month' => $selectedMonth, 'year' => $selectedYear])->links() }}
    </div>
    @endif
</div>

<style>
    @media print {
        body { background-color: white; padding: 0; margin: 0; }
        nav, header, aside, form>div:last-child { display: none !important; }
        main { padding: 0 !important; margin: 0 !important; }
        .shadow-sm { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
        h2, h3 { color: black !important; }
        .print\:hidden { display: none !important; }
        .print\:inline { display: inline !important; }
    }
</style>
@endsection
