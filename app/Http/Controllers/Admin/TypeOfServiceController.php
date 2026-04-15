<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeOfService;
use Illuminate\Http\Request;

class TypeOfServiceController extends Controller
{
    public function index()
    {
        $services = TypeOfService::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:50',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        TypeOfService::create($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Master Layanan Jasa berhasil ditambahkan.');
    }

    public function edit(TypeOfService $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, TypeOfService $service)
    {
        $request->validate([
            'service_name' => 'required|string|max:50',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Master Layanan Jasa berhasil diperbarui.');
    }

    public function destroy(TypeOfService $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Master Layanan Jasa berhasil dihapus.');
    }
}
