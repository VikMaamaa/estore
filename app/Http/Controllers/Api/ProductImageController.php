<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductImageUploadRequest;
use App\Services\ProductService;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponseTrait;

class ProductImageController extends Controller
{
    use ApiResponseTrait;

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Upload an image for a specific product.
     *
     * @OA\Post(
     *     path="/api/products/{productId}/upload-image",
     *     summary="Upload an image for a product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Image upload data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="image",
     *                     description="Image file",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Image uploaded successfully",
     *         @OA\JsonContent(type="object", example={"message": "Image uploaded successfully"})
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error response",
     *         @OA\JsonContent(type="object", example={"error": "Failed to upload image. Error message"})
     *     )
     * )
     *
     * @param ProductImageUploadRequest $request
     * @param int $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(ProductImageUploadRequest $request, $productId)
    {
        try {
            $product = $this->productService->findProductById($productId);

            $imagePath = $this->storeImage($request->file('image'));

            // Attach the image to the product using the media library
            $product->addMedia(storage_path('app/public/' . $imagePath))
                    ->toMediaCollection('product_images');

            return $this->successResponse([], 'Image uploaded successfully');
        } catch (\Exception $e) {
            // Log the error or report it as needed
            return $this->errorResponse('Failed to upload image. ' . $e->getMessage(), 500);
        }
    }

    protected function storeImage($file)
    {
        return $file->store('product_images', 'public');
    }
}
