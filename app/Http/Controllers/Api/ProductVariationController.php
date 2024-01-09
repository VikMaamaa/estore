<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductVariationRequest;
use App\Models\Shop\ProductVariation;
use App\Services\ProductVariationService;
use App\Traits\ApiResponseTrait;

class ProductVariationController extends Controller
{
    use ApiResponseTrait;

    protected $productVariationService;

    public function __construct(ProductVariationService $productVariationService)
    {
        // $this->middleware('auth:sanctum');
        $this->productVariationService = $productVariationService;
    }

    /**
 * @OA\Get(
 *     path="/api/products/{product}/variations",
 *     summary="Get variations for a specific product",
 *     tags={"Product Variations"},
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
 *         @OA\JsonContent(type="object", example={"success": true, "message": "Product variations retrieved successfully", "data": {}})
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
    public function index(Request $request, Product $product)
    {
        try {
            $variations = $this->productVariationService->getVariationsByProduct($product);

            return $this->successResponse($variations, 'Product variations retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
 * @OA\Post(
 *     path="/api/products/{product}/variations",
 *     summary="Create a new variation for a specific product",
 *     tags={"Product Variations"},
 *     @OA\Parameter(
 *         name="product",
 *         in="path",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "price"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="price", type="number", format="float"),
 *             @OA\Property(property="size", type="string"),
 *             @OA\Property(property="color", type="string"),
 *             @OA\Property(property="weight_unit", type="string"),
 *             @OA\Property(property="weight_value", type="number", format="float"),
 *             @OA\Property(property="height_unit", type="string"),
 *             @OA\Property(property="height_value", type="number", format="float"),
 *             @OA\Property(property="width_unit", type="string"),
 *             @OA\Property(property="width_value", type="number", format="float"),
 *             @OA\Property(property="depth_unit", type="string"),
 *             @OA\Property(property="depth_value", type="number", format="float"),
 *             @OA\Property(property="volume_unit", type="string"),
 *             @OA\Property(property="volume_value", type="number", format="float"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={"success": true, "message": "Product variation created successfully", "data": {}})
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param ProductVariationRequest $request
 * @param Product $product
 * @return JsonResponse
 */
    public function store(ProductVariationRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            $variation = $this->productVariationService->create($product, $data);

            return $this->successResponse($variation, 'Product variation created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

 /**
 * @OA\Get(
 *     path="/api/products/{product}/variations/{variation}",
 *     summary="Get details of a specific product variation",
 *     tags={"Product Variations"},
 *     @OA\Parameter(
 *         name="product",
 *         in="path",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="variation",
 *         in="path",
 *         description="Product Variation ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search parameters for product variation",
 *         required=false,
 *         @OA\JsonContent(type="object", example={"field1": "value1", "field2": "value2"})
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={"success": true, "message": "Product variation retrieved successfully", "data": {}})
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
 * @param ProductVariation $variation
 * @return JsonResponse
 */
    public function show(Request $request, Product $product, ProductVariation $variation)
    {
        try {
            $searchParams = $request->input('search', []);

            $variation = $this->productVariationService->getVariationById($product, $variation->id, $searchParams);

            return $this->successResponse($variation, 'Product variation retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

   /**
 * @OA\Put(
 *     path="/api/products/{product}/variations/{variation}",
 *     summary="Update details of a specific product variation",
 *     tags={"Product Variations"},
 *     @OA\Parameter(
 *         name="product",
 *         in="path",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="variation",
 *         in="path",
 *         description="Product Variation ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", description="Product Variation Name"),
 *             @OA\Property(property="price", type="number", description="Product Variation Price"),
 *             @OA\Property(property="weight", type="number", description="Product Variation Weight"),
 *
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={"success": true, "message": "Product variation updated successfully", "data": {}})
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param ProductVariationRequest $request
 * @param Product $product
 * @param ProductVariation $variation
 * @return JsonResponse
 */
    public function update(ProductVariationRequest $request, Product $product, ProductVariation $variation)
    {
        try {
            $data = $request->validated();
            $updatedVariation = $this->productVariationService->update($variation, $data);

            return $this->successResponse($updatedVariation, 'Product variation updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
 * @OA\Delete(
 *     path="/api/products/{product}/variations/{variation}",
 *     summary="Delete a specific product variation",
 *     tags={"Product Variations"},
 *     @OA\Parameter(
 *         name="product",
 *         in="path",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="variation",
 *         in="path",
 *         description="Product Variation ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={"success": true, "message": "Product variation Deleted successfully", "data": {}})
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param Product $product
 * @param ProductVariation $variation
 * @return JsonResponse
 */
    public function destroy(Product $product, ProductVariation $variation)
    {
        try {
            $this->productVariationService->delete($variation);

            return $this->successResponse([], 'Product variation Deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
