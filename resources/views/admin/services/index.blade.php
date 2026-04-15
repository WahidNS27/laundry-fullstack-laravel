@extends('layouts.admin')

@section('title', 'Kelola Layanan Jasa')
@section('header', 'Master Data Jasa')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-gray-700 font-medium">Daftar Jenis Layanan Jasa Laundry</h3>
    <a href="{{ route('admin.services.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
        <i class="fas fa-plus mr-2"></i> Tambah Jasa
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jasa</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga / KG</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($services as $key => $service)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $services->firstItem() + $key }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $service->service_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($service->description, 50) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.services.edit', $service->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 p-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus jasa ini?');">
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
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada data master jasa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($services->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $services->links() }}
    </div>
    @endif
</div>
@endsection
