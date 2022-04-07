<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\RecipeId;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RecipeList extends Model
{
    use HasFactory;
    protected $table = 'recipe_lists';
    public $timestamps = true;

    protected $fillable = [
        'name'
        //'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_lists_recipes', 'recipe_list_id', 'recipe_id');
    }
}
