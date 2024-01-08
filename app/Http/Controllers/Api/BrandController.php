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

    public function destroy(Brand $brand)
    {
        try {
            $this->brandService->delete($brand);

            return $this->successResponse([], 'Brand soft deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
