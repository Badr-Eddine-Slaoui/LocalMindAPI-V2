<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "LocalMind API",
    version: "1.0.0",
    description: "This is an API documentation for LocalMind."
)]

#[OA\Server(
    url: "http://localhost:3535/api",
    description: "LocalMind API Server"
)]

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Enter your bearer token in the format: Bearer {token}"
)]
abstract class Controller
{
    //
}
