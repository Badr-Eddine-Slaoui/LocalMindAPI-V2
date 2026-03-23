<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Question $question)
    {
        $request->validate([
            'answer' => 'required|string|max:150',
        ]);

        $answer = Answer::create([
            'answer' => $request->answer,
            'question_id' => $question->id,
            'user_id' => Auth::id()
        ]);

        if ($answer) {
            return redirect()->back()->with('success', 'Answer submitted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to submit answer.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        $request->validate([
            'answer' => 'required|string|max:150',
        ]);

        $isUpdated = $answer->update([
            'answer' => $request->answer
        ]);

        if ($isUpdated) {
            return redirect()->back()->with('success', 'Answer updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update answer.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answer $answer)
    {
        $isDeleted = $answer->delete();

        if ($isDeleted) {
            return redirect()->back()->with('success', 'Answer deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete answer.');
    }
}
