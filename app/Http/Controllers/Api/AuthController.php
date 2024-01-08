<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Traits\ApiResponseTrait;

/**
 * @OA\Info(
 *     title="Estore API",
 *     version="1.0.0",
 *     description="Estore Api",
 *     @OA\Contact(
 *         email="maaamaavictor@gmail.com",
 *         name="Victor Maamaa"
 *     ),
 *
 * )
 */
class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
 * @OA\Post(
 *     path="/api/login",
 *     summary="User login",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Login credentials",
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="maamaavictor@gmail.com"),
 *             @OA\Property(property="password", type="string", format="password", example="your_password_here")
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Login successful"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="user", type="object",
 *                     @OA\Property(property="id", type="integer", example=3),
 *                     @OA\Property(property="name", type="string", example="victor maamaa"),
 *                     @OA\Property(property="email", type="string", format="email", example="maamaavictor@gmail.com"),
 *                     @OA\Property(property="email_verified_at", type="string", format="date-time", example=null),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-08T09:53:41.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-08T09:53:41.000000Z")
 *                 ),
 *                 @OA\Property(property="token", type="string", example="6|YyTbKxM0XQFgMmmYbVv3hOx6O6rRLHdbkiSXKAdLc2e1fe8b")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid credentials"),
 *         )
 *     )
 * )
 */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $result = $this->authService->login($credentials);

            if ($result) {
                return $this->successResponse($result, 'Login successful');
            }

            throw new \Exception('Invalid credentials');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 401);
        }
    }

/**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register a new user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="User registration data",
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="secret123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful registration",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Registration successful"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="user", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="john@example.com"),
 *                     @OA\Property(property="email_verified_at", type="string", format="date-time", example=null),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-08T09:53:41.000000Z"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-08T09:53:41.000000Z")
 *                 ),
 *                 @OA\Property(property="token", type="string", example="6|YyTbKxM0XQFgMmmYbVv3hOx6O6rRLHdbkiSXKAdLc2e1fe8b")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error or registration failure",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="errors", type="object",
 *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required.")),
 *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required.")),
 *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="The password field is required."))
 *             )
 *         )
 *     )
 * )
 *
 * @param RegisterRequest $request
 * @return JsonResponse
 */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $result = $this->authService->register($data);

            return $this->successResponse($result, 'Registration successful');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }


}

