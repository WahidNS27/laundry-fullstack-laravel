<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\TypeOfService;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
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
        return view('operator.orders.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'required|exists:customer,id',
            'order_type' => 'required|array|min:1',
            'order_type.*' => 'exists:type_of_service,id',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
            'order_pay' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Generate Order Code: ORD-YYYYMMDD-XXXX
            $today = Carbon::now()->format('Ymd');
            $lastOrder = TransOrder::where('order_code', 'like', "ORD-{$today}-%")->orderBy('id', 'desc')->first();
            $increment = $lastOrder ? intval(substr($lastOrder->order_code, -4)) + 1 : 1;
            $orderCode = "ORD-{$today}-" . str_pad($increment, 4, '0', STR_PAD_LEFT);

            // Create Master Order
            $order = TransOrder::create([
                'id_customer' => $request->id_customer,
                'order_code' => $orderCode,
                'order_date' => Carbon::now()->toDateString(),
                'order_status' => 0, 
                'order_pay' => $request->order_pay ?? 0,
                'order_change' => 0, 
                'total' => 0, 
            ]);

            $grandTotal = 0;

            // Create Order Details
            foreach ($request->order_type as $index => $serviceId) {
                $service = TypeOfService::find($serviceId);
                $qty = $request->qty[$index];
                $subtotal = $service->price * $qty;

                TransOrderDetail::create([
                    'id_order' => $order->id,
                    'id_service' => $serviceId,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                    'notes' => $request->notes[$index] ?? null,
                ]);

                $grandTotal += $subtotal;
            }

            // Update Master Total and Change
            $payment = $request->order_pay ?? 0;
            $change = $payment >= $grandTotal ? $payment - $grandTotal : 0;
            
            $order->update([
                'total' => $grandTotal,
                'order_change' => $change,
            ]);

            DB::commit();
            return redirect()->route('operator.orders.show', $order->id)->with('success', 'Transaksi Laundry berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function show(TransOrder $order)
    {
        $order->load(['customer', 'details.service', 'pickup']);
        return view('operator.orders.show', compact('order'));
    }
}
