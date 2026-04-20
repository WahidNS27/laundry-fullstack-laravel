<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\TransOrder;
use App\Models\TransLaundryPickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransLaundryPickupController extends Controller
{
    public function create(TransOrder $order)
    {
        // Pastikan order belum diambil
        if ($order->order_status == 1) {
            return redirect()->route('operator.orders.index')->with('error', 'Pesanan ini sudah diambil sebelumnya!');
        }

        return view('operator.pickup.create', compact('order'));
    }

    public function store(Request $request, TransOrder $order)
    {
        // Pastikan order belum diambil (double check)
        if ($order->order_status == 1) {
            return redirect()->route('operator.orders.index')->with('error', 'Pesanan ini sudah diambil sebelumnya!');
        }

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Insert log pengambilan
            TransLaundryPickup::create([
                'id_order' => $order->id,
                'id_customer' => $order->id_customer,
                'pickup_date' => Carbon::now(),
                'notes' => $request->notes,
            ]);

            // Update status pesanan & tanggal selesai
            $order->update([
                'order_status' => 1, // Sudah Diambil
            ]);

            DB::commit();
            return redirect()->route('operator.orders.show', $order->id)->with('success', 'Berhasil memproses pengambilan laundry!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengambilan: ' . $e->getMessage());
        }
    }
}
