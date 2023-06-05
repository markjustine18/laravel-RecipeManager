<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')->get();

        $recipes = [];

        if (request()->isMethod('post') && request()->has('category_id')) {
            $category_id = request()->input('category_id');

            $recipes = DB::table('recipes')
                ->leftJoin('users', 'recipes.user_id', '=', 'users.user_id')
                ->where('category_id', $category_id)
                ->select('recipes.*', 'users.user_name')
                ->get()
                ->toArray();
        }

        return view('categories', compact('categories', 'recipes'));
    }
}
