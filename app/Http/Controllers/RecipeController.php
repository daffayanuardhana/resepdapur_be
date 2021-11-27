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
            'steps' => 'required|array|min:1',
        ]);
        $user = auth()->user();
        $title = $request->title;
        $img_id = $request->img_id;
        $description = $request->description;
        $stepsRequest = $request->steps;

        $recipes = $user->recipes();
        try {
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
        } catch (\Exception $e) {
            return response()->json(["message" => "database error", "error" => "$e"], 500);
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
        try {
            $recipes = Recipe::take($itemPerPage)
                ->orderBy('views')
                ->skip($startAt)
                ->get();
            $totalPage = ceil(Recipe::count() / 9);
        } catch (\Exception $e) {
            return response()->json(["message" => "database error", "error" => "$e"], 500);
        }
        return response()->json([
            "recipes" => $recipes,
            "pagination" => ["totalPage" => $totalPage, "currentPage" => $page]
        ], 200);
    }

    /**
     * Get The Recipe By id.
     *
     * @return JSON
     */
    public function getRecipeById(Request $request)
    {
        $id = $request->id;
        try {
            $recipe = Recipe::where('id', $id)->first();
            if (!$recipe) {
                return response()->json(["message" => "not found"], 404);
            }
            $steps = $recipe
                ->steps()
                ->orderBy('number')
                ->get();
        } catch (\Exception $e) {
            return response()->json(["message" => "database error", "error" => "$e"], 500);
        }
        return response()->json(["recipe" => $recipe, "steps" => $steps], 200);
    }


    /**
     * Update The Recipe By id.
     *
     * @return JSON
     */
    public function changeMyRecipe(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|min:5',
            'title' => 'required|max:255',
            'img_id' => 'required',
            'steps' => 'required|array|min:1',
        ]);
        $id = $request->id;
        $user = auth()->user();
        $title = $request->title;
        $img_id = $request->img_id;
        $description = $request->description;
        $stepsRequest = $request->steps;

        $recipes = $user->recipes();

        try {
            $recipe = $recipes->where('id', $id)->first();
            if (!$recipe) {
                return response()->json(["message" => "not found"], 404);
            }
            $recipes->update([
                'title' => $title,
                'img_id' => $img_id,
                'description' => $description,
            ]);
            $steps = $recipe->steps();
            $steps->delete();
            $number = 1;
            foreach ($stepsRequest as $desc) {
                // $step = $steps->where('number', $number);
                $steps->updateOrCreate(['number' => $number],['description' => $desc]);
                $number++;
            }
        } catch (\Exception $e) {
            return response()->json(["message" => "database error", "error" => "$e"], 500);
        }
        return response()->json(["message" => "success"], 201);
    }

    /**
     * Delete The Recipe By id.
     *
     * @return JSON
     */
    public function deleteMyRecipe(Request $request)
    {
        $id = $request->id;
        $user = auth()->user();

        $recipes = $user->recipes();

        try {
            $recipe = $recipes->where('id', $id)->first();
            if(!$recipe){
                return response()->json(["message" => "id not found"], 404);
            }
            $recipe->delete();
        } catch (\Exception $e) {
            return response()->json(["message" => "database error", "error" => "$e"], 500);
        }
        return response()->json(["message" => "success"], 205);
    }
}
