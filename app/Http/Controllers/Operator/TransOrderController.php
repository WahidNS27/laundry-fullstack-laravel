<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\TypeOfService;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;   
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransOrderController extends Controller
{
    public function index()
    {
        $orders = TransOrder::with('customer')->latest()->paginate(10);
        return view('operator.orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        $services = TypeOfService::orderBy('service_name')->get();
        $vouchers = Voucher::whereDate('valid_date', '>=', today())->get();
        return view('operator.orders.create', compact('customers', 'services' , 'vouchers'));
    }

    public function store(Request $request)
    {
        $rules = [
            'order_type' => 'required|array|min:1',
            'order_type.*' => 'exists:type_of_service,id',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
            'order_pay' => 'required|integer|min:0',
        ];

        if ($request->customer_type == 'member') {
            $rules['id_customer'] = 'required|exists:customer,id';
        } else {
            $rules['new_customer_name'] = 'required|string|max:255';
            $rules['new_customer_phone'] = 'required|string|max:20';
            $rules['new_customer_address'] = 'required|string';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Generate Order Code
            $today = Carbon::now()->format('Ymd');
            $lastOrder = TransOrder::where('order_code', 'like', "ORD-{$today}-%")
                ->orderBy('id', 'desc')
                ->first();

            $increment = $lastOrder ? intval(substr($lastOrder->order_code, -4)) + 1 : 1;
            $orderCode = "ORD-{$today}-" . str_pad($increment, 4, '0', STR_PAD_LEFT);

            if ($request->customer_type == 'member') {
                $customerId = $request->id_customer;
            } else {
                $newCustomer = Customer::create([
                    'customer_name' => $request->new_customer_name,
                    'phone' => $request->new_customer_phone,
                    'address' => $request->new_customer_address,
                    'is_member' => false,
                ]);
                $customerId = $newCustomer->id;
            }

            $order = TransOrder::create([
                'id_customer' => $customerId,
                'order_code' => $orderCode,
                'order_date' => Carbon::now()->toDateString(),
                'order_status' => 0,
                'order_pay' => $request->order_pay,
                'order_change' => 0,
                'total' => 0,
            ]);

            $subTotal = 0;

            // Detail Order
            foreach ($request->order_type as $index => $serviceId) {
                $service = TypeOfService::find($serviceId);
                $qty = $request->qty[$index];
                $subtotalItem = $service->price * $qty;

                TransOrderDetail::create([
                    'id_order' => $order->id,
                    'id_service' => $serviceId,
                    'qty' => $qty,
                    'subtotal' => $subtotalItem,
                    'notes' => $request->notes[$index] ?? null,
                ]);

                $subTotal += $subtotalItem;
            }

            // =========================
// =========================
// 🔥 HITUNG PAJAK
// =========================
$tax = round($subTotal * 0.10);
$grandTotal = $subTotal + $tax;

// ambil payment
$payment = (int) $request->order_pay;


// =========================
// 🔥 CEK MEMBER 
// =========================
$customer = Customer::find($customerId);

// Member harus dari admin/registrasi, bukan transaksi

// =========================
// 🔥 SUBTOTAL + PAJAK
// =========================
$tax = round($subTotal * 0.10);
$baseTotal = $subTotal + $tax;

// =========================
// 🔥 DISKON CUSTOMER BARU
// =========================
// Backend memberikan diskon 5% hanya untuk customer baru (non_member)
$memberDiscount = ($request->customer_type === 'non_member')
    ? ($baseTotal * 0.05)
    : 0;

// =========================
// 🔥 DISKON VOUCHER
// =========================
$voucherDiscount = 0;
$voucher = null;

if ($request->voucher_code) {

    $voucher = Voucher::where('code', trim($request->voucher_code))
        ->first();

    if (!$voucher) {
        throw new \Exception("Voucher tidak ditemukan!");
    }

    if (Carbon::parse($voucher->valid_date)->lt(Carbon::today())) {
        throw new \Exception("Voucher sudah expired!");
    }

    if ($customer) {
        $alreadyUsed = VoucherUsage::where('voucher_id', $voucher->id)
            ->where('customer_id', $customer->id)
            ->whereDate('used_date', today())
            ->exists();

        if ($alreadyUsed) {
            throw new \Exception("Voucher sudah digunakan hari ini!");
        }
    }

    $voucherDiscount = $baseTotal * ($voucher->discount / 100);
}

// =========================
// 🔥 TOTAL DISKON
// =========================
$totalDiscountAmount = $memberDiscount + $voucherDiscount;

// =========================
// 🔥 GRAND TOTAL FINAL (INI YANG DIPAKAI USER)
// =========================
$finalTotal = $baseTotal - $totalDiscountAmount;


// =========================
// 🔥 (7) VALIDASI PEMBAYARAN
// =========================
if ($payment < $finalTotal) {
    $kurang = $finalTotal - $payment;

    throw new \Exception("Gagal dibayar! Pembayaran kurang Rp " . number_format($kurang, 0, ',', '.') . " dari tagihan pembayaran.");
}


// =========================
// 🔥 (8) UPDATE ORDER
// =========================
$order->update([
    'total' => $finalTotal,
    'order_change' => $payment - $finalTotal,
    'subtotal' => $subTotal,
    'tax' => $tax,
    'discount_member' => $memberDiscount,
    'discount_voucher' => $voucherDiscount
]);

if ($request->voucher_code && isset($voucher) && $customer) {
    \App\Models\VoucherUsage::create([
        'voucher_id' => $voucher->id,
        'customer_id' => $customer->id,
        'used_date' => now()->toDateString(),
    ]);
}

DB::commit();

return redirect()
    ->route('operator.orders.show', $order->id)
    ->with('success', 'Transaksi Laundry berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function show(TransOrder $order)
    {
        $order->load(['customer', 'details.service', 'pickup']);
        return view('operator.orders.show', compact('order'));
    }
}
