<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Provider;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // register user
    public function register(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create Provider linked to this User
        $provider = Provider::create([
            'name' => $user->name,
            'email' => $user->email,
            'user_id' => $user->id,   // (Make sure provider table has user_id column)
        ]);

        // Create Inventory for the Provider
        $provider->inventory()->create([
            'stock' => 100,  // default stock
        ]);

        // Return response with token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User, Provider & Inventory created successfully!',
            'user' => $user,
            'provider' => $provider,
            'token' => $token,
        ], 201);
    }
    
    // login user
    public function login(Request $request)
    {
        // Validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check user exists
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Create Sanctum Token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
