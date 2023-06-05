<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <title>View Recipe</title>
</head>

<body>
    <header>
        <h1><img src="{{ asset('images/logos.png') }}" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="{{ route('recipes.index') }}">Home</a></li>
                <li><a href="{{ route('recipes.create') }}">Recipe</a></li>
                <li><a href="{{ route('categories') }}">Categories</a></li>
                <li><a href="{{ route('favorites') }}">Favorites</a></li>
                <li><a href="{{ route('profile') }}" class="active">Profile</a></li>
                <li><a href="{{ route('logout.auth') }}">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="recipe-container">
            <div class="recipe">
                <h3>{{ $recipe->recipe_name }}</h3>
                <img src="{{ asset('upload/' . $recipe->image) }}" alt="{{ $recipe->recipe_name }}">
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
            </div>
        </div>
    </main>
</body>

</html>
