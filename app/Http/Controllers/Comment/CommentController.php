<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comment = Comment::get();

        return response($comment, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate(['text' => 'required', 'post_id' => 'required']);

        Comment::create([
            "text" => $request->text,
            "post_id" => $request->post_id,
            "user_id" => Auth::user()->id
        ]);

        return response(['message' => 'Comment created successfully'], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response(['message' => 'Comment not found'], Response::HTTP_NOT_FOUND);
        }

        $comment->update($request->all());

        return response(['message' => 'Comment updated successfully'], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response(['message' => 'Comment not found'], Response::HTTP_NOT_FOUND);
        }

        return response($comment, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response(['message' => 'Comment not found'], Response::HTTP_NOT_FOUND);
        }

        $comment->delete();

        return response(['message' => 'Comment deleted successfully'], Response::HTTP_OK);
    }

    // get all comments belonging to a post
    public function postComment($id)
    {
        $post = Comment::where('post_id', $id)->with('user')->latest()->get();

        return response($post, Response::HTTP_OK);
    }
}
