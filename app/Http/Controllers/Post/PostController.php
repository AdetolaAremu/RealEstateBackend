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
use Illuminate\Support\Facades\File;
class PostController extends Controller
{
  public function index()
  {
    $posts = Post::with('images','comment')->withCount('likes')->get();

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
      $post->save();

      $file = $request->file('images');
      $imageName = time() . Str::random(4) . '.' . $request->file('images')->extension();
      $path = "home/assets/postimages";
      $documentURL = $path . '/' . $imageName;
      $file->move($path, $imageName);

      // $path = $request->file('image')->store('post_images', 's3');

      PostImages::create([
        "post_id" => $post->id,
        "images" => $documentURL,
      ]);

      // for ($i=0; $i < count($request->postimg); $i++) {

      //   $request->validate(['images'=>'mimes:png,jpg,jpeg']);

      //   $file = $request->postimg[$i];
      //   $imageName = time() . Str::random(4) . '.' . $request->postimg[$i]->extension();
      //   $path = "images/realestateimages";
      //   $documentURL = $path . '/' . $imageName;
      //   $file->move($path, $imageName);

      //   PostImages::create([
      //     "post_id" => $post->id,
      //     "images" => $documentURL,
      //   ]);
      // }


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

      if ($request->has('postimg')) {
        for ($i=0; $i < count($request->postimg); $i++) { 
          
          PostImages::where('post_id', $id)->each(function ($image) {
            if (File::exists(public_path($image->images))) {
                File::delete(public_path($image->images));
            }
          });
        
          $file = $request->postimg[$i];
          $imageName = time() . Str::random(4) . '.' . $request->postimg[$i]->extension();
          $path = "images/realestateimages";
          $documentURL = $path . '/' . $imageName;
          $file->move($path, $imageName);

          PostImages::create([
            "post_id" => $id,
            "images" => $documentURL,
          ]);
        }
      }

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
    $post = Post::with('images','comment')->withCount('likes')->get()->find($id);

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
      if (File::exists(public_path($image->images))) {
          File::delete(public_path($image->images));
      }
    });

    $post->images()->delete();
    
    $post->delete();

    return response(['message' => 'Post deleted'], Response::HTTP_OK);
  }

  public function filterbyCity($city)
  {
    $post = Post::where('city_id', $city)->with('avgRating','images','comment')
      ->orderBy('created_at', 'desc')->get();

    return response($post, Response::HTTP_OK);
  }

  public function mylikedPosts()
  {
    $like = Post::join('likes','likes.post_id', '=', 'posts.id')
      ->where('likes.user_id', auth()->user()->id)
      ->with('images')
      ->withCount('likes','comment')
      ->get();

    return response($like, Response::HTTP_OK);
  }

  public function postsByType()
  {
    $like = Post::join('estate_types','estate_types.id', '=', 'posts.type')
      ->with('images','type')
      ->withCount('likes','comment')
      ->get();

    return response($like, Response::HTTP_OK);
  }

  // search for posts
  public function searchPost(Request $request)
  {
    $post = Post::where("text", "LIKE", "%" . $request->q . "%")
        ->orwhere("address", "LIKE", "%" . $request->q . "%")
        ->with('images:id,post_id,images','user:id,username', 'city:id,name')
        ->withCount('likes','comment')
        ->get();
        
    return $post;
  }
}