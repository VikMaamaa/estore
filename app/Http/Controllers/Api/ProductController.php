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

    /**
 * @OA\Get(
 *     path="/api/products",
 *     summary="List and search products",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of items per page",
 *         required=false,
 *         @OA\Schema(type="integer", default=10)
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number",
 *         required=false,
 *         @OA\Schema(type="integer", default=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={"success": true, "message": "Products retrieved successfully", "data": {}})
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param Request $request
 * @return JsonResponse
 */
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

  /**
 * @OA\Post(
 *     path="/api/products",
 *     summary="Create a new product",
 *     tags={"Products"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="shop_brand_id", type="integer"),
 *             @OA\Property(property="slug", type="string"),
 *
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={"success": true, "message": "Product created successfully", "data": {}})
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param ProductRequest $request
 * @return JsonResponse
 */
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

    /**
 * @OA\Get(
 *     path="/api/products/{product}",
 *     summary="Get details of a specific product",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="product",
 *         in="path",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={"success": true, "message": "Product retrieved successfully", "data": {}})
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param Request $request
 * @param Product $product
 * @return JsonResponse
 */
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

    /**
 * @OA\Delete(
 *     path="/api/products/{product}",
 *     summary="Delete a specific product",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="product",
 *         in="path",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={"success": true, "message": "Product Deleted successfully"})
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param Product $product
 * @return JsonResponse
 */
    public function destroy(Product $product)
    {
        try {
            $this->productService->delete($product);

            return $this->successResponse([], 'Product Deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
