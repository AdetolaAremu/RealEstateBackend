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
        $post = Post::find($id);

        if (!$post) {
            return response(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }
        
        if(Likes::where('user_id', auth()->user()->id)->where('post_id', $id)->exists() ){
            return response(['message' => 'You have already liked this post'], Response::HTTP_BAD_REQUEST);
        }

        $like = new Likes();
        $like->user_id = auth()->user()->id;
        $like->post_id = $id;
        $like->save();

        return response(['message' => 'Post liked']);
    }

    public function destroy($id)
    {
        $like = Likes::where('user_id', auth()->id())->where('post_id', $id)->first();

        if (!$like) {
            return response(['message' => 'You have not liked this post'], Response::HTTP_NOT_FOUND);
        }

        $like->delete();

        return response(['message' => 'Like deleted']);
    }
}