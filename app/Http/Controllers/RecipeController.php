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
        $itemPerPage = 9;
        $pageNumber = $request->pageNumber;
        $startAt = $itemPerPage * ($pageNumber - 1);
        $recipes = Recipe::take($itemPerPage)
            ->orderBy('views')
            ->take($itemPerPage)
            ->skip($startAt)
            ->get();
        return $recipes;
    }
}
