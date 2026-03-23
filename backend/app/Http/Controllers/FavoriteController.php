<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favorites = Favorite::with([
        'question' => function ($q) {
                $q->withCount(['answers', 'favorites'])
                ->with('user');
            }
        ])
        ->where('user_id', Auth::id())
        ->get();
        return view('favorites.index', compact('favorites'));
    }

    public function favorite(Question $question)
    {
        $favorite = Favorite::create([
            'user_id' => Auth::id(),
            'question_id' => $question->id
        ]);

        if ($favorite) {
            return redirect()->back()->with('success', 'Question added to favorites.');
        }

        return redirect()->back()->with('error', 'Failed to add question to favorites.');
    }

    public function unfavorite(Question $question)
    {
        $favorite = Favorite::where('user_id', Auth::id())->where('question_id', $question->id)->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Question removed from favorites.');
        }

        return redirect()->back()->with('error', 'Failed to remove question from favorites.');
    }
}
