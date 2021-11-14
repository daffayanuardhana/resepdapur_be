<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    /**
     * Create Recipe 
     *
     * @return JSON
     */
    public function createRecipe(Request $request)
    {
        $user = auth()->user();
        $title = $request->title;
        $img_id = $request->img_id;
        $description = $request->description;
        $steps = $request->$user->recipes()->create([
                'title' => $title,
                'img_id' => $img_id,
                'description' => $description,
            ]);

        return response()->json($user->recipes);
    }
    /**
     * Get The Recipe By Page.
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
            ->skip($startAt)
            ->get();
        return $recipes;
    }
}
