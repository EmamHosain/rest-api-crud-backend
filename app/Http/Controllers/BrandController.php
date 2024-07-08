<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the brands.
     */
    public function index()
    {
        $brands = Brand::all();
        return response()->json([
            'success' => true,
            'brands' => $brands
        ], 200);
    }

    /**
     * Store a newly created brand in storage.
     */
    public function create(Request $request)
    {
        $request->validate([
            'brandName' => 'required|string|max:50|unique:brands',
            'brandImg' => 'required|string|max:300',
        ]);

        $brand = Brand::create([
            'brandName' => $request->brandName,
            'brandImg' => $request->brandImg,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Brand has been created successfully.'
        ], 201);
    }

    /**
     * Display the specified brand.
     */
    public function show($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'category' => $brand
        ], 200);
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        $request->validate([
            'brandName' => 'required|string|max:50|unique:brands,brandName,' . $id,
            'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
            'brandImg' => 'required|string|max:300',
        ]);

        $brand->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Brand has been updated successfully.'
        ], 200);
    }

    /**
     * Remove the specified brand from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand has been deleted successfully'
        ], 200);
    }
}
