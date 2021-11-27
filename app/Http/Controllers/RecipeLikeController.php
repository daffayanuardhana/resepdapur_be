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
        $recipe = Recipe::where('id',$id)->first();
        $like = $recipe->likes();
        if($like->get()->contains('user_id',$user->id)){
            // return 0;
            return response()->json(["message" => "already liked"],409);
        }
        // $like->create([
        //     'user_id' => $user->id
        // ]);
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
        $recipe = Recipe::where('id',$id)->first();
        $like = $recipe->likes();
        if($like->get()->contains('user_id',$user->id)){
            $liked = $like->where('user_id',$user->id);
            $liked->delete();
            return response()->json(["message" => "success"], 200);
        }
        return response()->json(["message" => "recipe isn't like"], 409);
    }

    // public function getLikeRecipes(Request $request){
    //     $user = auth()->user();
    //     $likes = $user->likes;
    //     $likedRecipe = Like::first()->recipe();
    //     return $likedRecipe;
    // }
}
