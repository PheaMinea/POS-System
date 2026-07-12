<?php

namespace App\Http\Controllers\Api;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends BaseApiController
{
    public function index()
    {
        try {

            $suppliers = Supplier::latest()->get();

            return $this->success(
                $suppliers,
                'Suppliers Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name'    => 'required|string|max:255',
                'phone'   => 'required|string|max:20',
                'address' => 'nullable|string',
                'image'   => 'nullable|string',
            ]);

            $supplier = Supplier::create([
                'name'    => $request->name,
                'phone'   => $request->phone,
                'address' => $request->address,
                'image'   => $request->image,
            ]);

            return $this->success(
                $supplier,
                'Supplier Created Successfully',
                201
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function show(Supplier $supplier)
    {
        try {

            return $this->success(
                $supplier,
                'Supplier Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function update(Request $request, Supplier $supplier)
    {
        try {

            $request->validate([
                'name'    => 'required|string|max:255',
                'phone'   => 'required|string|max:20',
                'address' => 'nullable|string',
                'image'   => 'nullable|string',
            ]);

            $supplier->update([
                'name'    => $request->name,
                'phone'   => $request->phone,
                'address' => $request->address,
                'image'   => $request->image,
            ]);

            return $this->success(
                $supplier->fresh(),
                'Supplier Updated Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function destroy(Supplier $supplier)
    {
        try {

            $supplier->delete();

            return $this->success(
                null,
                'Supplier Deleted Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }
}