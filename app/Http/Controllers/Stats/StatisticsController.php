<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Likes;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
}
