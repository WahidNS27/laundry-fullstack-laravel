@extends('layouts.admin')

@section('title', 'Buat Transaksi')
@section('header', 'Buat Transaksi Laundry Baru')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <a href="{{ route('operator.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke daftar
    </a>
</div>

<form action="{{ route('operator.orders.store') }}" method="POST" id="transactionForm">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Pilih Customer -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                <h4 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Data Pelanggan</h4>
                
                <div class="mb-4">
                    <label for="id_customer" class="block text-sm font-medium text-gray-700 mb-1">Pilih Customer <span class="text-red-500">*</span></label>
                    <select name="id_customer" id="id_customer" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('id_customer') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->customer_name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_customer')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Customer belum ada? <a href="#" onclick="alert('Buka tab Administrator untuk tambah Customer baru sementara.'); return false;" class="text-indigo-600 font-medium">Buat Baru (Khusus Admin)</a></p>
                </div>
                
                <div class="mb-4 pt-4 border-t border-gray-100">
                    <label for="order_pay" class="block text-sm font-medium text-gray-700 mb-1">Uang Bayar (Opsional)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="order_pay" id="order_pay" class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0" min="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Jasa -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h4 class="text-md font-semibold text-gray-800">Rincian Jasa / Item Laundry</h4>
                    <button type="button" id="addServiceRow" class="text-sm bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded shadow transition-colors">
                        <i class="fas fa-plus"></i> Tambah Item
                    </button>
                </div>

                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full divide-y divide-gray-200" id="servicesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Jasa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-24">Qty (Kg/Pcs)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase w-32">Subtotal</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="servicesContainer" class="bg-white divide-y divide-gray-200">
                            <!-- Template row (akan diduplikasi JS) -->
                            <tr class="service-row">
                                <td class="px-4 py-3">
                                    <select name="order_type[]" class="service-select w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="" data-price="0">-- Pilih Jasa --</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->service_name }} (Rp {{ number_format($service->price,0,',','.') }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="qty[]" class="qty-input w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 text-center" min="1" value="1" required>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="notes[]" class="w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Opsional...">
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-sm text-gray-900 subtotal-text">
                                    Rp 0
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="remove-row text-red-500 hover:text-red-700 disabled:opacity-50" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th colspan="3" class="px-4 py-3 text-right text-sm font-bold text-gray-700">GRAND TOTAL:</th>
                                <th class="px-4 py-3 text-right text-lg font-bold text-indigo-700" id="grandTotalText">Rp 0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-8 rounded-md shadow-md transition-all transform hover:scale-105 cursor-pointer flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('servicesContainer');
    const addBtn = document.getElementById('addServiceRow');
    const grandTotalText = document.getElementById('grandTotalText');

    // Update grand total
    function calculateGrandTotal() {
        let grandTotal = 0;
        const rows = document.querySelectorAll('.service-row');
        
        rows.forEach(row => {
            const select = row.querySelector('.service-select');
            const qtyInput = row.querySelector('.qty-input');
            const subtotalText = row.querySelector('.subtotal-text');
            
            const price = parseFloat(select.options[select.selectedIndex]?.dataset.price || 0);
            const qty = parseFloat(qtyInput.value || 0);
            
            const subtotal = price * qty;
            grandTotal += subtotal;
            
            subtotalText.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        });
        
        grandTotalText.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        
        // Disable remove button if only 1 row
        const removeBtns = document.querySelectorAll('.remove-row');
        removeBtns.forEach(btn => {
            btn.disabled = rows.length === 1;
        });
    }

    // Bind events to exist rows
    function bindRowEvents(row) {
        const select = row.querySelector('.service-select');
        const qtyInput = row.querySelector('.qty-input');
        const removeBtn = row.querySelector('.remove-row');
        
        select.addEventListener('change', calculateGrandTotal);
        qtyInput.addEventListener('input', calculateGrandTotal);
        
        removeBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.service-row').length > 1) {
                row.remove();
                calculateGrandTotal();
            }
        });
    }

    // Add new row
    addBtn.addEventListener('click', function() {
        const firstRow = document.querySelector('.service-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset values
        newRow.querySelector('.service-select').value = '';
        newRow.querySelector('.qty-input').value = '1';
        if(newRow.querySelector('input[name="notes[]"]')) {
            newRow.querySelector('input[name="notes[]"]').value = '';
        }
        newRow.querySelector('.subtotal-text').innerText = 'Rp 0';
        
        container.appendChild(newRow);
        bindRowEvents(newRow);
        calculateGrandTotal();
    });

    // Initialize first row
    bindRowEvents(document.querySelector('.service-row'));
    calculateGrandTotal();
});
</script>
@endsection
