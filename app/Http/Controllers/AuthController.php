<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** sign up */
    public function signup(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password'])
        ]);

        $token = $user->createToken('secret')->plainTextToken;
        $response = ['user' => $user, 'token' => $token];
        return response($response, 201);

    }

    /** login */
    public function login(Request $request)
    {
        $input = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email',$input['email'])->first();

        if(!$user || !Hash::check($input['password'],$user->password)){
            return response(['message'=>'login failed'],401);
        }

        $token = $user->createToken('secret')->plainTextToken;
        $response = ['user' => $user, 'token' => $token];
        return response($response, 200);

    }

    /** logout */
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return ['message'=>'logged out'];
    }
}
