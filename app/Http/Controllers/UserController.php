<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could not create token', 500]);
        }

        $data = User::where('email', '=', $request->email)->get();
        return response()->json([
            'status' => 1,
            'message' => 'Succes login!',
            'token' => $token,
            'data' => $data
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'role' => 'required|string',
            // 'type' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        //define nama file yang akan di upload
        $imageName = time() . '.' . $request->image->extension();

        //proses upload
        $request->image->move(public_path('images'), $imageName);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'image' => $imageName,
            'role' => $request->get('role'),
            // 'type' => $request->get('type'),

        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'), 201);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user not found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token expired'], $e());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token invalid'], $e());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token absent'], $e());
        }
        // return response()->json(compact('user'));
        return response()->json([
            'status' => 1,
            'message' => 'Succes login!',
            'data' => $user
        ]);
    }
}
