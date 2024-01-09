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

    /**
 * @OA\Get(
 *     path="/api/customers",
 *     summary="Get a list of customers",
 *     tags={"Customers"},
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of items per page",
 *         @OA\Schema(type="integer", default=10)
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number",
 *         @OA\Schema(type="integer", default=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={
 *             "success": true,
 *             "message": "Customers retrieved successfully",
 *             "data": {
 *                 "current_page": 1,
 *                 "data": {
 *                     {
 *                         "id": 1,
 *                         "name": "John Doe",
 *                         "email": "john.doe@example.com",
 *                         "photo": "https://example.com/profile.jpg",
 *                         "gender": "male",
 *                         "phone": "+1234567890",
 *                         "birthday": "1990-01-15T00:00:00.000000Z",
 *                         "created_at": "2024-01-08T18:44:16.000000Z",
 *                         "updated_at": "2024-01-08T18:44:16.000000Z",
 *                         "deleted_at": null
 *                     }
 *                 },
 *                 "first_page_url": "http://127.0.0.1:8000/api/customers?page=1",
 *                 "from": 1,
 *                 "last_page": 1,
 *                 "last_page_url": "http://127.0.0.1:8000/api/customers?page=1",
 *                 "links": {
 *                     {
 *                         "url": null,
 *                         "label": "&laquo; Previous",
 *                         "active": false
 *                     },
 *                     {
 *                         "url": "http://127.0.0.1:8000/api/customers?page=1",
 *                         "label": "1",
 *                         "active": true
 *                     },
 *                     {
 *                         "url": null,
 *                         "label": "Next &raquo;",
 *                         "active": false
 *                     }
 *                 },
 *                 "next_page_url": null,
 *                 "path": "http://127.0.0.1:8000/api/customers",
 *                 "per_page": 10,
 *                 "prev_page_url": null,
 *                 "to": 1,
 *                 "total": 1
 *             }
 *         })
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

            $customers = $this->customerService->search($filters, $perPage, $page);

            return $this->successResponse($customers, 'Customers retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

/**
 * @OA\Post(
 *     path="/api/customers",
 *     summary="Create a new customer",
 *     tags={"Customers"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Customer creation data",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "name": "John Doe",
 *                 "email": "john.doe@example.com",
 *                 "photo": "https://example.com/profile.jpg",
 *                 "gender": "male",
 *                 "phone": "+1234567890",
 *                 "birthday": "1990-01-15T00:00:00.000000Z"
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Customer created successfully",
 *         @OA\JsonContent(type="object", example={
 *             "success": true,
 *             "message": "Customer created successfully",
 *             "data": {
 *                 "name": "John Doe",
 *                 "email": "john.doe@example.com",
 *                 "photo": "https://example.com/profile.jpg",
 *                 "gender": "male",
 *                 "phone": "+1234567890",
 *                 "birthday": "1990-01-15T00:00:00.000000Z",
 *                 "created_at": "2024-01-08T18:44:32.000000Z",
 *                 "updated_at": "2024-01-08T18:44:32.000000Z",
 *                 "id": 1
 *             }
 *         })
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param CustomerRequest $request
 * @return JsonResponse
 */
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

    /**
 * @OA\Get(
 *     path="/api/customers/{customer}",
 *     summary="Get details of a specific customer",
 *     tags={"Customers"},
 *     @OA\Parameter(
 *         name="customer",
 *         in="path",
 *         description="Customer ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Customer name",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="Customer email",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="gender",
 *         in="query",
 *         description="Customer gender",
 *         @OA\Schema(type="string", enum={"male", "female"})
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={
 *             "success": true,
 *             "message": "Customer retrieved successfully",
 *             "data": {
 *                 "id": 1,
 *                 "name": "John Doe",
 *                 "email": "john.doe@example.com",
 *                 "photo": "https://example.com/profile.jpg",
 *                 "gender": "male",
 *                 "phone": "+1234567890",
 *                 "birthday": "1990-01-15T00:00:00.000000Z",
 *                 "created_at": "2024-01-08T18:44:16.000000Z",
 *                 "updated_at": "2024-01-08T18:44:16.000000Z",
 *                 "deleted_at": null
 *             }
 *         })
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param Request $request
 * @param Customer $customer
 * @return JsonResponse
 */
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

    /**
 * @OA\Put(
 *     path="/api/customers/{customer}",
 *     summary="Update details of a specific customer",
 *     tags={"Customers"},
 *     @OA\Parameter(
 *         name="customer",
 *         in="path",
 *         description="Customer ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Customer update data",
 *         @OA\JsonContent(
 *             type="object",
 *             example={
 *                 "name": "Updated John Doe",
 *                 "email": "updated.john.doe@example.com",
 *                 "photo": "https://example.com/updated-profile.jpg",
 *                 "gender": "male",
 *                 "phone": "+1234567890",
 *                 "birthday": "1990-01-15"
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={
 *             "success": true,
 *             "message": "Customer updated successfully",
 *             "data": {
 *                 "id": 1,
 *                 "name": "Updated John Doe",
 *                 "email": "updated.john.doe@example.com",
 *                 "photo": "https://example.com/updated-profile.jpg",
 *                 "gender": "male",
 *                 "phone": "+1234567890",
 *                 "birthday": "1990-01-15T00:00:00.000000Z",
 *                 "created_at": "2024-01-08T18:44:16.000000Z",
 *                 "updated_at": "2024-01-08T18:44:16.000000Z",
 *                 "deleted_at": null
 *             }
 *         })
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param CustomerRequest $request
 * @param Customer $customer
 * @return JsonResponse
 */
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

    /**
 * @OA\Delete(
 *     path="/api/customers/{customer}",
 *     summary="Delete a specific customer",
 *     tags={"Customers"},
 *     @OA\Parameter(
 *         name="customer",
 *         in="path",
 *         description="Customer ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(type="object", example={
 *             "success": true,
 *             "message": "Customer Deleted successfully",
 *             "data": {}
 *         })
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error response",
 *         @OA\JsonContent(type="object", example={"success": false, "message": "Error message", "errors": {}})
 *     )
 * )
 *
 * @param Customer $customer
 * @return JsonResponse
 */
    public function destroy(Customer $customer)
    {
        try {
            $this->customerService->delete($customer);

            return $this->successResponse([], 'Customer Deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
