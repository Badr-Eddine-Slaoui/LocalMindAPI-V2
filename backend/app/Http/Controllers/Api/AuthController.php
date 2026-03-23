<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/register',
        summary: 'Register a new user',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe', description: 'The name of the user'),
                    new OA\Property(property: 'email', type: 'string', example: 'VXOuZ@example.com', description: 'The email address of the user'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123', description: 'The password of the user'),
                    new OA\Property(property: 'password_confirmation', type: 'string', example: 'password123', description: 'The password confirmation of the user'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User registered successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Registration successful')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Failed to register')
                    ]
                )
            )
        ]
    )]
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'email' => 'required|string|max:150|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return response()->json([
                'message' => 'Registration successful'
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to register'
        ], 500);
    }

    #[OA\Post(
        path: '/login',
        summary: 'Login a user',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'VXOuZ@example.com', description: 'The email address of the user'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123', description: 'The password of the user'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User logged in successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', type: 'string', example: 'access_token'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid credentials')
                    ]
                )
            )
        ]
    )]
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => Auth::guard('api')->user()->load(['favorites', 'questions'])
        ],200);
    }

    #[OA\Get(
        path: '/me',
        summary: 'Get the authenticated user',
        tags: ['Authentication'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User retrieved successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'user', ref: '#/components/schemas/User', description: 'The authenticated user'),
                        new OA\Property(property: 'message', type: 'string', example: 'User retrieved successfully', description: 'A success message')
                    ]
                )
            )
        ]
    )]
    public function me()
    {
        return response()->json([
            'user' => Auth::guard('api')->user()->load(['favorites', 'questions']),
            'message' => 'User retrieved successfully'
        ], 200);
    }

    #[OA\Post(
        path: '/logout',
        summary: 'Logout the user',
        tags: ['Authentication'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logged out successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Logged out successfully', description: 'A success message')
                    ]
                )
            )
        ]
    )]
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
