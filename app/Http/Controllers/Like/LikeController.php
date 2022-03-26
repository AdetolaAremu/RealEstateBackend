<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Models\Likes;
use App\Models\Post;
use Illuminate\Http\Response;
class LikeController extends Controller
{
    public function store($id)
    {
        $getPostID = Post::where('id', $id)->first();

        $post = Post::find($id);

        // dd($post->id);

        if (!$post) {
            return response(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }
        
        if(Likes::where('user_id', auth()->user()->id)->where('post_id', $id)->exists()){
            return response(['message' => 'You have already liked this post'], Response::HTTP_BAD_REQUEST);
        }

        $like = new Likes();
        $like->post_id = $getPostID->id;
        $like->user_id = auth()->user()->id;
        $like->save();

        return response(['message' => 'Post liked']);
    }

    public function likesCountPerPost($id)
    {
        $like = Likes::where('post_id', $id)->count();

        return response($like, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $like = Likes::where('user_id', auth()->id())->where('post_id', $id)->first();

        if (!$like) {
            return response(['message' => 'You have not liked this post!'], Response::HTTP_NOT_FOUND);
        }

        $like->delete();

        return response(['message' => 'You have unliked this post!']);
    }

    public function checkLiked($id)
    {
        $check = Likes::where('user_id', auth()->user()->id)->where('post_id', $id)->exists();

        if (!$check) {
            return response(0, Response::HTTP_OK);
        }

        return response(1, Response::HTTP_OK);
    }
}