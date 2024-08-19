<?php


namespace App\Http\Controllers\Api\Pos;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function getProductsWithPaginate(): array
    {
        $products = Product::with(['createdBy', 'updatedBy'])->orderByDesc('id')->paginate(10);
        return ProductResource::collection($products)->response()->getData(true);
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->getProductsWithPaginate()
        ], Response::HTTP_OK);
    }

    public function store(ProductRequest $request): JsonResponse
    {

        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
            }

            Product::create([
                'user_id' => $data['user_id'],
                'created_by' => $data['created_by'],
                'product_name' => $data['product_name'],
                'brand' => $data['brand'],
                'description' => $data['description'],
                'price' => $data['price'],
                'quantity' => $data['quantity'],
                'alert_stock' => $data['alert_stock'],
                'image' => $imagePath
            ]);
            return response()->json([
                'data' => $this->getProductsWithPaginate(),
                'sucees' => true,
                'message' => 'Product Created successfully.',
            ], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
                'status code' => $th->getCode()
            ], Response::HTTP_BAD_REQUEST);
        }
    }



    public function show(Product $product)
    {
        try {
            return response()->json([
                'data' => ProductResource::make($product),
                'success' => true,
                'message' => 'Get product successfully.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'status' => $th->getCode(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }







    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        try {
            $product = Product::with(['createdBy', 'updatedBy'])->findOrFail($product->id);
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

            $data = $request->validated();
            $product->update([
                'user_id' => $data['user_id'],
                'updated_by' => $data['updated_by'],
                'product_name' => $data['product_name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'quantity' => $data['quantity'],
                'alert_stock' => $data['alert_stock'],
                'brand' => $data['brand'],
                'image' => $imagePath
            ]);

            return response()->json([
                'data' => ProductResource::make(Product::find($product->id)),
                'success' => true,
                'message' => 'Product updated successfully.',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'status' => $th->getCode(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }




    public function destroy(Product $product): JsonResponse
    {
        try {
            if ($product->image) {
                $imagePath = str_replace(Storage::disk('public')->url(''), '', $product->image);
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            $product->delete();
            return response()->json([
                'data' => $this->getProductsWithPaginate(),
                'success' => true,
                'message' => 'Product deleted successfully.',
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'status' => $th->getCode(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
