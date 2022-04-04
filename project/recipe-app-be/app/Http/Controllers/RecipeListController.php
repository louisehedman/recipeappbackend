<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\Models\RecipeList; 
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RecipeListController extends Controller
{
    public function __construct() {

    }

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
        $input = $request->all();
            $request->validate([
                'user_id' => 'required',
                'name' => 'required'
            ]);

        if (auth::user()) {
            $recipeList = RecipeList::create([
                'name' => $input['name'],
                'user_id' => auth::user()->id
            ]);
            return response()->json([
                'success' => true,
                'recipeList' => $recipeList
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the recipe list could not be created'
            ]);
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
