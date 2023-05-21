<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function sign_up(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json($res, 201);
    }

    public function login(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'msg' => 'incorrect username or password'
            ], 401);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($res, 201);
    }

    public function logout(Request $request, User $user)
    {
        $user->tokens()->delete();

        return response()->json([
            'message' => 'you have been logged out',
        ]);
    }
}
