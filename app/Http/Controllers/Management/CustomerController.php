<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(5); // Pagination
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $uniqueCode = $this->generateUniqueCode();

        Customer::create([
            'name' => $request->name,
            'loyalty_points' => $request->loyalty_points ?? 0,
            'unique_code' => $uniqueCode,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->only('name', 'loyalty_points'));

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    private function generateUniqueCode()
    {
        do {
            $code = Str::random(9);
        } while (Customer::where('unique_code', $code)->exists());

        return $code;
    }
}
