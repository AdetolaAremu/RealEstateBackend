<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

        $user->update($request->only('first_name', 'last_name', 'middle_name'));

        return response(['message' => 'User data updated successfully!'], Response::HTTP_OK);
    }

    public function changeUserPassword(Request $request)
    {
        $request->validate([
            'password' => 'required', 
            'confirm_password' => 'required|same:password'
        ]);

        $user = Auth::user();

        $user->update($request->only('password', 'confirm_password'));

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
