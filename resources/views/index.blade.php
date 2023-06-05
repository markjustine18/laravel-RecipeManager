<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <title>Home</title>
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
                <li><a href="{{ route('recipes.index') }}" class="active">Home</a></li>
                <li><a href="{{ route('recipes.create') }}">Recipe</a></li>
                <li><a href="{{ route('categories') }}">Categories</a></li>
                <li><a href="{{ route('favorites') }}">Favorites</a></li>
                <li><a href="{{ route('profile') }}">Profile</a></li>
                <li><a href="{{ route('logout.auth') }}">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="recipe-container" id="recipeContainer">
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
                    <div class="rate">
                        @if (Session::has('user_id'))
                            @php
                                $recipe_id = $recipe->recipe_id;
                                $user_id = Session::get('user_id');
                                $rating = DB::table('recipe_reviews')
                                    ->select('rating')
                                    ->where('recipe_id', $recipe_id)
                                    ->where('user_id', $user_id)
                                    ->first();
                            @endphp
                            @if ($rating)
                                <p>Your Rating:</p>
                                <div class="stars">
                                    @for ($i = 0; $i < $rating->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="ratings">
                        @php
                            $recipe_id = $recipe->recipe_id;
                            $avg_rating = DB::table('recipe_reviews')
                                ->where('recipe_id', $recipe_id)
                                ->avg('rating');
                            $avg_rating = round($avg_rating, 1);
                        @endphp
                        <p>Average Rating:</p>
                        <div class="stars">
                            @for ($i = 0; $i < $avg_rating; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="com">
                        <p>Comments</p>
                        @if (Session::has('user_id'))
                            @php
                                $user_id = Session::get('user_id');
                                $user_comment = DB::table('recipe_reviews')
                                    ->where('recipe_id', $recipe->recipe_id)
                                    ->where('user_id', $user_id)
                                    ->first();
                            @endphp
                            @if ($user_comment)
                                <div class="comment">
                                    <p class="username">You:</p>
                                    <p class="comment-text">{{ $user_comment->comment }}</p>
                                </div>
                            @endif
                        @endif
                        @php
                            $comments = DB::table('recipe_reviews')
                                ->join('users', 'recipe_reviews.user_id', '=', 'users.user_id')
                                ->where('recipe_reviews.recipe_id', $recipe->recipe_id)
                                ->orderBy('recipe_reviews.created_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp
                        @foreach ($comments as $comment)
                            @if (!Session::has('user_id') || $comment->user_id != Session::get('user_id'))
                                <div class="comment">
                                    <p class="username">{{ $comment->user_name }}:</p>
                                    <p class="comment-text">{{ $comment->comment }}</p>
                                </div>
                            @endif
                        @endforeach
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
                    <a href="{{ route('recipes.edit', ['id' => $recipe->recipe_id]) }}">Edit</a>
                    <a href="{{ route('recipes.delete', ['id' => $recipe->recipe_id]) }}">Delete</a>
                    <a href="#" class="favorite" data-recipe-id="{{ $recipe->recipe_id }}"><i
                            class="fas fa-heart"></i></a>
                </div>
            @endforeach

            @if (count($recipes) == 0)
                <p>No recipes found.</p>
            @endif
        </div>
    </main>

</body>

<script>
    $(document).ready(function() {
        $('.favorite').click(function(e) {
            e.preventDefault();
            var recipeId = $(this).data('recipe-id');
            var link = $(this);

            if (link.hasClass('active')) {
                $.ajax({
                    url: '/remove-favorites',
                    type: 'POST',
                    data: {
                        recipe_id: recipeId
                    },
                    success: function(response) {
                        link.removeClass('active');
                    }
                });
            } else {
                $.ajax({
                    url: '/add-favorites',
                    type: 'POST',
                    data: {
                        recipe_id: recipeId
                    },
                    success: function(response) {
                        link.addClass('active');
                    }
                });
            }
        });
    });
</script>

</html>
