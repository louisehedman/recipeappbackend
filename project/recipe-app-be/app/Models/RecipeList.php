<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RecipeList extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'recipe_id',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
