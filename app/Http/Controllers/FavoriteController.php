<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FavoriteController extends Controller
{
    public function add()
    {
        if (!Session::has('user_id')) {
            return 'Not logged in';
        }

        $user_id = Session::get('user_id');
        $recipe_id = request('recipe_id');

        $favorite = DB::table('favorites')
            ->where('user_id', $user_id)
            ->where('recipe_id', $recipe_id)
            ->first();

        if ($favorite) {
            return 'Recipe is already a favorite';
        }

        DB::table('favorites')->insert([
            'user_id' => $user_id,
            'recipe_id' => $recipe_id,
        ]);

        return 'Recipe added to favorites';
    }

    public function remove()
    {
        if (!Session::has('user_id')) {
            return 'Not logged in';
        }

        $user_id = Session::get('user_id');
        $recipe_id = request('recipe_id');

        $favorite = DB::table('favorites')
            ->where('user_id', $user_id)
            ->where('recipe_id', $recipe_id)
            ->first();

        if (!$favorite) {
            return 'Recipe is not a favorite';
        }

        DB::table('favorites')
            ->where('user_id', $user_id)
            ->where('recipe_id', $recipe_id)
            ->delete();

        return 'Recipe removed from favorites';
    }
    public function index()
    {
        // Check if user is logged in
        if (!Session::has('user_id')) {
            return 'You need to be logged in first to view your favorite recipes';
        }

        $user_id = Session::get('user_id');

        $favorites = DB::table('favorites')
            ->join('recipes', 'favorites.recipe_id', '=', 'recipes.recipe_id')
            ->leftJoin('recipe_reviews', function ($join) use ($user_id) {
                $join->on('recipes.recipe_id', '=', 'recipe_reviews.recipe_id')
                    ->where('recipe_reviews.user_id', $user_id);
            })
            ->select(
                'recipes.recipe_id',
                'recipes.recipe_name',
                'recipes.description',
                'recipes.ingredients',
                'recipes.instructions',
                'recipes.image',
                'recipes.servings',
                'recipes.prep_time',
                'recipes.category_id',
                'recipes.user_id',
                DB::raw('IFNULL(recipe_reviews.rating, 0) AS rating')
            )
            ->where('favorites.user_id', $user_id)
            ->get();

        return view('favorites', compact('favorites'));
    }
}
