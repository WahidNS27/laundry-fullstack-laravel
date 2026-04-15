@extends('layouts.admin')

@section('title', 'Proses Pengambilan Laundry')
@section('header', 'Proses Pengambilan Laundry')

@section('content')
<div class="mb-4">
    <a href="{{ route('operator.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke daftar
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <div class="p-6">
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Anda akan memproses pengambilan pesanan <strong>{{ $order->order_code }}</strong> atas nama <strong>{{ $order->customer->customer_name }}</strong>. 
                        Tindakan ini akan menetapkan status pesanan menjadi <span class="font-bold uppercase">"Sudah Diambil"</span>.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('operator.pickup.store', $order->id) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Pengambilan (Opsional)</label>
                <textarea name="notes" id="notes" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('notes') border-red-500 @enderror" placeholder="Contoh: Diambil oleh saudaranya (Bapak Joko)...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2">Waktu pengambilan akan dicatat secara otomatis mengikuti waktu server sistem saat ini.</p>
            </div>
            
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition-colors cursor-pointer flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Verifikasi Pengambilan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
