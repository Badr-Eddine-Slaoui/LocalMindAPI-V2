<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Question',
    title: 'Question',
    description: 'Question model',
    required: ['title', 'description', 'user_id'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1, format: 'int64', description: 'The ID of the question'),
        new OA\Property(property: 'title', type: 'string', example: 'What is Laravel?', description: 'The title of the question'),
        new OA\Property(property: 'description', type: 'string', example: 'Laravel is a web application framework with expressive, elegant syntax.', description: 'The description of the question'),
        new OA\Property(property: 'user_id', type: 'integer', example: 1, format: 'int64', description: 'The ID of the user who asked the question'),
    ]
)]
class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = ['title', 'description', 'user_id'];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
