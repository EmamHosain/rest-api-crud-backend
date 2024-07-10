<?php

namespace App\Http\Controllers;

use Response;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderByDesc('id')->paginate(10)->withQueryString();
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
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }

        // Create the product
        Product::create([
            'title' => $request->product_name,
            'short_des' => $request->short_description,
            'price' => $request->price,
            'product_quantity' => $request->product_quantity,
            'image' => $imagePath
        ]);
        return response()->json(['data' => Product::orderByDesc('id')->paginate(10)->withQueryString()], 201);
    }



    public function show(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'data' => $product
        ], 200);
    }







    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'product_name' => 'required|string|max:200',
            'short_description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'product_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('image')) {
            // delete previous image from folder
            if ($product->image) {
                $imagePath = str_replace(Storage::disk('public')->url(''), '', $product->image);
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            // store image to folder
            $image = $request->file('image');
            $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }else{
            $imagePath = str_replace(Storage::disk('public')->url(''), '', $product->image);
        }

        $product->update([
            'title' => $request->product_name,
            'short_des' => $request->short_description,
            'price' => $request->price,
            'product_quantity' => $request->product_quantity,
            'image' => $imagePath
        ]);

        return response()->json([
            'data' => Product::orderByDesc('id')->paginate(10)->withQueryString()
        ], 200);
    }




    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            $imagePath = str_replace(Storage::disk('public')->url(''), '', $product->image);
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
        $product->delete();
        return response()->json([
            'data' => Product::orderByDesc('id')->paginate(10)->withQueryString()
        ], 200);
    }
}
