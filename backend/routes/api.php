<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Auth */

Route::controller(AuthController::class)->middleware(["is_guest:api"])->group(function () {
    Route::post('/register', "register");
    Route::post('/login', "login");
    Route::middleware(["authed:api"])->withoutMiddleware(["is_guest:api"])->group(function () {
        Route::get('/me', "me");
        Route::post('/logout', "logout");
    });
});

/* Questions */

Route::apiResource('questions', QuestionController::class)
    ->names([
        'index' => 'api.questions.index',
        'store' => 'api.questions.store',
        'show' => 'api.questions.show',
        'update' => 'api.questions.update',
        'destroy' => 'api.questions.destroy',
    ])
    ->middleware(["authed:api"])
    ->withoutMiddlewareFor(["index", "show"], ["authed:api"]);

/* Favorites */

Route::prefix('favorites')->controller(FavoriteController::class)->middleware(["authed:api"])->group(function () {
    Route::get('/', "index");
    Route::post('/{question}', "favorite");
    Route::delete('/{question}', "unfavorite");
});

/* Answers */

Route::prefix('answers')->controller(AnswerController::class)->middleware(["authed:api"])->group(function () {
    Route::post('/{question}', "store");
    Route::put('/{answer}', "update");
    Route::delete('/{answer}', "destroy");
});

/* Admin */

Route::get('/admin', [AdminController::class, 'index'])->middleware(["authed:api", "admin:api"]);

/* Health */

Route::get('/health', fn () => response()->json(['status' => 'ok']));
