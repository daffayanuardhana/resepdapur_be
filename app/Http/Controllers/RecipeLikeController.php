<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeLikeController extends Controller
{
    /**
     * Liking Recipe
     *
     * @return JSON
     */
    public function likeRecipe(Request $request)
    {
        $user = auth()->user();
        $id = $request->id;
        $recipe = Recipe::where('id',$id)->first();
        return $id;
    }
}
