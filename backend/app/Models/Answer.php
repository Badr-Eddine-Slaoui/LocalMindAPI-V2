<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Answer",
    title: "Answer",
    description: "Answer model schema",
    required: ["answer", "user_id", "question_id"],
    properties: [
        new OA\Property(
            property: "id",
            type: "integer",
            format: "int64",
            example: 1,
            description: "The ID of the answer"
        ),
        new OA\Property(
            property: "answer",
            type: "string",
            description: "The content of the answer",
            example: "This is a detailed answer to the question."
        ),
        new OA\Property(
            property: "user_id",
            type: "integer",
            description: "The ID of the user who wrote the answer",
            example: 5
        ),
        new OA\Property(
            property: "question_id",
            type: "integer",
            description: "The ID of the question being answered",
            example: 12
        ),
        new OA\Property(
            property: "created_at",
            type: "string",
            format: "date-time",
            description: "Creation timestamp",
            example: "2024-03-11T10:00:00Z"
        ),
        new OA\Property(
            property: "updated_at",
            type: "string",
            format: "date-time",
            description: "Last update timestamp",
            example: "2024-03-11T12:00:00Z"
        )
    ]
)]
class Answer extends Model
{
    protected $table = 'answers';

    protected $fillable = [
        'answer',
        'user_id',
        'question_id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
