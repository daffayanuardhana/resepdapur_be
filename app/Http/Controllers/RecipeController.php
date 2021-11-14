<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    /**
     * Get The Top Recipe.
     *
     * @return JSON
     */
    public function getAllRecipe(Request $request)
    {
        // $itemPerPage =
        $length = $request->length;
        $recipes = Recipe::take(10)
            ->orderBy('views')
            ->get();
        return $recipes;
    }
}
