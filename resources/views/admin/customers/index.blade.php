@extends('layouts.admin')

@section('title', 'Kelola Customers')
@section('header', 'Data Customers')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-gray-700 font-medium">Daftar Pelanggan</h3>
    <a href="{{ route('admin.customers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
        <i class="fas fa-plus mr-2"></i> Tambah Customer
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $key => $customer)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customers->firstItem() + $key }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $customer->customer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->phone }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($customer->address, 50) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 p-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus customer ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 p-1">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data customer.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
