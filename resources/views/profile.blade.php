<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/style.css?v=1') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/profile.css?v=1') }}">
    <title>Profile</title>
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
    <div class="profile">
        <h2>Welcome, {{ $user->user_name }}!</h2>
        <p>Email: {{ $user->email }}</p>

        <h3>My Recipes</h3>
        @if ($recipes->count() > 0)
            <ul>
                @foreach ($recipes as $recipe)
                    <li><a
                            href="{{ route('recipes.view', ['recipe_id' => $recipe->recipe_id]) }}">{{ $recipe->recipe_name }}</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>You haven't added any recipes yet.</p>
        @endif

        <h3>My Favorites</h3>
        @if ($favorites->count() > 0)
            <ul>
                @foreach ($favorites as $favorite)
                    <li><a
                            href="{{ route('recipes.view', ['recipe_id' => $favorite->recipe_id]) }}">{{ $favorite->recipe_name }}</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>You haven't added any favorites yet.</p>
        @endif
    </div>
</main>
</body>

</html>
