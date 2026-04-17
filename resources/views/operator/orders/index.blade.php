@extends('layouts.admin')

@section('title', 'Kelola Transaksi Laundry')
@section('header', 'Data Transaksi Laundry')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-gray-700 font-medium">Daftar Transaksi</h3>
        <a href="{{ route('operator.orders.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
            <i class="fas fa-cart-plus mr-2"></i> Buat Transaksi Baru
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                            Order</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                {{ $order->order_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->order_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ optional($order->customer)->customer_name ?? $order->customer_name ?? '-' }}
                                @if(!$order->customer)
                                    <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded ml-1">Guest</span>
                                @endif
                            </td>
                            @php
                                if ($order->subtotal > 0) {
                                    $subtotal = $order->subtotal;
                                    $tax = $order->tax;
                                } else {
                                    $subtotal = $order->total / 1.1;
                                    $tax = $order->total - $subtotal;
                                }
                            @endphp

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                <div class="flex flex-col">
                                    <span class="font-semibold">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        Pajak: Rp {{ number_format($tax, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $order->status_badge }}-100 text-{{ $order->status_badge }}-800">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end space-x-3">
                                <a href="{{ route('operator.orders.show', $order->id) }}"
                                    class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                @if ($order->order_status == 0)
                                    <a href="{{ route('operator.pickup.create', $order->id) }}"
                                        class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-check-circle"></i> Proses Pengambilan
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi laundry.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
