<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     description="Register a new user with strict validation. Password must be at least 8 characters, containing uppercase, lowercase letters and numbers. Mobile number must follow the Iranian format.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "mobile", "password", "password_confirmation"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 minLength=5,
     *                 maxLength=100,
     *                 example="Nirvana"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="nirvana@example.com"
     *             ),
     *             @OA\Property(
     *                 property="mobile",
     *                 type="string",
     *                 pattern="^(\+98|0)?9\d{9}$",
     *                 example="09123456789",
     *                 description="Valid Iranian mobile number starting with 09 or +98 and followed by 9 digits."
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 minLength=8,
     *                 example="Test1234",
     *                 description="Minimum 8 characters, including uppercase, lowercase letters and numbers."
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 format="password",
     *                 example="Test1234",
     *                 description="Must be identical to the password field."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Nirvana"),
     *                 @OA\Property(property="email", type="string", example="nirvana@example.com"),
     *                 @OA\Property(property="mobile", type="string", example="09123456789"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The mobile field format is invalid. (and 3 more errors)"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="mobile", type="array",
     *                     @OA\Items(type="string", example="The mobile field format is invalid.")
     *                 ),
     *                 @OA\Property(property="password", type="array",
     *                     @OA\Items(type="string", example="The password field must be at least 8 characters."),
     *                     @OA\Items(type="string", example="The password field must contain at least one uppercase and one lowercase letter."),
     *                     @OA\Items(type="string", example="The password confirmation does not match.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|min:5',
            'email' => 'required|email|unique:users,email',
            'mobile' => [
                'required',
                'string',
                'regex:/^(\+98|0)?9\d{9}$/',
                'unique:users,mobile'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user and get token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="nirvana@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Test1234!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful, token returned",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJK..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     description="Requires a Bearer token in the Authorization header to log out the current user.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized – Invalid or missing token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get the authenticated user's information",
     *     description="Returns the user info for the currently authenticated user. Requires a valid Bearer token in the Authorization header.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User data retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="nirvana"),
     *             @OA\Property(property="email", type="string", example="nirvana@example.com"),
     *             @OA\Property(property="mobile", type="string", example="09123456789"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized – Token missing or invalid",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function user()
    {
        return response()->json(Auth::guard('api')->user());
    }
}
