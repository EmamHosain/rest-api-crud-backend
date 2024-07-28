<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function getProductsWithPaginate()
    {
        return Product::orderByDesc('id')->paginate(10);
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->getProductsWithPaginate()
        ], Response::HTTP_OK);
    }

    public function store(ProductRequest $request): JsonResponse
    {

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }
        Product::create([
            'title' => $request->product_name,
            'short_des' => $request->short_description,
            'price' => $request->price,
            'product_quantity' => $request->product_quantity,
            'image' => $imagePath
        ]);
        return response()->json(['data' => $this->getProductsWithPaginate()], Response::HTTP_CREATED);
    }



    public function show(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'data' => $product
        ], 200);
    }







    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);
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
        } else {
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
            'data' => $product
        ], Response::HTTP_OK);
    }




    public function destroy(Request $request, $id): JsonResponse
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
            'data' => $this->getProductsWithPaginate()
        ], Response::HTTP_OK);
    }
}
