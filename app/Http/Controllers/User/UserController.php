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
    public function allUsers()
    {
        $user = User::get();

        return response($user, Response::HTTP_OK);
    }

    public function getAUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return response($user, Response::HTTP_OK);
    }

    public function changeUserInfo(Request $request)
    {
        $user = Auth::user();

        $documentURL = $request->file('image_file_name')->storePublicly('profile_image', 's3');
        // validation on file size for this and post image, work on it
        // check if the image file name of the user is not null, if it is not null, delete the file in aws
        if ($user->image_file_name !== null) {
            Storage::disk('s3')->delete('profile_image/'.$user->image_file_name);

            if($request->has('image_file_name')){
                $user->update($request->only('first_name', 'last_name', 'middle_name') + [
                    'image_file_name' => basename($documentURL),
                    "url" => Storage::disk('s3')->url($documentURL)
                ]);
            }
        }
       
        $user->update($request->only('first_name', 'last_name', 'middle_name'));

        return response(['message' => 'User data updated successfully!'], Response::HTTP_OK);
    }

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

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $user->delete();

        return response(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }

    public function loggedInUser()
    {
        $user = Auth::user();

        return response($user, Response::HTTP_OK);
    }
}
