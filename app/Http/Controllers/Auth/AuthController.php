<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerUser(RegistrationRequest $request)
    {
        $user = User::create($request->only(
            'first_name', 'last_name', 'middle_name', 'username', 'email', 'phone_number'
        ) + [
            "role" => 'admin',
            'password' => Hash::make($request->input('password'))
        ]);

        dd($user);

        return response(['message' => 'Registration successful'], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $request->validate(['username' => 'required', 'password' => 'required']);

        if (Auth::attempt($request->only('username', 'password'))) {
            $user = Auth::user();

            $token = $user->createToken('user')->accessToken;

            $cookie = cookie('jwt', $token, 7200);

            return response(['token' => $token, 'message' => 'Login successful'], Response::HTTP_OK)->withCookie($cookie);
        }

        return response(["error" => "Username and Password do not match"], Response::HTTP_BAD_REQUEST);   
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response(['message' => 'Logout successful'], Response::HTTP_OK)->withCookie($cookie);
    }
}