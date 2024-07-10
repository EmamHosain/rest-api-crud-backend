<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Response;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate(10)->withQueryString();
        return response()->json([
            'data' => $products
        ], 200);
    }

    public function store(Request $request)
    {

        $request->validate([
            'product_name' => 'required|string|max:200',
            'short_description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'product_quantity' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products/', $imageName, 'public');
        }

        // Create the product
        $product = Product::create([
            'title' => $request->product_name,
            'short_des' => $request->short_description,
            'price' => $request->price,
            'product_quantity' => $request->product_quantity,
            'image' => $imagePath
        ]);
        return response()->json(['product' => $product], 201);
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json([
            'success' => true
        ], 204);
    }
}
