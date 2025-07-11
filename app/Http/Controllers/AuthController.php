<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{

    public function index()
    {
        return $this->sendResponse(new UserResource(Auth::user()), extra: ['token' => Auth::user()->createToken('App')->plainTextToken]);
    }

    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        // Login attempt
        $data = $validator->getData();
        if (!Auth::attempt($data))
            return $this->sendError('Auth error', ['error' => 'Unauthorized']);

        // Return token
        $user = Auth::user();
        $token = $user->createToken('App')->plainTextToken;
        return $this->sendResponse($user, 'Logged in', extra: ['token' => $token]);
    }

    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed:confirm-password',
            'confirm-password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        // Signup attempt
        $data = $validator->getData();
        $user = new User($data);
        $user->save();
        Auth::login($user);

        // Return token
        $user = Auth::user();
        $token = $user->createToken('App')->plainTextToken;
        return $this->sendResponse($user,
            'Registered',
            201,
            ['token' => $token]
        );
    }

    public function reset(Request $request)
    {

    }
}
