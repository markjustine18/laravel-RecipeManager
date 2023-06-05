<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        $recipes = DB::table('recipes')
            ->leftJoin('favorites', function ($join) use ($user_id) {
                $join
                    ->on('recipes.recipe_id', '=', 'favorites.recipe_id')
                    ->where('favorites.user_id', $user_id);
            })
            ->select(
                'recipes.*',
                DB::raw('IF(favorites.recipe_id IS NULL, 0, 1) AS is_favorite')
            )
            ->get();

        return view('index', ['recipes' => $recipes]);
    }

    public function index1()
    {
        $recipes = DB::table('recipes')->get();

        return view('index1', compact('recipes'));
    }

    public function edit($id)
    {
        $recipe = DB::table('recipes')
            ->where('recipe_id', $id)
            ->first();
        $categories = DB::table('categories')->get();

        return view('edit-recipe', compact('recipe', 'categories'));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $recipeName = $request->input('recipe_name');
            $description = $request->input('description');
            $ingredients = $request->input('ingredients');
            $instructions = $request->input('instructions');
            $servings = $request->input('servings');
            $prepTime = $request->input('prep_time');
            $categoryId = $request->input('category_id');

            $userId = Session::get('user_id');

            $image = $request->file('image');
            $imagePath = 'C:/xampp/htdocs/laravel-RecipeManager/public/upload/';
            $imageName = $image->getClientOriginalName();
            $image->move($imagePath, $imageName);

            DB::table('recipes')->insert([
                'recipe_name' => $recipeName,
                'description' => $description,
                'ingredients' => $ingredients,
                'instructions' => $instructions,
                'image' => $imageName,
                'servings' => $servings,
                'prep_time' => $prepTime,
                'category_id' => $categoryId,
                'user_id' => $userId,
            ]);

            return redirect()->route('recipes.index');
        }

        $categories = DB::table('categories')->get();

        return view('add-recipe', ['categories' => $categories]);
    }

    public function update(Request $request, $id)
    {
        $recipe_name = $request->input('recipe_name');
        $description = $request->input('description');
        $prep_time = $request->input('prep_time');
        $servings = $request->input('servings');
        $category_id = $request->input('category_id');
        $ingredients = $request->input('ingredients');
        $instructions = $request->input('instructions');

        if ($request->hasFile('new_image')) {
            $newImage = $request->file('new_image');
            $newImage->move(
                public_path('upload'),
                $newImage->getClientOriginalName()
            );

            DB::table('recipes')
                ->where('recipe_id', $id)
                ->update(['image' => $newImage->getClientOriginalName()]);
        }

        DB::table('recipes')
            ->where('recipe_id', $id)
            ->update([
                'recipe_name' => $recipe_name,
                'description' => $description,
                'prep_time' => $prep_time,
                'servings' => $servings,
                'category_id' => $category_id,
                'ingredients' => $ingredients,
                'instructions' => $instructions,
            ]);

        return redirect()->route('recipes.index');
    }

    public function delete($id)
    {
        DB::table('recipes')
            ->where('recipe_id', $id)
            ->delete();

        return redirect()->route('recipes.index');
    }

    public function show($recipe_id)
    {
        $recipe = DB::table('recipes')
            ->where('recipe_id', $recipe_id)
            ->first();

        if ($recipe) {
            $ingredients = explode(',', $recipe->ingredients);
            $steps = explode(',', $recipe->instructions);

            return view(
                'view-recipe',
                compact('recipe', 'ingredients', 'steps')
            );
        } else {
            return redirect('/index')->with('message', 'Recipe not found');
        }
    }
}
