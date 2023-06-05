<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <title>Favorites</title>
    <style>
        h2 {
            text-align: center;
            font-size: 2em;
        }
    </style>
</head>

<body>
    <header>
        <h1><img src="{{ asset('images/logos.png') }}" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="{{ route('recipes.index') }}">Home</a></li>
                <li><a href="{{ route('recipes.create') }}">Recipe</a></li>
                <li><a href="{{ route('categories') }}">Categories</a></li>
                <li><a href="{{ route('favorites') }}" class="active">Favorites</a></li>
                <li><a href="{{ route('profile') }}">Profile</a></li>
                <li><a href="{{ route('logout.auth') }}">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div id="recipeContainer" class="recipe-container">
            @if (!Session::has('user_id'))
                <p>You need to be logged in first to view your favorite recipes</p>
            @elseif ($favorites->isEmpty())
                <p>No favorite recipes found for this user.</p>
            @else
                @foreach ($favorites as $favorite)
                    <div class="recipe">
                        <h3>{{ $favorite->recipe_name }}</h3>
                        <img src="{{ asset('upload/' . $favorite->image) }}"
                            alt="{{ $favorite->recipe_name }}">
                            <p class="description">Description: <br>{!! nl2br(e($recipe->description)) !!}</p>
                            <p>Preparation Time: {{ $recipe->prep_time }} minutes</p>
                            <p>Servings: {{ $recipe->servings }}</p>
                            <p>Ingredients:</p>
                            <ul>
                                @php
                                    $ingredients = explode("\n", $recipe->ingredients);
                                @endphp
                                @foreach ($ingredients as $ingredient)
                                    <li>{{ trim($ingredient) }}</li>
                                @endforeach
                            </ul>
                            <p>Instructions:</p>
                            <ol>
                                @php
                                    $steps = explode("\n", $recipe->instructions);
                                @endphp
                                @foreach ($steps as $step)
                                    <li>{{ trim($step) }}</li>
                                @endforeach
                            </ol>
                        <p class="created-by">Created by:
                            @if ($favorite->user_id == null)
                                Anonymous
                            @else
                                @php
                                    $user = DB::table('users')
                                        ->where('user_id', $favorite->user_id)
                                        ->first();
                                @endphp
                                {{ $user ? $user->user_name : 'Anonymous' }}
                            @endif
                        </p>
                        @if ($favorite->rating == 0)
                            @php
                                $addReviewLink = route('reviews.create', ['recipe_id' => $favorite->recipe_id]);
                            @endphp
                            <a href="{{ $addReviewLink }}">Add Review</a>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </main>
</body>

</html>
