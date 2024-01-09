<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use App\Models\Shop\Brand;
use App\Services\BrandService;
use App\Traits\ApiResponseTrait;

class BrandController extends Controller
{
    use ApiResponseTrait;

    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        // $this->middleware('auth:sanctum');
        $this->brandService = $brandService;
    }

   /**
 * @OA\Get(
 *     path="/api/brands",
 *     summary="Get a list of brands",
 *     tags={"Brands"},
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of items per page (default: 10)",
 *         @OA\Schema(type="integer", default=10)
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number (default: 1)",
 *         @OA\Schema(type="integer", default=1)
 *     ),
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Filter by brand name",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="is_visible",
 *         in="query",
 *         description="Filter by visibility (true/false)",
 *         @OA\Schema(type="boolean")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "success": true,
 *                 "message": "Brands retrieved successfully",
 *                 "data": {
 *                     "current_page": 1,
 *                     "data": {
 *                         {
 *                             "id": 1,
 *                             "name": "Lockman Ltd",
 *                             "slug": "lockman-ltd",
 *                             "website": "https://www.kerluke.com",
 *                             "description": "Bill,' thought Alice,) 'Well, I shan't grow any more--As it is, I suppose?' 'Yes,' said Alice sharply, for she had drunk half the bottle, she found she could see, as she could not even room for her.",
 *                             "position": 0,
 *                             "is_visible": true,
 *                             "seo_title": null,
 *                             "seo_description": null,
 *                             "sort": null,
 *                             "created_at": "2023-06-09T14:02:19.000000Z",
 *                             "updated_at": "2023-08-09T22:31:03.000000Z"
 *                         }
 *                     },
 *                     "first_page_url": "...",
 *                     "from": 1,
 *                     "last_page": 1,
 *                     "... (pagination details)"
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "success": false,
 *                 "message": "Error message",
 *                 "errors": "{...} Validation errors or other errors"
 *             }
 *         )
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

            $brands = $this->brandService->search($filters, $perPage, $page);

            return $this->successResponse($brands, 'Brands retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
 * @OA\Post(
 *     path="/api/brands",
 *     summary="Create a new brand",
 *     tags={"Brands"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Brand creation data",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "name": "Brand Name",
 *                 "slug": "brand-slug",
 *                 "website": "https://www.brand.com",
 *                 "description": "Brand Description",
 *                 "position": 0,
 *                 "is_visible": true,
 *                 "seo_title": "SEO Title",
 *                 "seo_description": "SEO Description",
 *                 "sort": 1
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Brand created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "success": true,
 *                 "message": "Brand created successfully",
 *                 "data": {
 *                     "id": 1,
 *                     "name": "Brand Name",
 *                     "slug": "brand-slug",
 *                     "website": "https://www.brand.com",
 *                     "description": "Brand Description",
 *                     "position": 0,
 *                     "is_visible": true,
 *                     "seo_title": "SEO Title",
 *                     "seo_description": "SEO Description",
 *                     "sort": 1,
 *                     "created_at": "2024-01-08T13:14:29.000000Z",
 *                     "updated_at": "2024-01-08T13:14:29.000000Z"
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "success": false,
 *                 "message": "Validation error",
 *                 "errors": {
 *                     "name": {"The name field is required."},
 *                    " // ... (other validation errors)"
 *                 }
 *             }
 *         )
 *     )
 * )
 *
 * @param BrandRequest $request
 * @return JsonResponse
 */
    public function store(BrandRequest $request)
    {
        try {
            $data = $request->validated();
            $brand = $this->brandService->create($data);

            return $this->successResponse($brand, 'Brand created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
 * @OA\Get(
 *     path="/api/brands/{brand}",
 *     summary="Get details of a specific brand",
 *     tags={"Brands"},
 *     @OA\Parameter(
 *         name="brand",
 *         in="path",
 *         description="Brand ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Brand name",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="slug",
 *         in="query",
 *         description="Brand slug",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="website",
 *         in="query",
 *         description="Brand website",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="description",
 *         in="query",
 *         description="Brand description",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="position",
 *         in="query",
 *         description="Brand position",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="is_visible",
 *         in="query",
 *         description="Brand visibility",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *     @OA\Parameter(
 *         name="seo_title",
 *         in="query",
 *         description="Brand SEO title",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="seo_description",
 *         in="query",
 *         description="Brand SEO description",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="sort",
 *         in="query",
 *         description="Brand sort order",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "success": true,
 *                 "message": "Brand retrieved successfully",
 *                 "data": {
 *                     "id": 1,
 *                     "name": "Brand Name",
 *                     "slug": "brand-slug",
 *                     "website": "https://www.brand.com",
 *                     "description": "Brand Description",
 *                     "position": 1,
 *                     "is_visible": true,
 *                     "seo_title": "Sample SEO Title",
 *                     "seo_description": "Sample SEO Description",
 *                     "sort": 10,
 *                     "created_at": "2024-01-08T13:14:29.000000Z",
 *                     "updated_at": "2024-01-08T13:14:29.000000Z"
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "success": false,
 *                 "message": "Error message",
 *                 "errors": {
 *                    " // ... (validation errors)"
 *                 }
 *             }
 *         )
 *     )
 * )
 *
 * @param Request $request
 * @param Brand $brand
 * @return JsonResponse
 */
    public function show(Request $request, Brand $brand)
    {
        try {
            $searchParams = $request->all();
            $brand = $this->brandService->searchBrand($brand, $searchParams);

            return $this->successResponse($brand, 'Brand retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
 * @OA\Put(
 *     path="/api/brands/{brand}",
 *     summary="Update details of a specific brand",
 *     tags={"Brands"},
 *     @OA\Parameter(
 *         name="brand",
 *         in="path",
 *         description="Brand ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Brand update data",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "name": "Updated Brand Name",
 *                 "slug": "updated-brand-slug",
 *                 "website": "http://www.updatedbrand.com",
 *                 "description": "Updated Brand Description",
 *                 "position": 2,
 *                 "is_visible": true,
 *                 "seo_title": "Updated Brand SEO Title",
 *                 "seo_description": "Updated Brand SEO Description",
 *                 "sort": 150
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "success": true,
 *                 "message": "Brand updated successfully",
 *                 "data": {
 *                     "id": 1,
 *                     "name": "Updated Brand Name",
 *                     "slug": "updated-brand-slug",
 *                     "website": "http://www.updatedbrand.com",
 *                     "description": "Updated Brand Description",
 *                     "position": 2,
 *                     "is_visible": true,
 *                     "seo_title": "Updated Brand SEO Title",
 *                     "seo_description": "Updated Brand SEO Description",
 *                     "sort": 150,
 *                     "created_at": "2024-01-08T13:14:29.000000Z",
 *                     "updated_at": "2024-01-08T13:16:53.000000Z"
 *                 }
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "success": false,
 *                 "message": "Error message",
 *                 "errors": {
 *                     "// ... (validation errors)"
 *                 }
 *             }
 *         )
 *     )
 * )
 *
 * @param BrandRequest $request
 * @param Brand $brand
 * @return JsonResponse
 */
    public function update(BrandRequest $request, Brand $brand)
    {
        try {
            $data = $request->validated();
            $updatedBrand = $this->brandService->update($brand, $data);

            return $this->successResponse($updatedBrand, 'Brand updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/brands/{brand}",
     *     summary="Delete a specific brand",
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         name="brand",
     *         in="path",
     *         description="Brand ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object", example={"success": true, "message": "Brand Deleted successfully", "data": {}})
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error response",
     *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
     *     )
     * )
     *
     * @param Brand $brand
     * @return JsonResponse
     */
    public function destroy(Brand $brand)
    {
        try {
            $this->brandService->delete($brand);

            return $this->successResponse([], 'Brand Deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
