<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\EstateType\EstateTypeController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Ratings\RatingController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'registerUser']);
Route::post('/login', [AuthController::class, 'login']);

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
    Route::get('/', [EstateTypeController::class, 'index']);
    Route::post('/', [EstateTypeController::class, 'store']);
    Route::get('/{id}', [EstateTypeController::class, 'show']);
    Route::put('/{id}', [EstateTypeController::class, 'update']);
    Route::delete('/{id}', [EstateTypeController::class, 'destroy']);
  });

  // posts
  Route::group(['prefix' => 'post'], function() {
    Route::get('/', [PostController::class, 'index']);
    Route::post('/', [PostController::class, 'store']);
    Route::put('/{id}', [PostController::class, 'update']);
    Route::get('/{id}', [PostController::class, 'show']);
    Route::delete('/{id}', [PostController::class, 'destroy']);
  });

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

  Route::get('/currentuser', [UserController::class, 'loggedInUser']);

});