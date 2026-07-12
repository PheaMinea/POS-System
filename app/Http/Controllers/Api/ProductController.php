<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends BaseApiController
{
    public function index()
    {
        try {

            $products = Product::with('category')
                ->latest()
                ->get();

            return $this->success(
                $products,
                'Products Retrieved Successfully'
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
                'category_id' => 'required|exists:categories,id',
                'name'        => 'required|string|max:255',
                'price'       => 'required|numeric|min:0',
                'stock'       => 'required|integer|min:0',
                'image'       => 'nullable|image|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product = Product::create([
                'category_id' => $request->category_id,
                'name'        => $request->name,
                'price'       => $request->price,
                'stock'       => $request->stock,
                'image'       => $imagePath,
            ]);

            return $this->success(
                $product->load('category'),
                'Product Created Successfully',
                201
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function show(Product $product)
    {
        try {

            return $this->success(
                $product->load('category'),
                'Product Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function update(Request $request, Product $product)
    {
        try {

            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name'        => 'required|string|max:255',
                'price'       => 'required|numeric|min:0',
                'stock'       => 'required|integer|min:0',
                'image'       => 'nullable|image|max:2048',
            ]);

            $imagePath = $product->image;
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product->update([
                'category_id' => $request->category_id,
                'name'        => $request->name,
                'price'       => $request->price,
                'stock'       => $request->stock,
                'image'       => $imagePath,
            ]);

            return $this->success(
                $product->fresh()->load('category'),
                'Product Updated Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function destroy(Product $product)
    {
        try {

            $product->delete();

            return $this->success(
                null,
                'Product Deleted Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }
}