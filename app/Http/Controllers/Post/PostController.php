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
class PostController extends Controller
{
  public function index()
  {
    $posts = Post::with('images','comment','avgRating')->withCount('ratings')->get();

    return response($posts, Response::HTTP_OK);
  }

  public function store(PostRequest $request)
  {
    DB::beginTransaction();
    try {
      $post = new Post();
      $post->user_id = auth()->user()->id;
      $post->text = $request->text;
      $post->address = $request->address;
      $post->price = $request->price;
      $post->type = $request->type;
      $post->country_id = $request->country_id;
      $post->state_id = $request->state_id;
      $post->city_id = $request->city_id;
      $post->save();

      for ($i=0; $i < count($request->postimg); $i++) {

        $request->validate(['images'=>'mimes:png,jpg,jpeg']);

        $file = $request->postimg[$i];
        $imageName = time() . Str::random(4) . '.' . $request->postimg[$i]->extension();
        $path = "images/realestateimages";
        $documentURL = $path . '/' . $imageName;
        $file->move($path, $imageName);

        PostImages::create([
          "post_id" => $post->id,
          "images" => $documentURL,
        ]);
      }

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
      return response(['message' => 'Post not found']);
    }

    DB::beginTransaction();

    try {
      $post->text = $request->text ?? $post->text;
      $post->address = $request->address ?? $post->address;
      $post->price = $request->price ?? $post->price;
      $post->type = $request->type ?? $post->type;
      $post->country_id = $request->country_id ?? $post->country_id;
      $post->state_id = $request->state_id ?? $post->state_id;
      $post->city_id = $request->city_id ?? $post->city_id;
      $post->save();

      if ($request->has('postimg')) {
        for ($i=0; $i < count($request->postimg); $i++) { 
          
          PostImages::where('post_id', $id)->delete();
        
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

  public function myPosts()
  {
    $post = Post::where('user_id', auth()->user()->id)->with('comment','ratings','images')
      ->orderBy('created_at', 'desc')->get();

    return response($post, Response::HTTP_OK);
  }

  public function show($id)
  {
    $post = Post::with('images','comment','avgRating')->get()->find($id);

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

    $post->delete();

    return response(['message' => 'Post deleted'], Response::HTTP_OK);
  }

  public function filterbyCountry($country)
  {
    $post = Post::where('country_id', $country)->with('avgRating','images','comment')
      ->orderBy('created_at', 'desc')->get();
      // ->sortBy('avgRating');

    return response($post, Response::HTTP_OK);
  }

  public function filterbyState($state)
  {
    $post = Post::where('state_id', $state)->with('avgRating','images','comment')
      ->orderBy('created_at', 'desc')->get();

    return response($post, Response::HTTP_OK);
  }

  public function filterbyCity($city)
  {
    $post = Post::where('city_id', $city)->with('avgRating','images','comment')
      ->orderBy('created_at', 'desc')->get();

    return response($post, Response::HTTP_OK);
  }
}