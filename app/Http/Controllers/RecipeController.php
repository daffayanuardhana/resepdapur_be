<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Step;
use App\Models\User;

class RecipeController extends Controller
{

    /**
     * Create Recipe 
     *
     * @return JSON
     */
    public function createRecipe(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|min:5',
            'title' => 'required|max:255',
            'img_id' => 'required',
            'steps' => 'required|array',
        ]);
        $user = auth()->user();
        $title = $request->title;
        $img_id = $request->img_id;
        $description = $request->description;

        $stepsRequest = $request->steps;
        $recipes = $user->recipes();

        $recipesModel = $recipes->create([
            'title' => $title,
            'img_id' => $img_id,
            'description' => $description,
        ]);


        $steps = $recipesModel->steps();
        $number = 1;
        foreach ($stepsRequest as $step) {
            $steps->create([
                'description' => $step,
                'number' => $number
            ]);
            $number++;
        }


        return response()->json(["message" => "success"], 201);
    }

    /**
     * Get The Recipe By Page.
     *
     * @return JSON
     */
    public function getAllRecipe(Request $request)
    {
        $itemPerPage = 9;
        $page = $request->get('page');
        $startAt = $itemPerPage * ($page - 1);
        $recipes = Recipe::take($itemPerPage)
            ->orderBy('views')
            ->skip($startAt)
            ->get();
        $totalPage = ceil(Recipe::count() / 9);
        return response()->json(["recipes" => $recipes, "pagination" => ["totalPage" => $totalPage, "currentPage" => $page]]);
    }

    /**
     * Get The Recipe By id.
     *
     * @return JSON
     */
    public function getRecipeById(Request $request)
    {
        $id = $request->id;
        $recipe = Recipe::where('id',$id)->first();
        $steps = $recipe
            ->steps()
            ->orderBy('number')
            ->get();
        return response()->json(["recipe" => $recipe, "steps" => $steps]);
    }
}
