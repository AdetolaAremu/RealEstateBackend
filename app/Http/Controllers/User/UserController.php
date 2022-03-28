<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    // get all users
    public function allUsers()
    {
        if (Auth::user()->role !== 'admin') {
            return response(['message' => 'Only admins can see this'], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::get();

        return response($user, Response::HTTP_OK);
    }

    // get a user
    public function getAUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return response($user, Response::HTTP_OK);
    }

    // update a user
    public function changeUserInfo(Request $request)
    {
        $user = Auth::user();

        // $documentURL = $request->file('image_file_name')->storePublicly('profile_images', 's3');

        // $user->image_file_name = basename($documentURL);
        // $user->url = Storage::disk('s3')->url($documentURL);
        // $user->save();

        $user->first_name = $request->first_name ?? $user->first_name;
        $user->last_name = $request->last_name ?? $user->last_name;
        $user->middle_name = $request->middle_name ?? $user->middle_name;
        $user->update();

        return response(['message' => 'User data updated successfully!'], Response::HTTP_OK);
    }

    // update a user password
    public function changeUserPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required', 
            'new_password' => 'required', 
            'confirm_new_password' => 'required|same:new_password'
        ]);

        $user = Auth::user();

        $user->new_password = Hash::make($request->new_password);

        return response(['message' => 'Password updated successfully!'], Response::HTTP_OK);
    }

    // delete a user
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return response(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }

    // get logged in user/current user
    public function loggedInUser()
    {
        $user = Auth::user();

        return response($user, Response::HTTP_OK);
    }
}
