<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthUser;
use Tymon\JWTAuth\Contracts\JWTSubject;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'User',
    title: 'User',
    description: 'User model',
    required: ['name', 'email', 'password'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'John Doe', description: 'The name of the user'),
        new OA\Property(property: 'email', type: 'string', example: '0dMw0@example.com', description: 'The email of the user'),
        new OA\Property(property: 'password', type: 'string', example: 'password', description: 'The password of the user'),
        new OA\Property(property: 'role', type: 'string', example: 'user', description: 'The role of the user'),
    ]
)]
class User extends AuthUser implements JWTSubject
{
    use HasFactory;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role
        ];
    }
}
