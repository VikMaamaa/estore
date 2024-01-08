<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Shop\Product;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;

class ProductController extends Controller
{
    use ApiResponseTrait;

    protected $productService;

    public function __construct(ProductService $productService)
    {
        // $this->middleware('auth:sanctum');
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->all();

            // Set the default per page value or use the one provided in the request
            $perPage = $request->input('per_page', 10);

            // Set the default page value or use the one provided in the request
            $page = $request->input('page', 1);

            $products = $this->productService->search($filters, $perPage, $page);

            return $this->successResponse($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();
            $product = $this->productService->create($data);

            return $this->successResponse($product, 'Product created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function show(Request $request, Product $product)
    {
        try {
            $searchParams = $request->all();
            $product = $this->productService->searchProduct($product, $searchParams);

            return $this->successResponse($product, 'Product retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            $updatedProduct = $this->productService->update($product, $data);

            return $this->successResponse($updatedProduct, 'Product updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->productService->delete($product);

            return $this->successResponse([], 'Product soft deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
