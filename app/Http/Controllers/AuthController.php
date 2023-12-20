<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Method to register a new user
    public function register(Request $request)
    {
        try {
            // Method to register a new user
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);

            // Create a new user
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            // Return a success response
            return response()->json(['user' => $user, 'message' => 'User registered successfully!'], 201);
        
        } catch (ValidationException $e) {
            // Return an error response
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Method to log in a user
    public function login(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Get the credentials from the request
            $credentials = $request->only('email', 'password');

            // Attempt to authenticate the user
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('auth-token')->plainTextToken;

                // Return a success response with the user and token
                return response()->json(['user' => $user, 'token' => $token]);
            }

            // Return an error response
            return response()->json(['message' => 'Invalid credentials'], 401);

        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    // Method to get the authenticated user
    public function getUser(Request $request)
    {
        return $request->user();
    }
}

    
