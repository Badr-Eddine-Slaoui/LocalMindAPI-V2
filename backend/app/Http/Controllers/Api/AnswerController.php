<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class AnswerController extends Controller
{
    #[OA\Post(
        path: '/answers/{question}',
        summary: 'Store a new answer',
        tags: ['Answers'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'question',
                in: 'path',
                required: true,
                description: 'The ID of the question being answered',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'answer', type: 'string', example: 'This is my answer content.', description: 'The text content of the answer.')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Answer created successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the answer was created successfully'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Answer', description: 'The created answer'),
                        new OA\Property(property: 'message', type: 'string', example: 'Answer created successfully.', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Internal Server Error')
        ]
    )]
    public function store(Request $request, Question $question)
    {
        $request->validate([
            'answer' => 'required|string|max:150',
        ]);

        $answer = Answer::create([
            'answer' => $request->answer,
            'question_id' => $question->id,
            'user_id' => Auth::guard('api')->id()
        ]);

        if ($answer) {
            return response()->json([
                "success" => true,
                "data" => compact('answer'),
                "message" => "Answer created successfully."
            ], 200);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }

    #[OA\Put(
        path: '/answers/{answer}',
        summary: 'Update an existing answer',
        tags: ['Answers'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'answer',
                in: 'path',
                required: true,
                description: 'The ID of the answer to update',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'answer', type: 'string', example: 'Updated answer text.', description: 'The updated text content of the answer.')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Answer updated successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Answer', description: 'The updated answer'),
                        new OA\Property(property: 'message', type: 'string', example: 'Answer updated successfully.')
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Answer not found')
        ]
    )]
    public function update(Request $request, Answer $answer)
    {
        $request->validate([
            'answer' => 'required|string|max:150',
        ]);

        $isUpdated = $answer->update([
            'answer' => $request->answer
        ]);

        if ($isUpdated) {
            return response()->json([
                "success" => true,
                "data" => compact('answer'),
                "message" => "Answer updated successfully."
            ], 200);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }

    #[OA\Delete(
        path: '/answers/{answer}',
        summary: 'Delete an answer',
        tags: ['Answers'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'answer',
                in: 'path',
                required: true,
                description: 'The ID of the answer to delete',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Answer deleted successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true, description: 'Indicates if the answer was deleted successfully'),
                        new OA\Property(property: 'message', type: 'string', example: 'Answer deleted successfully.', description: 'A success message')
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Internal Server Error')
        ]
    )]
    public function destroy(Answer $answer)
    {
        if ($answer->delete()) {
            return response()->json([
                "success" => true,
                "message" => "Answer deleted successfully."
            ], 200);
        }

        return response()->json([
            "success" => false,
            "message" => "Something went wrong. Please try again."
        ], 500);
    }
}
