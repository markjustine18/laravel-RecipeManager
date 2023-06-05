<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function show()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            return redirect('/')->with('message', 'You need to be logged in first to view your profile');
        }

        $user_id = Session::get('user_id');

        $user = DB::table('users')->where('user_id', $user_id)->first();

        if ($user) {
            $recipes = DB::table('recipes')->where('user_id', $user_id)->get();
            $favorites = DB::table('favorites')
                ->join('recipes', 'favorites.recipe_id', '=', 'recipes.recipe_id')
                ->where('favorites.user_id', $user_id)
                ->get();

            return view('profile', compact('user', 'recipes', 'favorites'));
        } else {
            return redirect('/index')->with('message', 'User not found');
        }
    }
}
