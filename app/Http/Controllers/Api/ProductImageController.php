<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductImageUploadRequest;
use App\Services\ProductService;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function upload(ProductImageUploadRequest $request, $productId)
    {
        try {
            $product = $this->productService->findProductById($productId);

            $imagePath = $this->storeImage($request->file('image'));

            // Attach the image to the product using the media library
            $product->addMedia(storage_path('app/public/' . $imagePath))
                    ->toMediaCollection('product_images');

            return response()->json(['message' => 'Image uploaded successfully']);
        } catch (\Exception $e) {
            // Log the error or report it as needed
            return response()->json(['error' => 'Failed to upload image. ' . $e->getMessage()], 500);
        }
    }

    protected function storeImage($file)
    {
        return $file->store('product_images', 'public');
    }
}
