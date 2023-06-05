<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/edit_reci.css') }}" rel="stylesheet">
    <title>Edit Recipe</title>
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
        <h2>Edit Recipe</h2>
        <form action="{{ route('recipes.update', ['id' => $recipe->recipe_id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <label for="recipeName">Recipe Name:</label>
            <input type="text" id="recipeName" name="recipe_name" value="{{ $recipe->recipe_name }}" required>
            <br>
            <label for="image">Image:</label>
            <input type="file" id="image" name="new_image" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required>{{ $recipe->description }}</textarea>
            <br>
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" rows="4" required>{{ $recipe->ingredients }}</textarea>
            <br>
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" rows="4" required>{{ $recipe->instructions }}</textarea>
            <br>
            <label for="prepTime">Preparation Time (minutes):</label>
            <input type="number" id="prepTime" name="prep_time" value="{{ $recipe->prep_time }}" required
                min="0">
            <br>
            <label for="servings">Servings:</label>
            <input type="number" id="servings" name="servings" value="{{ $recipe->servings }}" required
                min="0">
            <br>
            <label for="categoryId">Category:</label>
            <select id="categoryId" name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->category_id }}"
                        {{ $category->category_id == $recipe->category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
            <br>
            <input type="submit" value="Update Recipe">
            <input type="button" value="Back" onclick="window.location.href='{{ route('recipes.index') }}';">
        </form>
    </main>

</body>

</html>
