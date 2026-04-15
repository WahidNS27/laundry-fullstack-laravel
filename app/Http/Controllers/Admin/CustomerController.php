<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:50',
            'phone' => 'required|string|max:13',
            'address' => 'required|string',
        ]);

        Customer::create($request->all());

        return redirect()->route('admin.customers.index')->with('success', 'Data customer berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'customer_name' => 'required|string|max:50',
            'phone' => 'required|string|max:13',
            'address' => 'required|string',
        ]);

        $customer->update($request->all());

        return redirect()->route('admin.customers.index')->with('success', 'Data customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Data customer berhasil dihapus.');
    }
}
