<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends BaseApiController
{
    public function index()
    {
        try {
            $customers = Customer::latest()->get();

            return $this->success(
                $customers,
                'Customers Retrieved Successfully'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'    => 'required|string|max:255',
                'phone'   => 'required|string|max:20',
                'address' => 'nullable|string',
            ]);

            $customer = Customer::create([
                'name'    => $request->name,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);

            return $this->success(
                $customer,
                'Customer Created Successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(Customer $customer)
    {
        try {
            return $this->success(
                $customer,
                'Customer Retrieved Successfully'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            $request->validate([
                'name'    => 'required|string|max:255',
                'phone'   => 'required|string|max:20',
                'address' => 'nullable|string',
            ]);

            $customer->update([
                'name'    => $request->name,
                'phone'   => $request->phone,
                'address' => $request->address,
            ]);

            return $this->success(
                $customer,
                'Customer Updated Successfully'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();

            return $this->success(
                null,
                'Customer Deleted Successfully'
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}