<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\User;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{    
    public function profile(Request $request) {
        return response()->json(auth()->user());
    }

    public function register(Request $request) {            
        $validator = Validator::make($request->all(), [
            'first_name' =>'required|string',
            'last_name' => 'required|string',
            'email' =>'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'in:male,female',
            'date_of_birth' => 'required|date'
        ]);

        if ($validator->fails()) 
            return response()->json(ResponseUtils::build(400, $validator->errors(), true), 400);

        try 
        {
            $user = User::create(array_merge($validator->validated(), ['password' => bcrypt($request->password)]));
            return response()->json(ResponseUtils::build(201, "User registered successfully", false, $user), 201);
        } catch (Exception $e) {
            return response()->json(ResponseUtils::build(500, $e->getMessage(), true), 500);
        }                
    }

    public function login(Request $request) { 
        $validator = Validator::make($request->all(), [
            'email' =>'required|string|email',
            'password' => 'required|string'
        ]);
        
        if ($validator->fails())
            return response()->json(ResponseUtils::build(400, $validator->errors(), true), 422);

        if(!$token=auth()->attempt($validator->validated())) 
            return response()->json(ResponseUtils::build(401, "Invalid credentials", true), 401);
        
        $newToken = $this->createNewToken($token);    
        
        return response()->json(ResponseUtils::build(200, "Logged-in successfully", false, $newToken), 200);        
    }

    public function createNewToken($token) {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24,
            'user' => auth()->user()
        ];
    }

    public function logout(Request $request) {         
        auth()->logout();
        return response()->json(ResponseUtils::build(200, "Logged-out successfully", false, null));
    }
}