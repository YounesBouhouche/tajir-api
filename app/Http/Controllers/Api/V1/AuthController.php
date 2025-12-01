<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Service\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for Authentication",
 * )
 */
class AuthController extends BaseController
{
    public function index()
    {
        # Solve N+1 problem
        $user = Auth::user()->load(['cart', 'orders', 'products', 'shippingAddresses', 'ratings']);
        return $this->sendResponse(new UserResource($user), extra: ['token' => $user->createToken('App')->plainTextToken]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Login user and retrieve authentication token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="email@example.com"),
     *        @OA\Property(property="password", type="string", format="password", example="password123"),
     *     )
     *   ),
     *     @OA\Response(
     *     response=200,
     *     description="Successful login",
     *     @OA\JsonContent(
     *     @OA\Property(property="success", type="boolean", example=true),
     *     @OA\Property(property="data", ref="#/components/schemas/User"),
     *     @OA\Property(
     *     property="extra",
     *     type="object",
     *     @OA\Property(property="token", type="string", example="token_value")
     *     )
     *  )
     * ),
     *     @OA\Response(
     *     response=400,
     *     description="Validation error",
     *     ),
     *     @OA\Response(
     *     response=401,
     *     description="Authentication error",
     *     )
     * )
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        // Login attempt
        $data = $validator->getData();
        if (!Auth::attempt($data)) {
            return $this->sendError('Authentication error', ['error' => 'Incorrect credentials'], 401);
        }

        // Return token
        $user = Auth::user();
        $token = $user->createToken('App')->plainTextToken;
        return $this->sendResponse(UserResource::make($user), 'Logged in', extra: ['token' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","confirm-password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="example@domain.com"),
     *        @OA\Property(property="password", type="string", format="password", example="password123"),
     *     @OA\Property(property="confirm-password", type="string", format="password", example="password123"),
     *        @OA\Property(property="image", type="string", format="binary", description="Profile image file (optional)")
     *    )
     *  ),
     *     @OA\Response(
     *     response=201,
     *     description="User registered successfully",
     *     @OA\JsonContent(
     *     @OA\Property(property="success", type="boolean", example=true),
     *     @OA\Property(property="data", ref="#/components/schemas/User"),
     *     @OA\Property(
     *     property="extra",
     *     type="object",
     *     @OA\Property(property="token", type="string", example="token_value")
     *    )
     * )
     * ),
     *     @OA\Response(
     *     response=400,
     *     description="Validation error",
     *     )
     * )
     */
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed:confirm-password',
            'confirm-password' => 'required|same:password',
            'image' => 'file|nullable|image|max:2048',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        $data = array_merge(
            $validator->getData(),
            ['password' => bcrypt($validator->getData()['password'])]
        );
        unset($data['confirm-password']);

        if (isset($data['image'])) {
            $data['image'] = app('image.product')->store($data['image']);
        }

        $user = User::create($data);
        $token = $user->createToken('App')->plainTextToken;

        return $this->sendResponse(
            UserResource::make($user),
            'Registered',
            201,
            ['token' => $token]
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logout user and revoke authentication tokens",
     *     tags={"Auth"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="string", example="Success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return $this->sendResponse("Success");
    }
}
