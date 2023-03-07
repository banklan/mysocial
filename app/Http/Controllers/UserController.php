<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(){
        $auth = auth('api')->user()->id;
        $users = User::where('id', '!=', $auth)->get();
        return response()->json($users, 200);
    }

    public function getUser($id){
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function getUserPosts($id){
        $posts = Post::where('user_id', $id)->get();
        return response()->json($posts, 200);
    }

    // get users following the author of a post
    public function getUserFollows($id){
        $author = User::findOrFail($id);
        $users = User::all();
        $followers = [];

        foreach ($users as $user) {
            if($user->isFollowing($author)){
                $followers[] = $user;
            }
        }

        return response()->json($followers, 200);
    }
}
