<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\User;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/admin',
    tags: ['Admin'],
    security: [
        ['bearerAuth' => []]
    ],
    summary: 'Get users count',
    responses: [
        new OA\Response(
            response: 200,
            description: 'Successful operation',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean'),
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(property: 'data', type: 'object'),
                ]
            )
        ),
        new OA\Response(
            response: 500,
            description: 'Server error',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean'),
                    new OA\Property(property: 'message', type: 'string'),
                ]
            )
        )
    ]
)]
class AdminController extends Controller
{
    public function index()
    {
        $users_count = User::count();

        if ($users_count) {
            return response()->json([
                "success" => true,
                "data" => compact("users_count"),
                "message" => "Questions fetched successfully."
            ], 200);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }
}
