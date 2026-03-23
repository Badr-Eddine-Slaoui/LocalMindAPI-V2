<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $questions = Question::with('user')
        ->withCount(['answers'])->get();

        $users_count = User::count();

        return view('admin.index', compact('questions', 'users_count'));
    }
}
