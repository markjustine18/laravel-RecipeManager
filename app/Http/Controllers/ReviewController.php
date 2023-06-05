<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    public function create()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            return redirect('/')->with('message', 'You need to be logged in first to add a review');
        }

        $recipe_id = request('recipe_id');

        return view('add-review', compact('recipe_id'));
    }

    public function store()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            return redirect('/')->with('message', 'You need to be logged in first to add a review');
        }

        $user_id = Session::get('user_id');
        $recipe_id = request('recipe_id');
        $rating = request('rating');
        $comment = request('comment');

        DB::table('recipe_reviews')->insert([
            'user_id' => $user_id,
            'recipe_id' => $recipe_id,
            'rating' => $rating,
            'comment' => $comment,
        ]);

        return redirect('/index')->with('message', 'Review added successfully');
    }
}
