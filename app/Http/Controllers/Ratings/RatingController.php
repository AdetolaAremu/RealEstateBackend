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
            'rating' => 'required|between:1,5|integer'
        ]);

        $check = PostRating::where('user_id', auth()->user()->id)->first();

        if ($check) {
            return response(['message' => 'You have already rated this listing']);
        }

        $ratings = new PostRating();
        $ratings->post_id = $request->post_id;
        $ratings->rating = $request->rating;
        $ratings->user_id = auth()->user()->id;
        $ratings->save();

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

    public function testingit()
    {
        return PostRating::join('posts', 'posts.id', '=', 'post_ratings.post_id')
      ->selectRaw('*, avg(post_ratings.rating) as average_rating')
      ->orderBy('average_rating', 'desc')
    //   ->with('comment')
      ->get();
    }
}