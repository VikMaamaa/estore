<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Models\Shop\Customer;
use App\Services\CustomerService;
use App\Traits\ApiResponseTrait;

class CustomerController extends Controller
{
    use ApiResponseTrait;

    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        // $this->middleware('auth:sanctum');
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->all();

            // Set the default per page value or use the one provided in the request
            $perPage = $request->input('per_page', 10);

            // Set the default page value or use the one provided in the request
            $page = $request->input('page', 1);

            $customers = $this->customerService->search($filters, $perPage, $page);

            return $this->successResponse($customers, 'Customers retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function store(CustomerRequest $request)
    {
        try {
            $data = $request->validated();
            $customer = $this->customerService->create($data);

            return $this->successResponse($customer, 'Customer created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function show(Request $request, Customer $customer)
    {
        try {
            $searchParams = $request->all();
            $customer = $this->customerService->searchCustomer($customer, $searchParams);

            return $this->successResponse($customer, 'Customer retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        try {
            $data = $request->validated();
            $updatedCustomer = $this->customerService->update($customer, $data);

            return $this->successResponse($updatedCustomer, 'Customer updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $this->customerService->delete($customer);

            return $this->successResponse([], 'Customer soft deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
