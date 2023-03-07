<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // save a new post
    public function save(Request $request){ //save post
        // validate the incoming post request
        $this->validate($request, [
            'post' => 'required|min:1|max:1000'
        ]);

        // if passed validation, save to the db and return a response
        $user = auth('api')->user()->id;

        $post = new Post;
        $post->content = $request->post;
        $post->user_id = $user;
        $post->save();
        $post = $post->fresh();
        return response()->json($post, 200);
    }

    // get all the posts
    public function getPosts(){
        $posts = Post::all();

        return response()->json($posts, 200);
    }

        // get a single post
    public function getPost($id){
        $post = Post::findOrFail($id);

        return response()->json($post, 200);
    }

    // other posts by the same author
    public function getOtherPostsByAuthor($id){
        $post = Post::findOrFail($id);
        $author = $post->user_id;
        $others = Post::where('user_id', $author)->where('id', '!=', $id)->get();

        return response()->json($others, 200);
    }

    public function updatePost(Request $request, $id){
        // validate incoming request
        $this->validate($request, [
            'post' => 'required|min:1|max:1000'
        ]);

        // if passed validation, get user and request, update post
        $user = auth('api')->user()->id;

        $post = Post::findOrFail($id);
        $post->update([
            $post->content = $request->post
        ]);

        return response()->json($post, 200);
    }

    public function deletePost($id){
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }

    public function checkIfLiked($id){
        $auth = auth('api')->user();
        $like = Like::where('user_id', $auth->id)->where('post_id', $id)->first();
        if($like){
            return response()->json(['message' => 1], 200);
        }else{
            return response()->json(['message' => 0], 200);
        }
    }
}
