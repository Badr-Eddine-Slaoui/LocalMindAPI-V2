<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Favorite',
    title: 'Favorite',
    description: 'Favorite model',
    required: ['user_id', 'question_id'],
    properties: [
        new OA\Property(
            property: "id",
            type: "integer",
            format: "int64",
            example: 1,
            description: "The ID of the favorite"
        ),
        new OA\Property(property: 'user_id', type: 'integer', example: 1, description: 'The ID of the user who favorited the question'),
        new OA\Property(property: 'question_id', type: 'integer', example: 1, description: 'The ID of the question being favorited'),
    ]
)]
class Favorite extends Model
{
    protected $table = 'favorites';

    protected $fillable = [
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
