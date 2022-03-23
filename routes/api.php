<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\EstateType\EstateTypeController;
use App\Http\Controllers\Like\LikeController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Ratings\RatingController;
use App\Http\Controllers\Stats\StatisticsController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'registerUser']);
Route::post('/login', [AuthController::class, 'login']);

// get all city
Route::get('/cities', [CountryController::class, 'city']);

// posts
Route::get('/all-posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

// get all comments belonging to a post
Route::get('/post-comments/{id}', [CommentController::class, 'postComment']);

// get all likes belonging to a post
Route::get('/likes/{id}', [LikeController::class, 'likesCountPerPost']);

// get all the cities in the DB for filtering purpose
Route::get('/cities', [PostController::class, 'getTheCityInDB']);

// all the types in the DB
Route::get('type/', [EstateTypeController::class, 'index']);

Route::group(['middleware' => 'auth:api'], function() {

  Route::post('/logout', [AuthController::class, 'logout']);
  
  // user
  Route::group(['prefix' => 'user'], function() {
    Route::get('/', [UserController::class, 'allUsers']);
    Route::get('/{id}', [UserController::class, 'getAUser']);
    Route::put('/info', [UserController::class, 'changeUserInfo']);
    Route::put('/password', [UserController::class, 'changeUserPassword']);
    Route::delete('/{id}', [UserController::class, 'deleteUser']);
  });

  // estate type
  Route::group(['prefix' => 'type'], function() {
    Route::post('/', [EstateTypeController::class, 'store']);
    Route::get('/{id}', [EstateTypeController::class, 'show']);
    Route::put('/{id}', [EstateTypeController::class, 'update']);
    Route::delete('/{id}', [EstateTypeController::class, 'destroy']);
  });

  // posts
  Route::group(['prefix' => 'post'], function() {
    Route::post('/', [PostController::class, 'store']);
    Route::put('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'destroy']);
  });

  // get logged in user posts
  Route::get('/my-posts', [PostController::class, 'myPosts']);

  // get logged in user each post
  Route::get('/my-each-posts/{id}', [PostController::class, 'EachLoggedInUserPost']);

  // all the login user liked posts
  Route::get('/liked-posts', [PostController::class, 'mylikedPosts']);

  // posts by types
  Route::get('/posts/type/{type}', [PostController::class, 'postsByType']);

  // search for posts
  Route::post('posts/search', [PostController::class, 'searchPost']);

  Route::group(['prefix' => 'rating'], function() {
    Route::get('/', [RatingController::class, 'index']);
    Route::post('/', [RatingController::class, 'store']);
    Route::delete('/{id}', [RatingController::class, 'destroy']);
  });

  Route::group(['prefix' => 'comment'], function() {
    Route::get('/', [CommentController::class, 'index']);
    Route::post('/', [CommentController::class, 'store']);
    Route::get('/{id}', [CommentController::class, 'show']);
    Route::put('/{id}', [CommentController::class, 'update']);
    Route::delete('/{id}', [CommentController::class, 'destroy']);
  });

  Route::group(['prefix' => 'filter'], function() {
    Route::get('/{city}', [PostController::class, 'filterByCity']);
  });

  Route::get('/ratings', [PostController::class, 'filterbyRating']);
  Route::get('/testss', [RatingController::class, 'testingit']);

  Route::group(['prefix' => 'likes'], function(){
    Route::post('/{id}/post', [LikeController::class, 'store']);
    Route::delete('/{id}/post', [LikeController::class, 'destroy']);
  });

  Route::get('/check-liked/{id}', [LikeController::class, 'checkLiked']);

  Route::get('/currentuser', [UserController::class, 'loggedInUser']);

  Route::get('/user-stats', [StatisticsController::class, 'userStats']);

  // Route::get('/{image}', [PostController::class, 'showImage']);
});