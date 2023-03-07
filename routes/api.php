<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// user unprotected routes
Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('confirm_user_email', 'UserController@confirmEmail');
    Route::get('get_posts', 'PostController@getPosts');
    Route::get('get_post/{id}', 'PostController@getPost');
    Route::get('get_other_posts/{id}', 'PostController@getOtherPostsByAuthor');
    Route::get('get_single_post/{id}', 'PostController@post');
    Route::get('get_user/{id}', 'UserController@getUser');
    Route::get('get_user_posts/{id}', 'UserController@getUserPosts');
    Route::get('get_user_follows/{id}', 'UserController@getUserFollows');
});



// (auth user) protected routes
Route::group(['middleware' => 'jwt.auth',  'prefix' => 'auth'], function($router){
    Route::post('logout', 'AuthController@logout');
    Route::post('save_post', 'PostController@save');
    // Route::get('not_following', 'FollowController@notFollowing');
    Route::post('follow_user/{id}', 'FollowController@follow');
    Route::get('get_followers', 'FollowController@getMyFollowers');
    Route::get('get_friends_user_follow', 'FollowController@getFriendsUserFollow');
    // Route::get('get_friends_following_user', 'FollowController@getFriendsFollowingUser');
    Route::post('like_post/{id}', 'LikeController@toggleLike');

    Route::get("get_users", 'UserController@getUsers');
    Route::post("follow/{id}", 'FollowController@follow');
    Route::post("unfollow/{id}", 'FollowController@unfollow');
    Route::get('users_auth_is_following', 'FollowController@UsersAuthIsFollowing');
    Route::get('suggested_follows', 'FollowController@suggestedFollows');
    Route::post('update_post/{id}', 'PostController@updatePost');
    Route::post('delete_post/{id}', 'PostController@deletePost');
    Route::get('check_if_post_is_liked/{id}', 'PostController@checkIfLiked');
});
