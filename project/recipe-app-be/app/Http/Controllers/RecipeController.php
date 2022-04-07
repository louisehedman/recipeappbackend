<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Recipe;
use App\Models\RecipeList;
use Illuminate\Http\Request;

class RecipeController extends Controller {
    public function index(RecipeList $recipeList)
    {
        $recipeList = RecipeList::where('user_id', auth::user()->id)->get();

        if (auth::user()) {
            $recipes = $recipeList->recipes;


            if ($recipes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This list does not have any recipes yet.'
                ], 200);
            } else {
                return response()->json([
                    'success' => true,
                    'recipes' => $recipes
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No recipe list with this id belongs to current user.'
            ], 401);
        }
    }

    public function store(Request $request, RecipeList $recipeList) {
        
        if (auth::user()) {
            $validator = Validator::make($request->only('recipe_api_id', 'title', 'img'), [
                'title' => 'required|string',
                'recipe_api_id' => 'required|numeric',
                'img' => 'nullable|string|url'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 200);
            }

            $recipe = Recipe::where('recipe_api_id', $request->recipe_api_id)->first();
    
            if (!$recipe) {
                $recipe = $recipeList->recipes()->create([
                    'title' => $request->title,
                    'recipe_api_id' => $request->recipe_api_id,
                    'img' => $request->img
                ]);
            } else {

                if ($recipeList->recipes()->where('recipe_id', $recipe->id)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The recipe is already in this list.'
                    ], 200);
                } else {
                    $recipeList->recipes()->attach($recipe->id);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Recipe successfully saved to list.',
                'recipe' => $recipe
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No recipe list with this id belongs to current user.'
            ], 401);
        }
    }

    public function checkIfExists(RecipeList $recipeList, $apiId)
    {
        if (auth::user()) {

            $recipe = Recipe::where('api_id', $apiId)->first();

            if (!$recipe) {
                return response()->json([
                    'success' => true,
                    'exists' => false,
                    'message' => 'This recipe does not exist in any list.'
                ], 200);
    
            } else {
                if ($recipeList->recipes()->where('recipe_id', $recipe->id)->doesntExist()) {
                    return response()->json([
                        'success' => true,
                        'exists' => false,
                        'message' => 'This recipe does not exist in any of this users lists.'
                    ], 200);

                } else {
                    return response()->json([
                        'success' => true,
                        'exists' => true,
                        'recipe' => $recipe
                    ], 200);
                }
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => 'No recipe list with this id belongs to current user.'
            ], 401);
        }
    }

    public function listsWithRecipe($recipeApiId)
    {
        $recipeList = auth::user()->recipeList->whereHas('recipes', function ($query) use ($recipeApiId) {
            $query->where('recipe_api_id', '=', intval($recipeApiId));
        })->get();

        return response()->json([
            'success' => true,
            'list' => $recipeList
        ], 200);
    }

    public function destroy(RecipeList $recipeList, Recipe $recipe)
    {
        if (auth::user()) {
            if ($recipeList->recipes()->where('recipe_id', $recipe->id)->doesntExist()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No recipe with this id exists in current list.'
                ], 200);
            } else {
                $recipeList->recipes()->detach($recipe->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Recipe successfully removed from list'
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No recipe list with this id belongs to current user.'
            ], 401);
        }
    }
}
