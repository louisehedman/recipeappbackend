<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecipeList;


class RecipeId extends Model
{
    use HasFactory;
    protected $fillable = [
        'recipe_api_id',
        'recipe_name',
        'recipe_list_id',
        'img'
        ];

        public function recipeList()
    {
        return $this->belongsTo(RecipeList::class);
    }
}
