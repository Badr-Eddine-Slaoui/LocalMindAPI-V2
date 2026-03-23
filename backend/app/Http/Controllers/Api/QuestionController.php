<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class QuestionController extends Controller
{
    #[OA\Get(
        path: '/questions',
        summary: 'Get all questions',
        tags: ['Questions'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Questions retrieved successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the questions were retrieved successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Question', description: 'The retrieved questions'),
                        new OA\Property(property: 'message', type: 'string', example: 'Questions retrieved successfully', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false, description: 'Indicates if the questions were retrieved successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Something went wrong, please try again', description: 'An error message')
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        $questions = Question::with(["user", "answers", "answers.user"])->withCount(['answers', 'favorites'])
            ->withExists([
                'favorites as is_favorited' => function ($q) {
                    $q->where('user_id', Auth::guard('api')->id());
                }
            ])
            ->get();

        if ($questions) {
            return response()->json([
                "success" => true,
                "data" => compact("questions"),
                "message" => "Questions fetched successfully."
            ], 200);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }

    #[OA\Post(
        path: '/questions',
        summary: 'Create a new question',
        tags: ['Questions'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'This is my question title.', description: 'The title of the question'),
                    new OA\Property(property: 'description', type: 'string', example: 'This is my question description.', description: 'The description of the question')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Question created successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the question was created successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Question', description: 'The created question'),
                        new OA\Property(property: 'message', type: 'string', example: 'Question created successfully', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false, description: 'Indicates if the question was created successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Something went wrong, please try again', description: 'An error message')
                    ]
                )
            )
        ]
    )]
    public function store(QuestionRequest $request)
    {
        $question = Question::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::guard('api')->id()
        ]);

        if ($question) {
            return response()->json([
                "success" => true,
                "data" => compact("question"),
                "message" => "Question created successfully."
            ], 201);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }

    #[OA\Get(
        path: '/questions/{question}',
        summary: 'Get a specific question',
        tags: ['Questions'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'question',
                in: 'path',
                required: true,
                description: 'The ID of the question to retrieve',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Question retrieved successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the question was retrieved successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Question', description: 'The retrieved question'),
                        new OA\Property(property: 'message', type: 'string', example: 'Question retrieved successfully', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false, description: 'Indicates if the question was retrieved successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Something went wrong, please try again', description: 'An error message')
                    ]
                )
            )
        ]
    )]
    public function show(Question $question)
    {
        $question = $question->withCount(['answers', 'favorites'])
        ->with(["user", "answers", "answers.user"])
        ->withExists([
                'favorites as is_favorited' => function ($q) {
                    $q->where('user_id', Auth::guard('api')->id());
                }
            ])
            ->first();

        if ($question) {
            return response()->json([
                "success" => true,
                "data" => compact("question"),
                "message" => "Question fetched successfully."
            ], 200);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }

    #[OA\Put(
        path: '/questions/{question}',
        summary: 'Update a specific question',
        tags: ['Questions'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'question',
                in: 'path',
                required: true,
                description: 'The ID of the question to update',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Updated question title.', description: 'The updated title of the question.'),
                    new OA\Property(property: 'description', type: 'string', example: 'Updated question description.', description: 'The updated description of the question.')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Question updated successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the question was updated successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Question', description: 'The updated question'),
                        new OA\Property(property: 'message', type: 'string', example: 'Question updated successfully', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false, description: 'Indicates if the question was updated successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Something went wrong, please try again', description: 'An error message')
                    ]
                )
            )
        ]
    )]
    public function update(QuestionRequest $request, Question $question)
    {
        $isUpdated = $question->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($isUpdated) {
            return response()->json([
                "success" => true,
                "data" => compact("question"),
                "message" => "Question updated successfully."
            ]);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ]);

    }

    #[OA\Delete(
        path: '/questions/{question}',
        summary: 'Delete a specific question',
        tags: ['Questions'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'question',
                in: 'path',
                required: true,
                description: 'The ID of the question to delete',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Question deleted successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the question was deleted successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Question deleted successfully.', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false, description: 'Indicates if the question was deleted successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Something went wrong, please try again', description: 'An error message')
                    ]
                )
            )
        ]
    )]
    public function destroy(Question $question)
    {
        $isDeleted = $question->delete();

        if ($isDeleted) {
            return response()->json([
                "success" => true,
                "message" => "Question deleted successfully."
            ]);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ]);

    }
}
