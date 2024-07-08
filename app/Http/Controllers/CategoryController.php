<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'categories' => $categories
        ], 200);
    }

    /**
     * Store a newly created category in storage.
     */
    public function create(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|string|max:50|unique:categories',
            'categoryImg' => 'required|string|max:300',
        ]);

        Category::create([
            'categoryName' => $request->categoryName,
            'categoryImg' => $request->categoryImg,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category has been created successfully.'
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'category' => $category
        ], 200);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $request->validate([
            'categoryName' => 'sometimes|required|string|max:50|unique:categories,categoryName,' . $id,
            'categoryImg' => 'sometimes|required|string|max:300',
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category has been updated successfully.'
        ], 200);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category has been deleted successfully',
        ], 200);
    }

}
