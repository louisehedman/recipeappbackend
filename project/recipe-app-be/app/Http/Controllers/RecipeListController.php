<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RecipeList;


class RecipeListController extends Controller
{
    // Get all recipelists that belongs to the user
    public function index()
    {
        if (Auth::user()) {
            $id = Auth::user()->id;
            $recipeLists = RecipeList::where('user_id', $id)->get();
            return response($recipeLists, 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, no recipe lists were found'
            ], 401);
        }
    }

    // Get one list 
    public function get($id)
    {
        if (auth::user()) {
            $recipeList = RecipeList::find($id);
            return response($recipeList, 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this list was not found'
            ], 401);
        }
    }

    // Create new recipe list
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string'

        ]);
        if (auth::user()) {
            $recipeList = RecipeList::create([
                'name' => $input['name'],
                'user_id' => auth::user()->id
            ]);
            return response($recipeList, 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the recipe list could not be created'
            ], 401);
        }
    }

    // Update recipe list name
    public function update(Request $request, $id)
    {
        if (auth::user()) {
            $recipeList = RecipeList::find($id);
            $recipeList->update($request->all());
            return response($recipeList, 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the recipe list name could not be updated'
            ], 401);
        }
    }

    // Delete recipe list
    public function delete($id)
    {
        if (auth::user()) {
            $recipeList = RecipeList::find($id);
            $recipeList->delete();
            return response()->json([
                'success' => true,
                'message' => 'The recipe list was successfully deleted'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the list could not be deleted'
            ], 401);
        }
    }
}
