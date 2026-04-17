@extends('layouts.admin')

@section('title', 'Buat Transaksi')
@section('header', 'Buat Transaksi Laundry Baru')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('operator.orders.index') }}"
            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
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
    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pelanggan</label>

    <div class="flex gap-4">
        <label class="flex items-center gap-2">
            <input type="radio" name="customer_type" value="member" id="memberRadio" {{ old('customer_type', 'member') == 'member' ? 'checked' : '' }}>
            <span>Customer Lama</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="radio" name="customer_type" value="non_member" id="nonMemberRadio" {{ old('customer_type') == 'non_member' ? 'checked' : '' }}>
            <span>Customer Baru (Diskon 5%)</span>
        </label>
    </div>
</div>
                    <div id="memberSelection" style="display: none;">
                        <div class="mb-4">
                            <label for="id_customer" class="block text-sm font-medium text-gray-700 mb-1">Pilih Customer <span
                                    class="text-red-500">*</span></label>
                            <select name="id_customer" id="id_customer"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Customer Lama --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('id_customer') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_name }} - {{ $customer->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_customer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="nonMemberInput">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan Baru <span class="text-red-500">*</span></label>
                            <input type="text" name="new_customer_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('new_customer_name') }}" placeholder="Masukkan nama">
                            @error('new_customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. HP Pelanggan <span class="text-red-500">*</span></label>
                            <input type="text" name="new_customer_phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('new_customer_phone') }}" placeholder="Masukkan nomor HP">
                            @error('new_customer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="new_customer_address" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Alamat lengkap">{{ old('new_customer_address') }}</textarea>
                            @error('new_customer_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mb-4 pt-4 border-t border-gray-100">
                        <label for="order_pay" class="block text-sm font-medium text-gray-700 mb-1">Uang Bayar
                            </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="order_pay" id="order_pay"
                                class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0" min="0">
                        </div>
                        <div id="paymentInfo" class="mt-2 text-sm font-medium"></div>
                    </div>
                  {{-- ========================= --}}
                {{-- 🔥 VOUCHER SECTION --}}
                {{-- ========================= --}}
                <div class="mb-4">
                    <h3 class="font-semibold mb-2">Voucher Tersedia</h3>
<div class="mb-3">
    <label>Kode Voucher</label>
    <input type="text" name="voucher_code" id="voucher_code">

    <div id="voucherInfo"></div>
</div>
                    <div class="flex gap-2 flex-wrap">
                        @foreach ($vouchers as $voucher)
                            <button 
                                type="button"
                                class="voucher-btn bg-green-500 text-white px-3 py-1 rounded"
                                data-code="{{ $voucher->code }}"
                            >
                                {{ $voucher->code }}
                            </button>
                        @endforeach
                    </div>
                </div>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Jasa -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h4 class="text-md font-semibold text-gray-800">Rincian Jasa / Item Laundry</h4>
                        <button type="button" id="addServiceRow"
                            class="text-sm bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded shadow transition-colors">
                            <i class="fas fa-plus"></i> Tambah Item
                        </button>
                    </div>

                    <div class="overflow-x-auto mb-4">
                        <table class="min-w-full divide-y divide-gray-200" id="servicesTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Jasa
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-24">Qty
                                        (Kg/Pcs)</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase w-32">
                                        Subtotal</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-16">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="servicesContainer" class="bg-white divide-y divide-gray-200">
                                <!-- Template row (akan diduplikasi JS) -->
                                <tr class="service-row">
                                    <td class="px-4 py-3">
                                        <select name="order_type[]"
                                            class="service-select w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required>
                                            <option value="" data-price="0">-- Pilih Jasa --</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                    {{ $service->service_name }} (Rp
                                                    {{ number_format($service->price, 0, ',', '.') }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="qty[]"
                                            class="qty-input w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 text-center"
                                            min="1" value="1" required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="notes[]"
                                            class="w-full rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Opsional...">
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium text-sm text-gray-900 subtotal-text">
                                        Rp 0
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button"
                                            class="remove-row text-red-500 hover:text-red-700 disabled:opacity-50" disabled>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <th colspan="3" class="px-4 py-2 text-right text-sm text-gray-600">Subtotal</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-800" id="subTotalText">Rp
                                        0</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="px-4 py-2 text-right text-sm text-gray-600">Pajak (10%)</th>
                                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-800" id="taxText">Rp 0
                                    </th>
                                    <th></th>
                                </tr>
                                 <!-- 🔥 DISKON CUSTOMER BARU -->
                                    <tr>
                                        <th colspan="3" class="px-4 py-2 text-right text-sm text-green-600">Diskon Pelanggan Baru (5%)</th>
                                        <th class="px-4 py-2 text-right text-sm font-medium text-green-600" id="memberDiscountText">Rp 0</th>
                                        <th></th>
                                    </tr>

                                    <!-- 🔥 DISKON VOUCHER -->
                                    <tr>
                                        <th colspan="3" class="px-4 py-2 text-right text-sm text-green-600">Diskon Voucher (10%)</th>
                                        <th class="px-4 py-2 text-right text-sm font-medium text-green-600" id="voucherDiscountText">Rp 0</th>
                                        <th></th>
                                    </tr>

                                    <!-- 🔥 TOTAL SETELAH DISKON -->
                                    <tr>
                                        <th colspan="3" class="px-4 py-2 text-right text-sm text-gray-600">Total Setelah Diskon</th>
                                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-800" id="finalTotalText">Rp 0</th>
                                        <th></th>
                                    </tr>
                                <tr>
                                    <th colspan="3" class="px-4 py-3 text-right text-lg font-bold text-gray-700">GRAND
                                        TOTAL</th>
                                    <th class="px-4 py-3 text-right text-lg font-bold text-indigo-700" id="grandTotalText">
                                        Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            </tfoot>
                        </table>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-8 rounded-md shadow-md transition-all transform hover:scale-105 cursor-pointer flex items-center">
                            <i class="fas fa-save mr-2"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const validVouchers = @json($vouchers);
    const container = document.getElementById('servicesContainer');
    const addBtn = document.getElementById('addServiceRow');
    const paymentInput = document.getElementById('order_pay');
    const paymentInfo = document.getElementById('paymentInfo');
    const form = document.getElementById('transactionForm');

    const voucherInput = document.querySelector('input[name="voucher_code"]');
    const voucherInfo = document.getElementById('voucherInfo');

    const memberRadio = document.getElementById('memberRadio');
    const nonMemberRadio = document.getElementById('nonMemberRadio');

    // =========================
    // HITUNG TOTAL
    // =========================
    function calculateGrandTotal() {

        let subTotal = 0;
        const rows = document.querySelectorAll('.service-row');

        rows.forEach(row => {
            const select = row.querySelector('.service-select');
            const qtyInput = row.querySelector('.qty-input');
            const subtotalText = row.querySelector('.subtotal-text');

            const price = parseFloat(select.options[select.selectedIndex]?.dataset.price || 0);
            const qty = parseFloat(qtyInput.value || 0);

            const subtotal = price * qty;
            subTotal += subtotal;

            if (subtotalText) {
                subtotalText.innerText =
                    'Rp ' + subtotal.toLocaleString('id-ID');
            }
        });

        // =========================
        // PAJAK
        // =========================
        const tax = subTotal * 0.10;
        const baseTotal = subTotal + tax;

        // =========================
        // DISKON LOGIC (FIXED MEMBER SYSTEM)
        // =========================
        const isMember = memberRadio && memberRadio.checked;

        let memberDiscount = 0;
        let voucherDiscount = 0;

        if (nonMemberRadio && nonMemberRadio.checked) {
            memberDiscount = baseTotal * 0.05;
        }

        const voucherPct = parseFloat(voucherInput ? (voucherInput.dataset.discount || 0) : 0);
        if (voucherPct > 0) {
            voucherDiscount = baseTotal * (voucherPct / 100);
        }

        const totalDiscount = memberDiscount + voucherDiscount;
        const finalTotal = baseTotal - totalDiscount;

        // =========================
        // DISPLAY
        // =========================
        document.getElementById('subTotalText').innerText =
            'Rp ' + subTotal.toLocaleString('id-ID');

        document.getElementById('taxText').innerText =
            'Rp ' + tax.toLocaleString('id-ID');

        document.getElementById('memberDiscountText').innerText =
            'Rp ' + Math.round(memberDiscount).toLocaleString('id-ID');

        document.getElementById('voucherDiscountText').innerText =
            'Rp ' + Math.round(voucherDiscount).toLocaleString('id-ID');

        document.getElementById('finalTotalText').innerText =
            'Rp ' + Math.round(finalTotal).toLocaleString('id-ID');

        // 🔥 INI YANG DIPAKAI SEBAGAI GRAND TOTAL
        document.getElementById('grandTotalText').innerText =
            'Rp ' + Math.round(finalTotal).toLocaleString('id-ID');

        // =========================
        // PAYMENT VALIDATION
        // =========================
        const payment = parseFloat(paymentInput.value || 0);

        if (payment > 0) {
            if (payment < finalTotal) {
                const kurang = finalTotal - payment;

                paymentInfo.innerHTML =
                    `<span class="text-red-600">Kurang bayar: Rp ${Math.round(kurang).toLocaleString('id-ID')}</span>`;
            } else {
                const change = payment - finalTotal;

                paymentInfo.innerHTML =
                    `<span class="text-green-600">Kembalian: Rp ${Math.round(change).toLocaleString('id-ID')}</span>`;
            }
        } else {
            paymentInfo.innerText = 'Masukkan pembayaran';
        }

        return finalTotal;
    }

    // =========================
    // ROW EVENTS
    // =========================
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

    // =========================
    // ADD ROW
    // =========================
    addBtn.addEventListener('click', function() {
        const firstRow = document.querySelector('.service-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('.service-select').value = '';
        newRow.querySelector('.qty-input').value = '1';
        newRow.querySelector('.subtotal-text').innerText = 'Rp 0';

        container.appendChild(newRow);
        bindRowEvents(newRow);
        calculateGrandTotal();
    });

    // =========================
    // VOUCHER
    // =========================
    document.querySelectorAll('.voucher-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            voucherInput.value = this.dataset.code;
            
            // trigger input event manually
            const event = new Event('input', {
                bubbles: true,
                cancelable: true,
            });
            voucherInput.dispatchEvent(event);
        });
    });

    if (voucherInput) {
        voucherInput.addEventListener('input', function() {
            const code = this.value.trim().toLowerCase();
            const found = validVouchers.find(v => v.code.toLowerCase() === code);
            
            if (found) {
                voucherInfo.innerHTML = `<span class="text-green-600">Voucher valid! Diskon ${found.discount}%</span>`;
                voucherInput.dataset.discount = found.discount;
            } else {
                if (code !== "") {
                    voucherInfo.innerHTML = `<span class="text-red-600">Voucher tidak ditemukan / expired</span>`;
                } else {
                    voucherInfo.innerHTML = '';
                }
                voucherInput.dataset.discount = 0;
            }

            calculateGrandTotal();
        });
    }

    // =========================
    // MEMBER / NON MEMBER CHANGE
    // =========================
    function toggleCustomerInput() {
        const memberSelection = document.getElementById('memberSelection');
        const nonMemberInput = document.getElementById('nonMemberInput');
        const idCustomerSelect = document.getElementById('id_customer');
        
        if (memberRadio && memberRadio.checked) {
            memberSelection.style.display = 'block';
            nonMemberInput.style.display = 'none';
            idCustomerSelect.setAttribute('required', 'required');
        } else {
            memberSelection.style.display = 'none';
            nonMemberInput.style.display = 'block';
            idCustomerSelect.removeAttribute('required');
        }
        calculateGrandTotal();
    }

    if (memberRadio) memberRadio.addEventListener('change', toggleCustomerInput);
    if (nonMemberRadio) nonMemberRadio.addEventListener('change', toggleCustomerInput);

    // =========================
    // PAYMENT
    // =========================
    paymentInput.addEventListener('input', calculateGrandTotal);

    // =========================
    // INIT
    // =========================
    bindRowEvents(document.querySelector('.service-row'));
    toggleCustomerInput();
    calculateGrandTotal();

    // =========================
    // FORM VALIDATION SUBMIT
    // =========================
    form.addEventListener('submit', function(e) {
        const finalTotal = calculateGrandTotal();
        const payment = parseFloat(paymentInput.value || 0);

        if (payment < finalTotal) {
            e.preventDefault();
            const kurang = finalTotal - payment;
            alert(`Gagal dibayar! Pembayaran kurang Rp ${Math.round(kurang).toLocaleString('id-ID')} dari pembayaran tagihan.`);
        }
    });

});
</script>
@endsection
