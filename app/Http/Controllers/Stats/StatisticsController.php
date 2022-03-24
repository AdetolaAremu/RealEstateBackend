<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Likes;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function userStats()
    {
        $count = array("userPosts" => 0, "likedPosts" => 0, "userComments" => 0);
        $count['userPosts'] = Post::where('user_id', auth()->user()->id)->count();
        $count['likedPosts'] = Likes::where('user_id', auth()->user()->id)->count();
        $count['userComments'] = Comment::where('user_id', auth()->user()->id)->count();

        return response($count, Response::HTTP_OK);
    }

    public function adminStats()
    {
        // if (Auth::user()->role !== 'admin') {
        //     return response(['message' => 'Only admins can see this'], Response::HTTP_UNAUTHORIZED);
        // }

        $count = array("userCount" => 0, "allPosts" => 0, "allLikes", "allComments");
        $count['userCount'] = User::count();
        $count['allPosts'] = Post::count();
        $count['allLikes'] = Likes::count();
        $count['allComments'] = Comment::count();

        return response($count, Response::HTTP_OK);
    }
}
