<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/categ.css') }}" rel="stylesheet">
    <title>Categories</title>
</head>

<body>
    <header>
        <h1><img src="{{ asset('images/logos.png') }}" alt="Logo">Recipe Manager</h1>
        <nav>
            <ul>
                <li><a href="{{ route('recipes.index') }}">Home</a></li>
                <li><a href="{{ route('recipes.create') }}">Recipe</a></li>
                <li><a href="{{ route('categories') }}" class="active">Categories</a></li>
                <li><a href="{{ route('favorites') }}">Favorites</a></li>
                <li><a href="{{ route('profile') }}">Profile</a></li>
                <li><a href="{{ route('logout.auth') }}">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Recipe Categories</h2>
        <form method="post">
            @csrf
            <select id="categorySelect" name="category_id">
                @foreach ($categories as $category)
                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
            <input type="submit" value="Submit">
        </form>
        @if (isset($recipes) && !empty($recipes))
            <div class="recipe-container">
                @foreach ($recipes as $recipe)
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
                        <div class="com">
                            <p>Comments</p>
                            @if (Session::has('user_id'))
                                @php
                                    $user_id = Session::get('user_id');
                                    $query_comment = "SELECT * FROM recipe_reviews WHERE recipe_id = '{$recipe->recipe_id}' AND user_id = '$user_id'";
                                    $result_comment = DB::select($query_comment);
                                    if (count($result_comment) > 0) {
                                        $row_comment = $result_comment[0];
                                    }
                                @endphp
                                @if (isset($row_comment))
                                    <div class="comment">
                                        <p class="username">You:</p>
                                        <p class="comment-text">{{ $row_comment->comment }}</p>
                                    </div>
                                @endif
                            @endif
                            @php
                                $query_comments = "SELECT r.*, u.user_name FROM recipe_reviews r INNER JOIN users u ON r.user_id = u.user_id WHERE r.recipe_id = '{$recipe->recipe_id}' ORDER BY r.created_at DESC LIMIT 3";
                                $result_comments = DB::select($query_comments);
                            @endphp
                            @if (count($result_comments) > 0)
                                @foreach ($result_comments as $row_comment)
                                    @if (!isset($user_id) || $row_comment->user_id != $user_id)
                                        <div class="comment">
                                            <p class="username">{{ $row_comment->user_name }}:</p>
                                            <p class="comment-text">{{ $row_comment->comment }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <p class="created-by">Created by:
                            @if ($recipe->user_id == null)
                                Anonymous
                            @else
                                @php
                                    $user = DB::table('users')
                                        ->select('user_name')
                                        ->where('user_id', $recipe->user_id)
                                        ->first();
                                @endphp
                                @if ($user)
                                    {{ $user->user_name }}
                                @else
                                    Anonymous
                                @endif
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>

</html>
