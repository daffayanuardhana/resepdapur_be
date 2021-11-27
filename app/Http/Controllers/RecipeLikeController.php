<?php

namespace App\Http\Controllers;

use App\Models\Like;
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
        $recipe = Recipe::where('id', $id)->first();
        $like = $recipe->likes();
        if ($like->get()->contains('user_id', $user->id)) {
            return response()->json(["message" => "already liked"], 409);
        }
        $like->create([
            'user_id' => $user->id
        ]);
        return response()->json(["message" => "success"], 200);
    }

    /**
     * Unliking Recipe
     *
     * @return JSON
     */

    public function unlikeRecipe(Request $request)
    {
        $user = auth()->user();
        $id = $request->id;
        try {
            $recipe = Recipe::where('id', $id)->first();
            $like = $recipe->likes();
            if ($like->get()->contains('user_id', $user->id)) {
                $liked = $like->where('user_id', $user->id);
                $liked->delete();
                return response()->json(["message" => "success"], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["message" => "database error", "error" => "$e"], 500);
        }
        return response()->json(["message" => "recipe isn't like"], 409);
    }

    public function getLikeRecipes(Request $request)
    {
        $itemPerPage = 9;
        $page = $request->get('page');
        $startAt = $itemPerPage * ($page - 1);
        try {
            $user = auth()->user();
            $likes = $user->likes->all();
            $likedRecipeId = array();

            foreach ($likes as $like)
                array_push($likedRecipeId, $like->recipe_id);

            $likedRecipe = Recipe::whereIn('id', $likedRecipeId)
                ->orderBy('views')
                ->take($itemPerPage)
                ->skip($startAt)
                ->get();
            $totalLiked = Recipe::whereIn('id', $likedRecipeId)->count();
            $totalPage = ceil($totalLiked / $itemPerPage);
        } catch (\Exception $e) {
            return response()->json(["message" => "database error", "error" => "$e"], 500);
        }
        return response()->json([
            "recipes" => $likedRecipe,
            "pagination" => ["totalPage" => $totalPage, "currentPage" => $page]
        ], 200);
    }
}
