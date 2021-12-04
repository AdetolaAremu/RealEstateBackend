<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request)
    {
        User::create($request->all());

        return response(['message' => 'Registration successful!'], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        if ($request->has('email')) {

            $request->validate(['email' => 'required', 'password' => 'required']);

            if (Auth::attempt($request->only('email', 'password'))) {

                $user = Auth::user();

                $token = $user->createToken('user')->accessToken;

                $cookie = cookie('jwt', $token, 7200);

                return response(['token' => $token, 'message' => 'Login successful'], Response::HTTP_OK)->withCookie($cookie);
            }
        }

        if ($request->has('username')) {

            $request->validate(['username' => 'required', 'password' => 'required']);

            if (Auth::attempt($request->only('username', 'password'))) {
                $user = Auth::user();

                $token = $user->createToken('user')->accessToken;

                $cookie = cookie('jwt', $token, 7200);

                return response(['token' => $token, 'message' => 'Login successful'], Response::HTTP_OK)->withCookie($cookie);
            }
        }

        
    }

    public function changeUserInfo(Request $request)
    {
        $user = Auth::user();

        $user->update($request->only('first_name', 'last_name', 'middle_name'));

        return response(['message' => 'User data updated successfully!']);
    }

    public function changeUserPassword(Request $request)
    {
        $request->validate([
            'password' => 'required', 
            'confirm_password' => 'required|same:password'
        ]);

        $user = Auth::user();

        $user->update($request->only('password', 'confirm_password'));

        return response(['message' => 'Password updated successfully!']);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response(['message' => 'Logout successful'], Response::HTTP_OK)->withCookie($cookie);
    }
}