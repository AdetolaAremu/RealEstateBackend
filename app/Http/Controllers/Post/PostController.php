<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\PostImages;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
  public function index()
  {
    $posts = Post::with('images','comment','type')->withCount('likes')->get();

    return response($posts, Response::HTTP_OK);
  }

  public function store(PostRequest $request)
  {
    DB::beginTransaction();
    try {
      $post = new Post();
      $post->user_id = auth()->user()->id;
      $post->title = $request->title;
      $post->text = $request->text;
      $post->address = $request->address;
      $post->price = $request->price;
      $post->type = $request->type;
      $post->city = $request->city;
      // $post->featured = $request->featured
      $post->slug = Str::slug($request->title);
      $post->save();

      $documentURL = $request->file('images')->storePublicly('post_images', 's3');

      PostImages::create([
        "post_id" => $post->id,
        "images" => basename($documentURL),
        "url" => Storage::disk('s3')->url($documentURL)
      ]);

      DB::commit();
      return response(['message' => 'Post created successfully!'], Response::HTTP_CREATED);
    } catch (\Throwable $th) {
      DB::rollBack();      
      return response($th, Response::HTTP_BAD_REQUEST);
    }
  }

  public function update(Request $request, $id)
  {
    $post = Post::find($id);

    if (!$post) {
      return response(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
    }

    DB::beginTransaction();

    try {
      $post->title = $request->title ?? $post->title;
      $post->address = $request->address ?? $post->address;
      $post->price = $request->price ?? $post->price;
      $post->type = $request->type ?? $post->type;
      $post->city = $request->city ?? $post->city;
      $post->save();

      DB::commit();
      return response(['message' => 'Post updated successfully'], Response::HTTP_OK);
    } catch (\Throwable $th) {
      DB::rollBack();
      return response($th, Response::HTTP_BAD_REQUEST);
    }
  }

  // get logged in user estate posts
  public function myPosts()
  {
    $post = Post::where('user_id', auth()->user()->id)->with('comment','ratings','images')
      ->orderBy('created_at', 'desc')->get();

    return response($post, Response::HTTP_OK);
  }

  // get a logged user estate post
  public function EachLoggedInUserPost($id)
  {
    $post = Post::where('user_id', auth()->user()->id)->find($id);

    if (!$post) {
      return response(['message' => 'The post does not exist or it does not belong to you'], 
      Response::HTTP_NOT_FOUND);
    }

    return response($post, Response::HTTP_OK);
  }

  // get a real estate post
  public function show($id)
  {
    $id = Post::where('slug', $id)->first();

    $post = Post::with('images','comment','type','user:id,first_name,last_name,email,phone_number,username')
      ->withCount('likes')->get()->find($id);

    if (!$post) {
      return response(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
    }

    return response($post, Response::HTTP_OK);
  }

  public function destroy($id)
  {
    $post = Post::find($id);

    if (!$post) {
      return response(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
    }

    PostImages::where('post_id', $id)->each(function ($image) {
      Storage::disk('s3')->delete('post_images/'.$image->images);
    });
    
    $post->delete();

    return response(['message' => 'Post deleted'], Response::HTTP_OK);
  }

  // get all the city in the database only so we can use it to filter by city
  public function getTheCityInDB()
  {
    $post = Post::select('id','city')->groupBy('city')->get();

    return response($post, Response::HTTP_OK);
  }

  // filter by the city we have in the database
  public function filterByCity($city)
  {
    $post = Post::where('city', $city)->with('likes','images','comment')
      ->latest()->withCount('likes')->get();

    if (!$post) {
      return response(['message' => 'There are no search results for this city']);
    }

    return response($post, Response::HTTP_OK);
  }

  // get all the posts that has been liked by the logged in user
  public function mylikedPosts()
  {
    $like = Post::join('likes','likes.slug', '=', 'posts.slug')
      ->where('likes.user_id', auth()->user()->id)
      ->with('images')
      ->withCount('likes','comment')
      ->get();

    return response($like, Response::HTTP_OK);
  }

  // get post by types
  public function postsByType($type)
  {
    $post = Post::join('estate_types','estate_types.id', '=', 'posts.type')
      ->where('estate_types.name', $type)
      ->with('images','type')
      ->withCount('likes','comment')
      ->get();
    
    if (!$post) {
      return response(['message' => 'There are no search results for this category'], Response::HTTP_NOT_FOUND);
    }

    return response($post, Response::HTTP_OK);
  }

  // search for posts
  public function searchPost(Request $request)
  {
    $post = Post::where("text", "LIKE", "%" . $request->q . "%")
      ->orwhere("title", "LIKE", "%". $request->q . "%")
      ->orwhere("city", "LIKE", "%". $request->q . "%")
      ->orwhere("address", "LIKE", "%" . $request->q . "%")
      ->with('images:id,post_id,images,url','user:id,username','type')
      ->withCount('likes','comment')
      ->get();
    
    if (!$post) {
      return response(['message' => 'There are no search results for this city']);
    }
        
    return response($post, Response::HTTP_OK);
  }
}