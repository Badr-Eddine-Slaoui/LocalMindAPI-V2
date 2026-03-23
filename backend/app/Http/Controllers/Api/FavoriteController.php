<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class FavoriteController extends Controller
{

    #[OA\Get(
        path: '/favorites',
        summary: 'Get all favorites',
        tags: ['Favorites'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Favorites retrieved successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the favorites were retrieved successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Favorite', description: 'The retrieved favorites'),
                        new OA\Property(property: 'message', type: 'string', example: 'Favorites retrieved successfully', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false, description: 'Indicates if the favorites were retrieved successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Something went wrong, please try again', description: 'An error message')
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        $favorites = Favorite::with([
        'question' => function ($q) {
                $q->withCount(['answers', 'favorites'])
                ->with('user');
            }
        ])
        ->where('user_id', Auth::guard('api')->id())
        ->get();

        if ($favorites) {
            return response()->json([
                "success" => true,
                "data" => compact("favorites"),
                "message" => "Favorites fetched successfully."
            ], 200);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }

    #[OA\Post(
        path: '/favorites/{question}',
        summary: 'Add a question to favorites',
        tags: ['Favorites'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'question',
                in: 'path',
                required: true,
                description: 'The ID of the question to add to favorites',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Question added to favorites successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the question was added to favorites successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Question added to favorites successfully.', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false, description: 'Indicates if the question was added to favorites successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Favorite', description: 'The created favorite'),
                        new OA\Property(property: 'message', type: 'string', example: 'Something went wrong, please try again', description: 'An error message')
                    ]
                )
            )
        ]
    )]
    public function favorite(Question $question)
    {
        $favorite = Favorite::create([
            'user_id' => Auth::guard('api')->id(),
            'question_id' => $question->id
        ]);

        if ($favorite) {
            return response()->json([
                "success" => true,
                "data" => compact("favorite"),
                "message" => "Question added to favorites successfully."
            ], 201);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }


    #[OA\Delete(
        path: '/favorites/{question}',
        summary: 'Remove a question from favorites',
        tags: ['Favorites'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'question',
                in: 'path',
                required: true,
                description: 'The ID of the question to remove from favorites',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Question removed from favorites successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the question was removed from favorites successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Question removed from favorites successfully.', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false, description: 'Indicates if the question was removed from favorites successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Something went wrong, please try again', description: 'An error message')
                    ]
                )
            )
        ]
    )]
    public function unfavorite(Question $question)
    {
        $favorite = Favorite::where('user_id', Auth::guard('api')->id())->where('question_id', $question->id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                "success" => true,
                "message" => "Question removed from favorites successfully."
            ], 200);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }
}
