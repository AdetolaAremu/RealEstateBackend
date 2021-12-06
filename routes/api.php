<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'registerUser']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function() {

  Route::post('/logout', [AuthController::class, 'logout']);
  
  // everything related to the user
  Route::group(['prefix' => 'user'], function() {
    Route::get('/', [UserController::class, 'allUsers']);
    Route::get('/{id}', [UserController::class, 'getAUser']);
    Route::put('/info', [UserController::class, 'changeUserInfo']);
    Route::put('/password', [UserController::class, 'changeUserPassword']);
    Route::delete('/{id}', [UserController::class, 'deleteUser']);
  });

  // posts here then comments

  Route::get('/currentuser', [UserController::class, 'loggedInUser']);

});