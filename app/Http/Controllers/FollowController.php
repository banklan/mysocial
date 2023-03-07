<?php

namespace App\Http\Controllers;

use App\User;
use App\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{

    public function notFollowing(){
        $user = auth('api')->user();
        $followings = Follow::where('user_id', $user->id)->get();
        $users = User::where('id', '!=', $user->id)->get()->toArray();
        $diff = array_diff($users, $followings->toArray());
        return response()->json($diff, 200);
    }

        // follow a user
    public function follow(Request $request, $id) {
        $userToFollow = User::findOrFail($id);
        auth()->user()->follow($userToFollow);

        return response()->json(['message' => 'success'], 200);
    }

    // unfollow a user
    public function unfollow($id) {
        $userToUnfollow = User::findOrFail($id);
        auth()->user()->unfollow($userToUnfollow);

        return response()->json(['message' => 'success'], 200);
    }

    // users the auth user is currently following
    public function UsersAuthIsFollowing(){
        $auth = auth('api')->user();
        $users = User::where('id', '!=', $auth->id)->get();
        $follows = [];
        foreach ($users as $user) {
            if($auth->isFollowing($user)){
                $follows[] = $user;
            }
        }

        return response()->json($follows, 200);
    }

    //users suggested to auth user to follow
    public function suggestedFollows(){
        $auth = auth('api')->user();
        $users = User::where('id', '!=', $auth->id)->get();
        $follows = [];
        foreach ($users as $user) {
            if(!$auth->isFollowing($user)){
                $follows[] = $user;
            }
        }

        return response()->json($follows, 200);
    }
}
