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
        'user_id'
    ];

    /*public function user(){
        return $this->belongsTo(User::class);
    }*/

    public function recipe()
    {
        return $this->hasMany(Recipe::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
