<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        Comment::create($request->all());

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
}
