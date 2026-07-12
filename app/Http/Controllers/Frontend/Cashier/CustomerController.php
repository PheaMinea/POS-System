<?php

namespace App\Http\Controllers\Frontend\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        try {

            $customers = Customer::withCount('orders')
                ->latest()
                ->get();

            return view(
                'cashier.customers.index',
                compact('customers')
            );

        } catch (\Exception $e) {

            return view(
                'cashier.customers.index',
                ['customers' => []]
            )->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function create()
    {
        return view(
            'cashier.customers.create'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        Customer::create($validated);

        return redirect()
            ->route('cashier.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show($id)
    {
        try {
            $customer = Customer::withCount('orders')->findOrFail($id);

            return view(
                'cashier.customers.show',
                compact('customer')
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('cashier.customers.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function edit($id)
    {
        try {

            $customer = Customer::findOrFail($id);

            return view(
                'cashier.customers.edit',
                compact('customer')
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('cashier.customers.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            // Delete customer (orders will be kept but customer_id becomes null)
            $customer->delete();

            Log::info('Customer deleted by cashier', [
                'customer_id' => $id,
                'cashier_id' => auth('web')->id(),
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer deleted successfully',
                ]);
            }

            return redirect()
                ->route('cashier.customers.index')
                ->with('success', 'Customer deleted successfully');

        } catch (\Exception $e) {
            Log::error('Customer delete error', [
                'customer_id' => $id,
                'error' => $e->getMessage(),
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete customer: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()
                ->route('cashier.customers.index')
                ->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }
}