<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

/* Home Page */

Route::get('/', [QuestionController::class, 'index'])->name('home');

/* Auth */

Route::controller(AuthController::class)->middleware(["is_guest"])->group(function () {
    Route::get('/register', "register")->name('register');
    Route::post('/register', "submitRegister")->name('submitRegister');
    Route::get('/login', "login")->name('login');
    Route::post('/login', "submitLogin")->name('submitLogin');
    Route::post('/logout', "logout")->name('logout')->middleware(["authed"])->withoutMiddleware(["is_guest"]);
});

/* Questions */

Route::resource('questions', QuestionController::class)->middleware(["authed"])->withoutMiddlewareFor(["index", "show"], ["authed"]);

/* Favorites */

Route::controller(FavoriteController::class)->middleware(["authed"])->group(function () {
    Route::get('/favorites', "index")->name('favorites.index');
    Route::post('/favorite/{question}', "favorite")->name('favorite');
    Route::post('/unfavorite/{question}', "unfavorite")->name('unfavorite');
});

/* Answers */

Route::controller(AnswerController::class)->middleware(["authed"])->group(function () {
    Route::post('/answer/{question}/store', "store")->name('answers.store');
    Route::put('/answer/{answer}/update', "update")->name('answers.update');
    Route::delete('/answer/{answer}/destroy', "destroy")->name('answers.destroy');
});

/* Admin */

Route::get('/admin', [AdminController::class, 'index'])->middleware(["authed", "admin"])->name('admin');
