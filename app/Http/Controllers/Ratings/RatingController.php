<?php

namespace App\Http\Controllers\Ratings;

use App\Http\Controllers\Controller;
use App\Models\PostRating;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RatingController extends Controller
{
    public function index()
    {
        $rating = PostRating::get();

        return response($rating, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|between:1,5'
        ]);

        $check = PostRating::where('user_id', auth()->user()->id);

        if ($check) {
            return response(['message' => 'You have already rated this listing']);
        }

        $rating = new PostRating();
        $rating->post_id = $request->post_id;
        $rating->rating = $request->rating;
        $rating->user_id = auth()->user()->id;
        $rating->save();

        return response(['message' => 'Success, post rated']);
    }

    public function destroy($id)
    {
        $rating = PostRating::find($id);

        if (!$rating) {
            return response(['message' => 'Rating not found']);
        }

        $rating->delete();

        return response(['message' => 'Rating deleted successfully']);
    }
}