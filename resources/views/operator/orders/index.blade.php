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
                                <div class="flex flex-col gap-2">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $order->status_badge }}-100 text-{{ $order->status_badge }}-800 w-fit">
                                        {{ $order->status_label }}
                                    </span>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $order->payment_badge }}-100 text-{{ $order->payment_badge }}-800 w-fit">
                                        {{ $order->payment_label }}
                                    </span>
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end space-x-3">
                                <a href="{{ route('operator.orders.show', $order->id) }}"
                                    class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                @if ($order->payment_status == 0)
                                    <button type="button" onclick="openPaymentModal('{{ route('operator.orders.pay', $order->id) }}', {{ $order->total }})"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-money-bill-wave"></i> Bayar
                                    </button>
                                @endif
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

    <!-- Modal Pembayaran -->
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closePaymentModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="paymentForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Pembayaran Pesanan
                                </h3>
                                <div class="mt-4 w-full text-left">
                                    <p class="text-sm text-gray-500 mb-2">Total Tagihan: <strong id="modalTotalTagihan" class="text-gray-900"></strong></p>
                                    <label class="block text-sm font-medium text-gray-700">Nominal Uang Pelanggan (Rp)</label>
                                    <input type="number" name="order_pay" id="modalOrderPayInput" min="0" required
                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                                    <p class="mt-2 text-sm text-gray-500">Kembalian: <strong id="modalKembalian" class="text-gray-900">Rp 0</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Proses Bayar
                        </button>
                        <button type="button" onclick="closePaymentModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
let currentTotal = 0;

function formatRupiah(num){
    return 'Rp ' + Math.round(num).toLocaleString('id-ID');
}

function openPaymentModal(url, total) {
    document.getElementById('paymentForm').action = url;
    currentTotal = total;

    document.getElementById('modalTotalTagihan').innerText = formatRupiah(total);
    document.getElementById('modalOrderPayInput').value = '';
    document.getElementById('modalKembalian').innerText = 'Rp 0';

    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}

const input = document.getElementById('modalOrderPayInput');
const info = document.getElementById('modalKembalian');

input.addEventListener('input', function() {
    let pay = parseFloat(this.value) || 0;

    if(pay < currentTotal){
        const kurang = currentTotal - pay;

        info.innerHTML = `<span class="text-red-600">
            Kurang ${formatRupiah(kurang)}
        </span>`;
    } else {
        const change = pay - currentTotal;

        info.innerHTML = `<span class="text-green-600">
            Kembalian ${formatRupiah(change)}
        </span>`;
    }
});


// 🔥 SUBMIT VALIDATION (SWEETALERT)
document.getElementById('paymentForm').addEventListener('submit', function(e) {

    let pay = parseFloat(input.value) || 0;

    if (pay < currentTotal) {
        e.preventDefault();

        const kurang = currentTotal - pay;

        Swal.fire({
            icon: 'error',
            title: 'Pembayaran Kurang!',
            html: `Kurang <b>${formatRupiah(kurang)}</b> dari total tagihan`,
            confirmButtonText: 'OK',
            confirmButtonColor: '#ef4444'
        });

        return false;
    }

    // 🔥 SUCCESS ALERT (OPSIONAL)
    Swal.fire({
        icon: 'success',
        title: 'Pembayaran Berhasil!',
        text: 'Transaksi berhasil dibayar',
        timer: 1500,
        showConfirmButton: false
    });

});
</script>
@endsection
