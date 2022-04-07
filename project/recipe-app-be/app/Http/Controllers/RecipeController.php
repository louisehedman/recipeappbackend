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
            $user = $recipeList->user;

            if ($user->id === auth::user()->id) {
            $recipes = $recipeList->recipes;

            if ($recipes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This list is empty'
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
                'message' => 'Could not find list'
            ], 401);
        }
    }

    public function addRecipe(Request $request, RecipeList $recipeList) {
        
        $user = $recipeList->user;

        if ($user->id === auth()->user()->id) {
        
            $validator = Validator::make($request->only('recipe_api_id', 'title', 'img'), [
                'title' => 'required|string',
                'recipe_api_id' => 'required|numeric',
                'img' => 'nullable|string|url'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
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
                        'message' => 'This recipe does already exist in this list.'
                    ], 200);
                } else {
                    $recipeList->recipes()->attach($recipe->id);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'This recipe was successfully saved to list.',
                'recipe' => $recipe
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Could not find recipe list.'
            ], 401);
        }
    }


    public function delete(RecipeList $recipeList, Recipe $recipe) {
        
        $user = $recipeList->user;

        if ($user->id === auth()->user()->id) {
            if ($recipeList->recipes()->where('recipe_id', $recipe->id)->doesntExist()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No recipe with this id exists in current list.'
                ], 200);
            } else {
                $recipeList->recipes()->detach($recipe->id);

                return response()->json([
                    'success' => true,
                    'message' => 'This recipe was successfully deleted from list'
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Could not find this recipe list.'
            ], 401);
        }
    }
}
