<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseApiController
{
    /**
     * Display Categories
     */
    public function index()
    {
        try {

            $categories = Category::latest()->get();

            return $this->successResponse(
                $categories,
                'Categories Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                500
            );

        }
    }

    /**
     * Store Category
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'name'  => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {

                $imagePath = $request
                    ->file('image')
                    ->store('categories', 'public');
            }

            $category = Category::create([
                'name'  => $request->name,
                'image' => $imagePath,
            ]);

            return $this->successResponse(
                $category,
                'Category Created Successfully',
                201
            );

        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                500
            );

        }
    }

    /**
     * Show Category
     */
    public function show(Category $category)
    {
        try {

            return $this->successResponse(
                $category,
                'Category Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                500
            );

        }
    }

    /**
     * Update Category
     */
    public function update(
        Request $request,
        Category $category
    ) {
        try {

            $request->validate([
                'name'  => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            $imagePath = $category->image;

            if ($request->hasFile('image')) {

                $imagePath = $request
                    ->file('image')
                    ->store('categories', 'public');
            }

            $category->update([
                'name'  => $request->name,
                'image' => $imagePath,
            ]);

            return $this->successResponse(
                $category,
                'Category Updated Successfully'
            );

        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                500
            );

        }
    }

    /**
     * Delete Category
     */
    public function destroy(Category $category)
    {
        try {

            $category->delete();

            return $this->successResponse(
                null,
                'Category Deleted Successfully'
            );

        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage(),
                500
            );

        }
    }

}