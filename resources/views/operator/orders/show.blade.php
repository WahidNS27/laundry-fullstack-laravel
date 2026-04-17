@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('header', 'Detail Transaksi #' . $order->order_code)

@section('content')
    <div class="mb-4 flex space-x-2">
        <a href="{{ route('operator.orders.index') }}"
            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded shadow-sm text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <button onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded shadow-sm text-sm">
            <i class="fas fa-print mr-2"></i> Cetak Struk
        </button>
        @if ($order->order_status == 0)
            <a href="{{ route('operator.pickup.create', $order->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded shadow-sm text-sm">
                <i class="fas fa-check-circle mr-2"></i> Proses Pengambilan
            </a>
        @endif
    </div>

    <!-- Print Section Container -->
    <div
        class="bg-white rounded-lg shadow-md border border-gray-200 p-8 max-w-4xl print:shadow-none print:border-none print:w-full">

        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-6 mb-6">
            <div>
                <h2 class="text-3xl font-bold text-indigo-700 tracking-tight uppercase"><i class="fas fa-tshirt mr-2"></i>
                    CLEAN LAUNDRY</h2>
                <p class="text-sm text-gray-500 mt-1">Sistem Informasi Manajemen Laundry Cepat & Bersih</p>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-bold text-gray-800">{{ $order->order_code }}</h3>
                <p class="text-sm text-gray-600 mt-1">Tanggal: {{ $order->order_date->format('d M Y') }}</p>
                <div class="mt-2 text-sm">
                    Status:
                    <span
                        class="font-bold text-{{ $order->status_badge }}-600 px-2 border rounded border-{{ $order->status_badge }}-300 bg-{{ $order->status_badge }}-50 uppercase tracking-wide">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Info Pelanggan -->
            <div>
                <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Informasi Pelanggan</h4>
                <div class="bg-gray-50 rounded p-4 border border-gray-100">
                    <p class="font-bold text-gray-900 text-lg">
                        {{ $order->customer->customer_name ?? $order->customer_name ?? '-' }}
                        @if(!$order->customer)
                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded ml-2">Non-Member</span>
                        @endif
                    </p>
                    <p class="text-gray-600 text-sm mt-1 flex items-center"><i class="fas fa-phone mr-2 text-gray-400"></i>
                        {{ $order->customer->phone ?? $order->customer_phone ?? '-' }}</p>
                    <p class="text-gray-600 text-sm mt-1 whitespace-pre-line leading-snug"><i
                            class="fas fa-map-marker-alt mr-2 text-gray-400"></i> {{ $order->customer->address ?? $order->customer_address ?? '-' }}
                    </p>
                </div>
            </div>
            <!-- Info Pengambilan -->
            <div>
                <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Informasi Pengambilan</h4>
                <div class="bg-gray-50 rounded p-4 border border-gray-100 h-full">
                    @if ($order->order_status == 1 && $order->pickup)
                        <p class="font-medium flex items-center text-green-700 text-sm"><i
                                class="fas fa-check text-green-500 mr-2"></i> Telah Diambil Pada:</p>
                        <p class="font-bold text-gray-900 text-lg mt-1 ml-6">
                            {{ $order->pickup->pickup_date->format('d M Y, H:i') }}</p>
                        @if ($order->pickup->notes)
                            <p class="text-gray-500 text-xs mt-2 italic ml-6 border-l-2 pl-2">"{{ $order->pickup->notes }}"
                            </p>
                        @endif
                    @else
                        <div class="flex items-center text-yellow-600 h-full">
                            <i class="fas fa-clock text-xl mr-3"></i>
                            <p class="font-medium text-sm">Laundry Sedang Diproses / Belum Diambil.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabel Rincian Jasa -->
        <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Rincian Layanan Jasa</h4>
        <table class="w-full text-left mb-6 border-collapse">
            <thead>
                <tr class="bg-indigo-50 border-y border-indigo-100">
                    <th class="py-3 px-4 font-semibold text-sm text-indigo-900">Deskripsi Layanan</th>
                    <th class="py-3 px-4 font-semibold text-sm text-indigo-900 text-center">Catatan</th>
                    <th class="py-3 px-4 font-semibold text-sm text-indigo-900 text-center">Qty</th>
                    <th class="py-3 px-4 font-semibold text-sm text-indigo-900 text-right">Harga</th>
                    <th class="py-3 px-4 font-semibold text-sm text-indigo-900 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->details as $detail)
                    <tr class="border-b border-gray-100 group">
                        <td class="py-3 px-4 text-sm text-gray-800 font-medium group-hover:bg-gray-50">
                            {{ $detail->service->service_name ?? 'Jasa Dihapus' }}</td>
                        <td class="py-3 px-4 text-sm text-gray-500 text-center italic group-hover:bg-gray-50">
                            {{ $detail->notes ?? '-' }}</td>
                        <td class="py-3 px-4 text-sm text-gray-800 text-center group-hover:bg-gray-50">{{ $detail->qty }}
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-800 text-right group-hover:bg-gray-50">Rp
                            {{ number_format(optional($detail->service)->price ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-sm text-gray-900 font-semibold text-right group-hover:bg-gray-50">Rp
                            {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Ringkasan Total -->
        @php
            if ($order->subtotal > 0) {
                $subtotal = $order->subtotal;
                $tax = $order->tax;
            } else {
                // Fallback untuk riwayat order lama sebelum fitur ini ditambahkan
                $subtotal = $order->total / 1.1;
                $tax = $order->total - $subtotal;
            }
        @endphp

        <div class="flex justify-end pt-4 border-t-2 border-gray-100 w-full">
            <div class="w-full max-w-sm space-y-3">

                <!-- SUBTOTAL -->
                <div class="flex justify-between text-gray-600 text-sm">
                    <span>Subtotal:</span>
                    <span class="font-medium text-gray-900">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                </div>

                <!-- PAJAK -->
                <div class="flex justify-between text-gray-600 text-sm">
                    <span>Pajak 10%:</span>
                    <span class="font-medium text-gray-900">
                        Rp {{ number_format($tax, 0, ',', '.') }}
                    </span>
                </div>

<!-- DISKON CUSTOMER BARU -->
<div class="flex justify-between text-gray-600 text-sm">
    <span>Diskon Pelanggan Baru:</span>
    <span class="font-medium text-green-600">
        Rp {{ number_format($order->discount_member ?? 0, 0, ',', '.') }}
    </span>
</div>

<!-- DISKON VOUCHER -->
<div class="flex justify-between text-gray-600 text-sm">
    <span>Diskon Voucher:</span>
    <span class="font-medium text-green-600">
        Rp {{ number_format($order->discount_voucher ?? 0, 0, ',', '.') }}
    </span>
</div>

                <!-- GRAND TOTAL -->
                <div
                    class="flex justify-between items-center text-lg font-bold text-gray-800 bg-gray-50 p-2 rounded border border-gray-200">
                    <span>GRAND TOTAL:</span>
                    <span class="text-indigo-700 text-xl">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>

                <!-- PEMBAYARAN -->
                <div class="flex justify-between text-gray-600 text-sm pt-2">
                    <span>Tunai (Pembayaran):</span>
                    <span class="font-medium text-gray-900">
                        Rp {{ number_format($order->order_pay, 0, ',', '.') }}
                    </span>
                </div>

                <!-- KEMBALIAN -->
                <div class="flex justify-between text-gray-600 text-sm">
                    <span>Kembalian:</span>
                    <span class="font-medium text-gray-900">
                        Rp {{ number_format($order->order_change, 0, ',', '.') }}
                    </span>
                </div>

            </div>
        </div>

        <!-- Print Footer -->
        <div class="mt-12 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
            <p class="font-medium text-gray-700 italic">Terima kasih telah menggunakan jasa kami!</p>
            <p class="mt-1">Laundry yang tidak diambil dalam 30 hari bukan tanggung jawab kami.</p>
        </div>
    </div>

    <style>
        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }

            nav,
            header,
            aside,
            .flex.space-x-2 {
                display: none !important;
            }

            main {
                padding: 0 !important;
                margin: 0 !important;
            }

            .bg-indigo-50 {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
@endsection
