<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleLike(Request $request, $id){
        $user = auth('api')->user()->id;
        $liked = Like::where('user_id', $user)->where('post_id', $id)->first();
        if($liked){
            $liked->delete();
            return response()->json(['message' => 'unliked'], 200);
        }else{
            $like = new Like;
            $like->user_id = $user;
            $like->post_id = $id;
            $like->save();
            return response()->json(['message' => 'liked'], 200);
        }
    }
}
