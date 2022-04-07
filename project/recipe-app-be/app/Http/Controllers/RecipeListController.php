<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\Models\RecipeList; 
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Support\Facades\Validator;

class RecipeListController extends Controller
{

    public function index() {
        if (Auth::user()) {
            $id = Auth::user()->id;
            $recipeLists = RecipeList::where('user_id', $id)->get();
            return $recipeLists;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, no recipe lists were found'
            ]);
        }
    }

    public function get($id){
        if (auth::user()) {
            $recipeList = RecipeList::find($id);
            return $recipeList;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this list was not found'
            ]);
        }
    }

    public function store(Request $request){
        $input = $request->validate([
            'name' => 'required|string'

        ]);
        if (auth::user()) {
            $recipeList = RecipeList::create([
                'name' => $input['name'],
                'user_id' => auth::user()->id
            ]);
            return response ($recipeList, 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the recipe list could not be created'
            ]);
        }
    }

    public function addRecipe(Request $request, $id)
    {
        $recipe = $request->recipe_api_id;
        $recipeList = RecipeList::find($id);

        if (auth::user()) {
            
            if (RecipeList::where('recipe_list_id', $recipeList)->where('recipe_api_id', $recipe)->first()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This recipe is already in this list'
                ]);
            } else {
                $input = $request->all();
                $request->validate([
                    'recipe_api_id' => 'required|number',
                    'recipe_name' => 'required|string',
                    'recipe_list_id' => 'required|numeric',
                    'img' => 'nullable|string|url'
                ]);

                Recipe::create($input);
                return response()->json([
                    'success' => true,
                    'message' => 'This recipe has been added successfully'
                ]);
            }
        }
     
    }


    public function update(Request $request, $id) {
        if (auth::user()) {
            $recipeList = RecipeList::find($id);
            $recipeList->update($request->all());
            $recipeList->save();
            return $recipeList;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the list name could not be updated'
            ]);
        }
    }

    public function delete($id) {
        if (auth::user()){
            $recipeList = RecipeList::find($id);
            $recipeList->delete();
            return response()->json([
                'success' => true,
                'message' => 'The recipe list was successfully deleted'
        ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the list could not be deleted'
            ]);
        }
    }
}
