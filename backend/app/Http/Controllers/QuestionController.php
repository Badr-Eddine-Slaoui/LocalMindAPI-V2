<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::withCount(['answers', 'favorites'])
            ->withExists([
                'favorites as is_favorited' => function ($q) {
                    $q->where('user_id', Auth::id());
                }
            ])
            ->get();
        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionRequest $request)
    {
        $question = Question::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id()
        ]);

        if ($question) {
            return redirect()->route('questions.index')->with('success', 'Question created successfully.');
        }

        return redirect()->back()->with('error', 'Failed to create question.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        $question = $question->withCount(['answers', 'favorites'])
        ->with(["user", "answers", "answers.user"])
        ->withExists([
                'favorites as is_favorited' => function ($q) {
                    $q->where('user_id', Auth::id());
                }
            ])
            ->first();
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        return view('questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'title' => 'required|min:3|max:100|string',
            'description' => 'required|min:3|string',
        ]);

        $isUpdated = $question->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($isUpdated) {
            return redirect()->route('questions.index')->with('success', 'Question updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update question.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $isDeleted = $question->delete();

        if ($isDeleted) {
            return redirect()->back()->with('success', 'Question deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete question.');
    }
}
