<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // get all users
    public function getUsers(){
        $auth = auth('api')->user()->id;
        $users = User::where('id', '!=', $auth)->get();
        return response()->json($users, 200);
    }

    //get single user
    public function getUser($id){
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    //post by user
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

    //get users the user is following
    public function getUserFollowings($id){
        $user = User::findOrFail($id);
        
        $follows = $user->following()->get();
        return response()->json($follows, 200);
    }
}
