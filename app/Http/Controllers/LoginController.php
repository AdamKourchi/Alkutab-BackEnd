<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    function login(Request $request) {

    
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('api-token')->plainTextToken;
    
        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first(),
            ]
        ]);
    }

    function register(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign the user a default role (e.g., 'student')
        $user->assignRole('student');
    
        $token = $user->createToken('api-token')->plainTextToken;
    
        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first(),
            ]
        ]);
    }

    function logout(Request $request) {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}

