<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "name" => "nullable|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|string|confirmed"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $users = User::create([
            "name"=> $request->name,
            "email"=> $request->email,
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "message" => "User created successfully",
            "user" => $users
        ], 201);

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "email" => "required|string|email",
            "password" => "required|string"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where("email", $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "message" => "Invalid credentials"
            ], 401);
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "token" => $token,
            "token_type" => "Bearer"
        ], 200);
    }
}
