<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/add_reci.css') }}" rel="stylesheet">
    <title>Add Recipe</title>
</head>

<body>
    <header>
        <h1><img src="{{ asset('images/logos.png') }}" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="{{ route('recipes.index') }}">Home</a></li>
                <li><a href="{{ route('recipes.create') }}" class="active">Recipe</a></li>
                <li><a href="{{ route('categories') }}">Categories</a></li>
                <li><a href="{{ route('favorites') }}">Favorites</a></li>
                <li><a href="{{ route('profile') }}">Profile</a></li>
                <li><a href="{{ route('logout.auth') }}">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Add Recipe</h2>
        <form action="{{ route('recipes.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="recipe_name">Recipe Name:</label>
            <input type="text" id="recipe_name" name="recipe_name" required>
            <br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
            <br>
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" rows="4" required></textarea>
            <br>
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" rows="4" required></textarea>
            <br>
            <label for="servings">Servings:</label>
            <input type="number" id="servings" name="servings" required min="0">
            <br>
            <label for="prep_time">Preparation Time (in minutes):</label>
            <input type="number" id="prep_time" name="prep_time" required min="0">
            <br>
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
            <br>
            <input type="submit" value="Add Recipe">
        </form>
    </main>
</body>

</html>
