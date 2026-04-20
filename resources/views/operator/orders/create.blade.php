@extends('layouts.admin')

@section('title', 'Buat Transaksi')
@section('header', 'Buat Transaksi Laundry Baru')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <a href="{{ route('operator.orders.index') }}"
        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<form action="{{ route('operator.orders.store') }}" method="POST" id="transactionForm">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- ========================= -->
    <!-- CUSTOMER -->
    <!-- ========================= -->
    <div class="bg-white p-6 rounded shadow">

        <h3 class="font-semibold mb-4">Data Pelanggan</h3>

        <!-- TYPE -->
        <div class="mb-3">
            <label class="font-medium text-gray-700 block mb-1">Tipe Pelanggan</label>
            <select name="customer_type" id="customerTypeSelect" class="w-full border rounded p-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="member">Member Lama</option>
                <option value="member_baru">Member Baru (Diskon 5% & Voucher)</option>
                <option value="non_member">Non Member</option>
            </select>
        </div>

        <!-- MEMBER -->
        <div id="memberSelection">
            <select name="id_customer" class="w-full border rounded p-2">
                <option value="">-- Pilih Customer --</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- NON MEMBER -->
        <div id="nonMemberInput" class="hidden">
            <input type="text" name="new_customer_name" placeholder="Nama"
                class="w-full border rounded p-2 mb-2">

            <input type="text" name="new_customer_phone" placeholder="No HP"
                class="w-full border rounded p-2 mb-2">

            <textarea name="new_customer_address" placeholder="Alamat"
                class="w-full border rounded p-2"></textarea>
        </div>

        <!-- ESTIMASI LAUNDRY -->
        <div class="mt-4">
            <label class="font-medium text-gray-700">Estimasi Selesai / Diambil</label>
            <input type="date" name="order_end_date" class="border focus:ring-indigo-500 focus:border-indigo-500 rounded p-2 w-full mt-1" required>
        </div>

        <!-- ========================= -->
        <!-- VOUCHER -->
        <!-- ========================= -->
        <div class="mt-4">
            <label class="font-medium">Kode Voucher</label>
            <input type="text" name="voucher_code" id="voucher_code" class="border p-2 w-full mt-1">
            <div id="voucherInfo" class="text-sm mt-1"></div>

            <div class="flex gap-2 mt-2 flex-wrap">
                @foreach($vouchers as $v)
                    <button type="button"
                        class="voucher-btn bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded"
                        data-code="{{ $v->code }}">
                        {{ $v->code }}
                    </button>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ========================= -->
    <!-- SERVICES -->
    <!-- ========================= -->
    <div class="lg:col-span-2 bg-white p-6 rounded shadow">

        <div class="flex justify-between mb-4">
            <h3 class="font-semibold">Rincian Laundry</h3>
            <button type="button" id="addRow"
                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                + Tambah
            </button>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Jasa</th>
                    <th class="p-2 w-24">Qty</th>
                    <th class="p-2 text-right">Subtotal</th>
                    <th class="p-2 w-10"></th>
                </tr>
            </thead>

            <tbody id="servicesContainer">
                <tr class="service-row">
                    <td>
                        <select name="order_type[]" class="service-select border p-1 w-full">
                            <option value="">-- pilih --</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}" data-price="{{ $s->price }}">
                                    {{ $s->service_name }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <input type="number" name="qty[]" class="qty-input w-full text-center border" value="1" min="1">
                    </td>

                    <td class="subtotal-text text-right">Rp 0</td>

                    <td class="text-center">
                        <button type="button" class="remove-row text-red-500 hover:text-red-700">x</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- ========================= -->
        <!-- TOTAL -->
        <!-- ========================= -->
        <div class="mt-4 text-right space-y-1">
            <p>Subtotal: <span id="subTotalText">Rp 0</span></p>
            <p>Pajak (10%): <span id="taxText">Rp 0</span></p>
            <p class="text-green-600">Diskon Member: <span id="memberDiscountText">Rp 0</span></p>
            <p class="text-green-600">Diskon Voucher: <span id="voucherDiscountText">Rp 0</span></p>
            <h3 class="font-bold text-lg">TOTAL: <span id="grandTotalText">Rp 0</span></h3>
        </div>

        <button type="submit"
            class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
            Simpan Transaksi
        </button>

    </div>
</div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function(){

    const vouchers = @json($vouchers);
    const voucherInput = document.getElementById('voucher_code');

    const formatRupiah = (num) => 'Rp ' + Math.round(num).toLocaleString('id-ID');

    function calculate(){
        let sub = 0;

        document.querySelectorAll('.service-row').forEach(row=>{
            const select = row.querySelector('.service-select');
            const qtyInput = row.querySelector('.qty-input');

            const price = parseFloat(select.selectedOptions[0]?.dataset.price || 0);
            const qty = parseFloat(qtyInput.value || 0);

            const total = price * qty;
            sub += total;

            row.querySelector('.subtotal-text').innerText = formatRupiah(total);
        });

        const tax = sub * 0.1;
        const base = sub + tax;

        const type = document.getElementById('customerTypeSelect').value;

        // Diskon member baru 5%
        let memberDisc = (type === 'member_baru') ? base * 0.05 : 0;

        let voucherDisc = 0;
        const code = voucherInput.value.toLowerCase();
        
        if(code){
            const found = vouchers.find(v => v.code.toLowerCase() === code);
            if(found){
                voucherDisc = base * (found.discount / 100);
                document.getElementById('voucherInfo').innerHTML =
                    `<span class="text-green-600">Voucher aktif (${found.discount}%)</span>`;
            } else {
                document.getElementById('voucherInfo').innerHTML = '<span class="text-red-500">Voucher tidak valid</span>';
            }
        } else {
            document.getElementById('voucherInfo').innerHTML = '';
            voucherDisc = 0;
        }

        const total = base - memberDisc - voucherDisc;

        document.getElementById('subTotalText').innerText = formatRupiah(sub);
        document.getElementById('taxText').innerText = formatRupiah(tax);
        document.getElementById('memberDiscountText').innerText = formatRupiah(memberDisc);
        document.getElementById('voucherDiscountText').innerText = formatRupiah(voucherDisc);
        document.getElementById('grandTotalText').innerText = formatRupiah(total);

        return total;
    }

    // EVENT GLOBAL
    document.addEventListener('input', calculate);

    // ADD ROW
    document.getElementById('addRow').onclick = ()=>{
        const row = document.querySelector('.service-row').cloneNode(true);

        row.querySelector('.service-select').value = '';
        row.querySelector('.qty-input').value = 1;
        row.querySelector('.subtotal-text').innerText = 'Rp 0';

        document.getElementById('servicesContainer').appendChild(row);
    };

    // REMOVE ROW (MINIMAL 1)
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-row')){
            const rows = document.querySelectorAll('.service-row');
            if(rows.length > 1){
                e.target.closest('tr').remove();
                calculate();
            }
        }
    });

    // VOUCHER CLICK
    document.querySelectorAll('.voucher-btn').forEach(btn=>{
        btn.onclick = ()=>{
            voucherInput.value = btn.dataset.code;
            calculate();
        }
    });

    // TOGGLE CUSTOMER
    const customerTypeSelect = document.getElementById('customerTypeSelect');
    const voucherCodeInput = document.getElementById('voucher_code');

    function toggleCustomer(){
        const val = customerTypeSelect.value;
        const isMemberLama = val === 'member';

        document.getElementById('memberSelection').classList.toggle('hidden', !isMemberLama);
        document.getElementById('nonMemberInput').classList.toggle('hidden', isMemberLama);
        
        // Kalau member_baru/non_member pastikan select id_customer kosong biar tidak kirim id
        if(!isMemberLama) {
            document.querySelector('[name="id_customer"]').value = '';
        }

        calculate();
    }

    customerTypeSelect.addEventListener('change', toggleCustomer);
    toggleCustomer();
    calculate();

});
</script>
@endsection